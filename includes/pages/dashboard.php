<?php

function getDashboardTitle()
{
    return _('Dashboard');
}

/**
 * Main function to create the parameters passed to the view.
 *
 * @return array
 */
function get_dashboard()
{
    global $privileges;

    require_once realpath(__DIR__ . '/../controller/api.php');
    $shifts = getAllUpcomingShifts();

    $viewData = array(
        'number_upcoming_shifts' => block(
            array(
                'description' => _("Angels needed in the next 3 hrs"),
                'number' => countUpcomingNeededAngels($shifts, 3*60*60)),
            BLOCK_TYPE_COUNTER
        ),
        'number_upcoming_night_shifts' => block(
            array(
                'description' => _("Angels needed for night shifts"),
                'number' => getNumberUpcomingNightShifts()
            ),
            BLOCK_TYPE_COUNTER
        ),
        'number_currently_working' => block(
            array(
                'description' => _("Angels currently working"),
                'number' => getCurrentlyWorkingAngels()
                ),
            BLOCK_TYPE_COUNTER
        ),
        'number_hours_worked' => block(
            array(
                'description' => _("Hours to be worked"),
                'number' => countHoursToBeWorked($shifts)
            ),
            BLOCK_TYPE_COUNTER
        ),
        'my_next_jobs' => (in_array('user_shifts', $privileges))
            ? block(array('title' => _("My next jobs"), 'body' => getUsersNextJobs($shifts, 3*60*60)), BLOCK_TYPE_PANEL)
            : '',
        'jobs_currently_running' => block(
            array(
                'title' => _("Currently running"),
                'body' => getListCurrentShifts($shifts)
            ),
            BLOCK_TYPE_PANEL
        ),
        'jobs_now' => block(
            array(
                'title' => _("Within the next hour"),
                'body' => getListUpcomingShifts($shifts, 60*60)
            ),
            BLOCK_TYPE_PANEL
        ),
        'jobs_soon' => block(
            array(
                'title' => _("Within the next 3 hours"),
                'body' => getListUpcomingShifts($shifts, 3*60*60)
            ),
            BLOCK_TYPE_PANEL
        ),
        'news' => block(
            array(
                'title' => _("News"), 'body' => getAllNewsList()),
            BLOCK_TYPE_PANEL
        ),
        'api_shifts_link' => api_link('shifts'),
    );

    return  dashboardView($viewData);
}

/**
 * @param array $shifts
 * @param $withinSeconds
 *
 * @return int
 */
function getNumberUpcomingShifts($shifts, $withinSeconds)
{
    return count(getUpcomingShifts($shifts, $withinSeconds));
}

/**
 * @param $shifts
 * @param $withinSeconds
 *
 * @return string
 */
function getListUpcomingShifts($shifts, $withinSeconds)
{
    $upcomingShifts = getUpcomingShifts($shifts, $withinSeconds);

    return buildList($upcomingShifts);
}

/**
 * Filters the upcoming shifts within a given time.
 *
 * @param $shifts
 * @param $withinSeconds
 *
 * @return array
 */
function getUpcomingShifts($shifts, $withinSeconds)
{
    return array_filter($shifts, function ($shift) use ($withinSeconds) {
        $currentTime = time();

        return $shift['start'] > $currentTime && $shift['start'] <= ($currentTime + $withinSeconds);
    });
}

/**
 * Creates a list of currently running shifts with its subjects as label.
 *
 * @param $shifts
 * @return string
 */
function getListCurrentShifts($shifts)
{
    $currentlyRunning = getCurrentShifts($shifts);

    return buildList($currentlyRunning);
}

/**
 * Filters the currently running shifs
 *
 * @param $shifts
 * @return array
 */
function getCurrentShifts($shifts)
{
    return array_filter($shifts, function ($shift) {
        $currentTime = time();

        return $currentTime >= $shift['start'] && $shift['end'] >= $currentTime;
    });
}

function countUpcomingNeededAngels($shifts, $withinSeconds)
{
    $upcomingShifts = getUpcomingShifts($shifts, $withinSeconds);
    $ids = array();
    foreach ($upcomingShifts as $shift) {
        $ids[] = $shift['SID'];
    }
    if (count($ids) === 0) {
        return 0;
    }

    $neededInShifts = sql_select(
        sprintf(
            "SELECT SUM(sub.stillNeeded) as countNeeded FROM (
            SELECT
                '1' as grouping,
                s.SID,
                (SUM(nat.count)- COUNT(se.SID)) as stillNeeded
            FROM Shifts s
            INNER JOIN NeededAngelTypes nat
                ON s.SID = nat.shift_id
            LEFT JOIN ShiftEntry se
                ON se.SID = s.SID
            WHERE s.SID IN ('%s')
            GROUP BY s.SID
            ) sub
            GROUP BY sub.grouping;",
            implode("', '", $ids)
        )
    );

    if (!$neededInShifts) {
        return 0;
    }

    return $neededInShifts[0]['countNeeded'];
}

