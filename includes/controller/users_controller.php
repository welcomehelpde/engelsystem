<?php

/**
 * Route user actions.
 */
function users_controller() {
  global $privileges, $user;
  
  if (! isset($user))
    redirect(page_link_to(''));
  
  if (! isset($_REQUEST['action']))
    $_REQUEST['action'] = 'list';
  
  switch ($_REQUEST['action']) {
    default:
    case 'csv':
    case 'list':
      return users_list_controller();
    case 'view':
      return user_controller();
    case 'edit':
      return user_edit_controller();
    case 'delete':
      return user_delete_controller();
    case 'edit_vouchers':
      return user_edit_vouchers_controller();
  }
}

function users_link() {
  return page_link_to('users');
}

function users_link_csv() {
  return page_link_to('users').'&action=csv';
}

function user_link($user) {
  return page_link_to('users') . '&action=view&user_id=' . $user['UID'];
}

function user_edit_vouchers_controller() {
  global $privileges, $user;
  
  if (isset($_REQUEST['user_id'])) {
    $user_source = User($_REQUEST['user_id']);
  } else
    $user_source = $user;
  
  if (! in_array('admin_user', $privileges))
    redirect(page_link_to(''));
  
  if (isset($_REQUEST['submit'])) {
    $ok = true;
    
    if (isset($_REQUEST['vouchers']) && test_request_int('vouchers') && trim($_REQUEST['vouchers']) >= 0)
      $vouchers = trim($_REQUEST['vouchers']);
    else {
      $ok = false;
      error(_("Please enter a valid number of vouchers."));
    }
    
    if ($ok) {
      $user_source['got_voucher'] = $vouchers;
      
      $result = User_update($user_source);
      if ($result === false)
        engelsystem_error('Unable to update user.');
      
      success(_("Saved the number of vouchers."));
      engelsystem_log(User_Nick_render($user_source) . ': ' . sprintf("Got %s vouchers", $user_source['got_voucher']));
      
      redirect(user_link($user_source));
    }
  }
  
  return array(
      sprintf(_("%s's vouchers"), $user_source['Nick']),
      User_edit_vouchers_view($user_source) 
  );
}

