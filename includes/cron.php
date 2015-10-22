<?php

define('ENGELSYSTEM_CRONJOB', true);
define('REMINDER_SEND_PERIOD', 3600*3);
define('REMINDER_FILE_LOC', "../tmp/lastReminderSendAt.txt");

require_once realpath(__DIR__.'/../includes/engelsystem_provider.php');

// send reminder emails for soon beginning shifts
// load last end time
$lastEndTime = time();
$fReminder = fopen(REMINDER_FILE_LOC, "c+");
$fsize = filesize(REMINDER_FILE_LOC);
if($fsize > 0) {
  $lastEndTime = fread($fReminder, $fsize);
}
// persist new lastEnd
fseek($fReminder, 0);
fwrite($fReminder, $lastEndTime + (REMINDER_SEND_PERIOD));
fclose($fReminder);

// retrieve shifts in next period
$shiftsInNextP = Shifts_find_by_start_interval($lastEndTime, REMINDER_SEND_PERIOD);

// iterate over every shift and find assigned users
foreach ($shiftsInNextP as $shift) {

  $shift['RID'] = Room($shift['RID']);
  $assignedUsers = ShiftEntries_by_shift($shift['SID']);
  
  // iterate over each user and send eMail
  foreach($assignedUsers as $user) {
    
    mail_shift_reminder($user, $shift, _("3 hours"));
  }
}

?>