function getUsersNextJobs($shifts, $withinSeconds)
{
    global $user;
    $ids = array();
    foreach (getUpcomingShifts($shifts, $withinSeconds) as $shift) {
        $ids[] = $shift['SID'];
    }
    if (count($ids) === 0) {
        return 0;
    }
    $sql = sprintf(
        "SELECT s.* FROM ShiftEntry se JOIN Shifts s ON s.SID = se.SID WHERE se.UID = '%s' AND se.SID IN ('%s')",
        $user['UID'],
        implode("', '", $ids)
    );
    $usersShifts = sql_select($sql);

    return buildList($usersShifts);
}

/**
 * Creates a li list out of shifts with its titles as labels.
 *
 * @param $shifts
 * @return string
 */
function buildList($shifts)
{
    global $privileges;

    if (0 === count($shifts)) {
        return '';
    }

    $listItems = array();
    foreach ($shifts as $shift) {
        $title = $shift['title'] ?: sprintf(
            "%s</br>(%s)",
            htmlspecialchars($shift['type']),
            htmlspecialchars($shift['location'])
        );
        if (in_array('user_shifts', $privileges)) {
            $shiftLink = shift_link($shift);
            $content = sprintf("%s - %s", date('H:i', $shift['start']), date('H:i M.d.Y', $shift['end']));
            $title = sprintf(
                "<h4><a href=\"%s\">%s</a></h4><p>%s</p>",
                htmlspecialchars($shiftLink),
                $title,
                htmlspecialchars($content)
            );
        } else {
            $title = sprintf(
                "<span class=\"badge\">%s</span>%s",
                date('H:i', $shift['start']),
                htmlspecialchars($title)
            );
        }
        $listItems[] = $title;
    }

    return listView($listItems, array('class' => 'list-group', 'item_class' => 'list-group-item'));
}

/**
 * Get all shift types.
 *
 * @return array
 */
function getAllUpcomingShifts()
{
    return sql_select(
        "SELECT s.*, r.Name as location, t.name as type
         FROM `Shifts` s, `Room` r, `ShiftTypes` t
         WHERE s.`start` > UNIX_TIMESTAMP() OR s.`end` > UNIX_TIMESTAMP()
         GROUP BY s.`SID` ORDER BY s.`start`"
    );
}

/**
 * Creates an ul list of news items with its subjects as label.
 *
 * @return string
 */
function getAllNewsList()
{
    global $privileges;
    $news = sql_select("SELECT * FROM `News` ORDER BY `Datum`");

    if (0 === count($news)) {
        return '';
    }

    $listItems = array();
    foreach ($news as $article) {
        $title = $article['Betreff'];
        if (in_array('admin_news', $privileges)) {
            $title = sprintf("<h4>%s</h4><p>%s</p>", htmlspecialchars($title), htmlspecialchars($article['Text']));
        }
        $listItems[] = sprintf("%s", $title);
    }

    return listView($listItems, array('class' => 'list-group', 'item_class' => 'list-group-item news-list'));
}

/**
 * Counts the currently working angels by a simple SQL query.
 *
 * @return int
 */
function getCurrentlyWorkingAngels()
{
    $result = sql_select(
        "SELECT COUNT(s.SID) as countWorkingAngels, s.SID
         FROM ShiftEntry se
         INNER JOIN Shifts s ON se.SID = s.SID
         WHERE s.`start` < UNIX_TIMESTAMP() AND s.`end` > UNIX_TIMESTAMP()
         GROUP BY s.SID;"
    );

    if (1 !== count($result)) {
        return 0;
    }

    return $result[0]['countWorkingAngels'];
}

/**
 * @param array $shifts
 *
 * @return int
 */
function countHoursToBeWorked($shifts)
{
    $seconds = 0;
    $currentTime = time();

    foreach ($shifts as $shift) {
        if ($shift['start'] >= $currentTime) {
            // has not started yet
            $diff = $shift['end'] - $shift['start'];
            $seconds += $diff > 0 ? $diff : 0;
        } elseif ($shift['end'] >= $currentTime && $shift['start'] <= $currentTime) {
            // shift has started, so just use the time until the end
            $seconds += $shift['end'] - $currentTime;
        }
    }

    return round($seconds/60/60, 0);
}

/**
 * counts the night shifts which are upcoming.
 *
 * @return int
 */
function getNumberUpcomingNightShifts()
{
    $result = sql_select("SELECT SUM(sub.stillNeeded) as countUpcomingNightShifts FROM (
	SELECT
		'1' as grouping,
		s.SID,
		(SUM(nat.count)- COUNT(se.SID)) as stillNeeded
	FROM Shifts s
	INNER JOIN NeededAngelTypes nat
		ON s.SID = nat.shift_id
	LEFT JOIN ShiftEntry se
		ON se.SID = s.SID
	WHERE
	    (s.start -UNIX_TIMESTAMP() BETWEEN -7200 AND 64800) AND
		(FROM_UNIXTIME(s.start, '%H') > 18 OR FROM_UNIXTIME(s.end, '%H') < 6)
	AND  (s.start > UNIX_TIMESTAMP() OR s.end > UNIX_TIMESTAMP())
	GROUP BY s.SID
    ) sub
    GROUP BY sub.grouping;");
    if (1 !== count($result)) {
        return 0;
    }

    return $result[0]['countUpcomingNightShifts'];
}