function user_controller() {
  global $privileges, $user;
  
  if (isset($_REQUEST['user_id'])) {
    $user_source = User($_REQUEST['user_id']);
  } else
    $user_source = $user;
  
  $shifts = Shifts_by_user($user_source);
  foreach ($shifts as &$shift) {
    // TODO: Move queries to model
    $shift['needed_angeltypes'] = sql_select("SELECT DISTINCT `AngelTypes`.* FROM `ShiftEntry` JOIN `AngelTypes` ON `ShiftEntry`.`TID`=`AngelTypes`.`id` WHERE `ShiftEntry`.`SID`='" . sql_escape($shift['SID']) . "'  ORDER BY `AngelTypes`.`name`");
    foreach ($shift['needed_angeltypes'] as &$needed_angeltype) {
      $needed_angeltype['users'] = sql_select("
          SELECT `ShiftEntry`.`freeloaded`, `User`.*
          FROM `ShiftEntry`
          JOIN `User` ON `ShiftEntry`.`UID`=`User`.`UID`
          WHERE `ShiftEntry`.`SID`='" . sql_escape($shift['SID']) . "'
          AND `ShiftEntry`.`TID`='" . sql_escape($needed_angeltype['id']) . "'");
    }
  }
  
  if ($user_source['api_key'] == "")
    User_reset_api_key($user_source, false);
  
  return array(
      $user_source['Nick'],
      User_view($user_source, in_array('admin_user', $privileges), User_is_freeloader($user_source), User_angeltypes($user_source), User_groups($user_source), $shifts, $user['UID'] == $user_source['UID']) 
  );
}

/**
 * List all users.
 */
function users_list_controller() {
  global $privileges;

  if (! in_array('admin_user', $privileges))
    redirect(page_link_to(''));
  
  $order_by = 'Nick';
  if (isset($_REQUEST['OrderBy']) && in_array($_REQUEST['OrderBy'], User_sortable_columns()))
    $order_by = $_REQUEST['OrderBy'];
  
  $users = Users($order_by);
  if ($users === false)
    engelsystem_error('Unable to load users.');
  
  foreach ($users as &$user)
    $user['freeloads'] = count(ShiftEntries_freeloaded_by_user($user));

  if($_REQUEST['action'] == "csv") {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=engelsystem_users_'.date('Y-m-d').'.csv');

    $csv = fopen('php://output', 'w');
    fputcsv($csv, array(_('Nick'), _('Name'), _('First Name'), _('Phone'), _('Mobile'), _('E-mail'), _('Additional Info')));

    foreach ($users as &$user) {
        fputcsv($csv, array($user['Nick'], $user['Name'], $user['Vorname'], $user['Telefon'], $user['Handy'], $user['email'], $user['kommentar']));
    }

    fclose($csv);

    die();
  } else {
    return array(
      _('All users'),
      Users_view($users, $order_by, User_arrived_count(), User_active_count(), User_force_active_count(), ShiftEntries_freeleaded_count(), User_tshirts_count(), User_got_voucher_count()) 
    );
  }
}

/**
 * User password recovery.
 * (By email)
 */
function user_password_recovery_controller() {
  if (isset($_REQUEST['token'])) {
    $user_source = User_by_password_recovery_token($_REQUEST['token']);
    if ($user_source === false)
      engelsystem_error("Unable to load user.");
    if ($user_source == null) {
      error(_("Token is not correct."));
      redirect(page_link_to('login'));
    }
    
    if (isset($_REQUEST['submit'])) {
      $ok = true;
      
      if (isset($_REQUEST['password']) && strlen($_REQUEST['password']) >= MIN_PASSWORD_LENGTH) {
        if ($_REQUEST['password'] != $_REQUEST['password2']) {
          $ok = false;
          error(_("Your passwords don't match."));
        }
      } else {
        $ok = false;
        error(_("Your password is to short (please use at least 6 characters)."));
      }
      
      if ($ok) {
        $result = set_password($user_source['UID'], $_REQUEST['password']);
        if ($result === false)
          engelsystem_error(_("Password could not be updated."));
        
        success(_("Password saved."));
        redirect(page_link_to('login'));
      }
    }
    
    return User_password_set_view();
  } else {
    if (isset($_REQUEST['submit'])) {
      $ok = true;
      
      if (isset($_REQUEST['email']) && strlen(strip_request_item('email')) > 0) {
        $email = strip_request_item('email');
        if (check_email($email)) {
          $user_source = User_by_email($email);
          if ($user_source === false)
            engelsystem_error("Unable to load user.");
          if ($user_source == null) {
            $ok = false;
            error(_("E-mail address is not correct."));
          }
        } else {
          $ok = false;
          error(_("E-mail address is not correct."));
        }
      } else {
        $ok = false;
        error(_("Please enter your e-mail."));
      }
      
      if ($ok) {
        $token = User_generate_password_recovery_token($user_source);
        if ($token === false)
          engelsystem_error("Unable to generate password recovery token.");
        $result = engelsystem_email_to_user($user_source, _("Password recovery"), sprintf(_("Please visit %s to recover your password."), page_link_to_absolute('user_password_recovery') . '&token=' . $token));
        if ($result === false)
          engelsystem_error("Unable to send password recovery email.");
        
        success(_("We sent an email containing your password recovery link."));
        redirect(page_link_to('login'));
      }
    }
    
    return User_password_recovery_view();
  }
}

// Send verification email
function user_send_verification_email($mail, $confirmationToken) {
  global $customization;

  $confirmationTokenUrl = page_link_to_absolute('user_activate_account') . '&token=' . $confirmationToken;
  $signature = $customization['instance_name'];

  $mailText = _('Hello %1$s! Thanks for signing up at Engelsystem. Please confirm your eMail-address by clicking the following link: %2$s %3$s');

  engelsystem_email($mail, _('Please confirm your eMail-address'), sprintf($mailText, $mail, $confirmationTokenUrl, $signature));
}

function user_resend_verification_token() {  
  global $user, $privileges;

  $success = false;

  if(isset($_GET['uid'])) {
     $uid = $_GET['uid'];
     if(is_numeric($uid)) {
       $user = User($uid);

       if($user != null && $user['user_account_approved'] == 0) {
         // found user entry, check verification bit set? and send email
         user_send_verification_email($user['email'], $user['mailaddress_verification_token']);

         success(_("Verification E-Mail was send again to your E-Mail address. If you still don't receive it, please check your spam folder and ask a Dispatcher."));
         $success = true;
       }
     }
  } elseif (isset($_REQUEST['email']) && strlen(strip_request_item('email')) > 0) {
    $email = strip_request_item('email');
    if(check_email($email)) {
      $user = User_by_email($email);
      
      if($user != null && $user['user_account_approved'] == 0) {
        user_send_verification_email($user['email'], $user['mailaddress_verification_token']);
        success(_("Verification E-Mail was send again to your E-Mail address. If you still don't receive it, please check your spam folder and ask a Dispatcher."));
        $success = true;        
      }      
    }
  } else { 
    // show page to input E-Mail
    return User_request_verification_token_view();
  }

  $admin_priv = in_array('admin_user', $privileges);

  if($success == false) {
    // failure, couldn't find user or something went wrong
    error(_("Verification E-Mail Could not be send. Please ask a Dispatcher."));
  }


  if($admin_priv && $success == true && isset($user) && isset($user['UID'])) {
    redirect(user_link($user));
  } else {
    redirect('?');     
  }
}

/**
 * Menu title for password recovery.
 */
function user_password_recovery_title() {
  return _("Password recovery");
}

/**
 * Menu title for requesting verification token.
 */
function user_request_verification_token_title() {
  return _("Requesting additional verification token");
}

?>
