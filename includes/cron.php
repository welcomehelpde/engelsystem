<?php

/**
 * Defines periodically called operations. The frequency of invocation
 * is undefined.
 */

define('ENGELSYSTEM_CRONJOB', true);
define('REMINDER_SEND_PERIOD', 3600); // one hour
define('REMINDER_MIN_FUTURE', 3600*3); // 3 hours into future
define('REMINDER_FILE_LOC', "../tmp/lastReminderSendAt.txt");

require_once realpath(__DIR__.'/../includes/engelsystem_provider.php');

/**
 * Sends reminder emails to users for soon beginning shifts
 *
 */
function send_reminder_emails_for_shifts() {
  //  load last end time
  $lastEndTime = time() + REMINDER_MIN_FUTURE;
  $fReminder = fopen(REMINDER_FILE_LOC, "c+");
  $fsize = filesize(REMINDER_FILE_LOC);
  if($fsize > 0) {
    $readLET = fread($fReminder, $fsize);
    }

  if(isset($readLET) && $readLET > $lastEndTime) {
    // cron was invoked before REMINDER_SEND_PERIOD time passed
    // -> do not send out new eMails starting from $readLET
    fclose($fReminder);

  } else {
    if(isset($readLET)) // for the first time readLET is not set
      $lastEndTime = $readLET;

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
      } // end of $assignedUsers
    } // end of $shiftsInNextP
  } // end of else (isset($readLET) ...)
} // end of function




/**
 * List of cronjobs functionality
 *
 * Implementations of function need to take into account that cronjob frequency may vary.
 */
send_reminder_emails_for_shifts();

?>