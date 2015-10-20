<?php

function login_title() {
  return _("Login");
}

function register_title() {
  return _("Register");
}

function logout_title() {
  return _("Logout");
}

function user_activate_account_title() {
  return _("Activate Account");
}

// Engel registrieren
function guest_register() {
  global $default_theme, $genders;
  
  $msg = "";
  $nick = "";
  $lastname = "";
  $prename = "";
  $age = "";
  $tel = "";
  $mobile = "";
  $mail = "";
  $email_shiftinfo = false;
  $hometown = "";
  $comment = "";
  $password_hash = "";
  $selected_angel_types = array();
  $gender = "none";

  $angel_types_source = sql_select("SELECT * FROM `AngelTypes` ORDER BY `name`");
  $angel_types = array();
  foreach ($angel_types_source as $angel_type) {
    $angel_types[$angel_type['id']] = $angel_type['name'] . ($angel_type['restricted'] ? " (restricted)" : "");
    if (! $angel_type['restricted'])
      $selected_angel_types[] = $angel_type['id'];
  }
  
  if (isset($_REQUEST['submit'])) {
    $ok = true;
    
    if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 1) {
      $nick = User_validate_Nick($_REQUEST['nick']);
      if (sql_num_query("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "' LIMIT 1") > 0) {
        $ok = false;
        $msg .= error(sprintf(_("Your nick &quot;%s&quot; already exists."), $nick), true);
      }
    } else {
      $ok = false;
      $msg .= error(sprintf(_("Your nick &quot;%s&quot; is too short (min. 2 characters)."), User_validate_Nick($_REQUEST['nick'])), true);
    }
    
    if (isset($_REQUEST['mail']) && strlen(strip_request_item('mail')) > 0) {
      $mail = strip_request_item('mail');
      if (! check_email($mail)) {
        $ok = false;
        $msg .= error(_("E-mail address is not correct."), true);
      }
    } else {
      $ok = false;
      $msg .= error(_("Please enter your e-mail."), true);
    }
    
    if (isset($_REQUEST['email_shiftinfo']))
      $email_shiftinfo = true;
    
    
    if (isset($_REQUEST['password']) && strlen($_REQUEST['password']) >= MIN_PASSWORD_LENGTH) {
      if ($_REQUEST['password'] != $_REQUEST['password2']) {
        $ok = false;
        $msg .= error(_("Your passwords don't match."), true);
      }
    } else {
      $ok = false;
      $msg .= error(sprintf(_("Your password is too short (please use at least %s characters)."), MIN_PASSWORD_LENGTH), true);
    }
    
    
    $selected_angel_types = array();
    foreach ($angel_types as $angel_type_id => $angel_type_name)
      if (isset($_REQUEST['angel_types_' . $angel_type_id]))
        $selected_angel_types[] = $angel_type_id;
      
      // Trivia
    if (isset($_REQUEST['lastname']))
      $lastname = strip_request_item('lastname');
    if (isset($_REQUEST['prename']))
      $prename = strip_request_item('prename');
    if (isset($_REQUEST['age']) && preg_match("/^[0-9]{0,4}$/", $_REQUEST['age']))
      $age = strip_request_item('age');
    if (isset($_REQUEST['tel']))
      $tel = strip_request_item('tel');
    if (isset($_REQUEST['mobile']))
      $mobile = strip_request_item('mobile');
    if (isset($_REQUEST['hometown']))
      $hometown = strip_request_item('hometown');
    if (isset($_REQUEST['comment']))
      $comment = strip_request_item_nl('comment');

    if (isset($_REQUEST['gender'])
        && array_key_exists($_REQUEST['gender'], $genders)) {
        $gender = $_REQUEST['gender'];
    }


    if ($ok) {
      $confirmationToken = bin2hex(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM));
      $confirmationTokenUrl = page_link_to_absolute('user_activate_account') . '&token=' . $confirmationToken;

      sql_query("
          INSERT INTO `User` SET 
          `color`='" . sql_escape($default_theme) . "', 
          `Nick`='" . sql_escape($nick) . "', 
          `Vorname`='" . sql_escape($prename) . "', 
          `Name`='" . sql_escape($lastname) . "', 
          `Alter`='" . sql_escape($age) . "', 
          `gender`='" . sql_escape($gender) . "',
          `Telefon`='" . sql_escape($tel) . "', 
          `Handy`='" . sql_escape($mobile) . "', 
          `email`='" . sql_escape($mail) . "', 
          `email_shiftinfo`=" . sql_bool($email_shiftinfo) . ", 
          `Passwort`='" . sql_escape($password_hash) . "', 
          `kommentar`='" . sql_escape($comment) . "', 
          `Hometown`='" . sql_escape($hometown) . "', 
          `CreateDate`=NOW(), 
          `Sprache`='" . sql_escape($_SESSION["locale"]) . "',
          `arrival_date`=NULL,
          `planned_arrival_date`= 0,
          `mailaddress_verification_token` = '" . sql_escape($confirmationToken) . "',
          `user_account_approved` = 0");
      
      // Assign user-group and set password
      $user_id = sql_id();
      sql_query("INSERT INTO `UserGroups` SET `uid`='" . sql_escape($user_id) . "', `group_id`=-2");
      set_password($user_id, $_REQUEST['password']);
      
      // Assign angel-types
      $user_angel_types_info = array();
      foreach ($selected_angel_types as $selected_angel_type_id) {
        sql_query("INSERT INTO `UserAngelTypes` SET `user_id`='" . sql_escape($user_id) . "', `angeltype_id`='" . sql_escape($selected_angel_type_id) . "'");
        $user_angel_types_info[] = $angel_types[$selected_angel_type_id];
      }
      engelsystem_log("User " . $nick . " signed up as: " . join(", ", $user_angel_types_info));

      engelsystem_email($mail, _('Please confirm your eMail-address'), sprintf(_('Hello %1$s! Thanks for signing up at Engelsystem. Please confirm your eMail-address by clicking the following link: %2$s'), $mail, $confirmationTokenUrl));
      
      success(_("Angel registration successful! Please click the confirmation link in the eMail we sent you to activate your account."));

      redirect('?');
    }
  }
  
  return page_with_title(register_title(), array(
      _("By completing this form you're registering as an helper. Please enter a username/nick of your choice, your e-mail adress and your full name. Only your nick will be shown to other users."),
      $msg,
      msg(),
      form(array(
          div('row', array(
              div('col-md-6', array(
                  div('row', array(
                      div('col-sm-4', array(
                          form_text('nick', _("Nick") . ' ' . entry_required(), $nick) 
                      )),
                      div('col-sm-8', array(
                          form_email('mail', _("E-Mail") . ' ' . entry_required(), $mail),
                          form_checkbox('email_shiftinfo', _("Please keep me informed by e-mail, e.g. if my shifts change"), $email_shiftinfo) 
                      )),
					  div('col-sm-4', array(
                          form_text('prename', _("First name") . ' ' . entry_required(), $prename) 
                      )),
                      div('col-sm-4', array(
                          form_text('lastname', _("Last name") . ' ' . entry_required(), $lastname) 
                      )) 
                  )),
                  div('row', array(
                      div('col-sm-6', array(
                      )),
                      div('col-sm-6', array(
                      )) 
                  )),
                  div('row', array(
                      div('col-sm-6', array(
                          form_password('password', _("Password") . ' ' . entry_required()) 
                      )),
                      div('col-sm-6', array(
                          form_password('password2', _("Confirm password") . ' ' . entry_required()) 
                      )) 
                  )),
                  // form_checkboxes('angel_types', _("What do you want to do?") . sprintf(" (<a href=\"%s\">%s</a>)", page_link_to('angeltypes') . '&action=about', _("Description of job types")), $angel_types, $selected_angel_types),
                 //				 form_info("", _("Restricted angel types need will be confirmed later by an archangel. You can change your selection in the options section.")) 
              )),
              div('col-md-6', array(
                  div('row', array(
                      div('col-sm-4', array(
                          form_text('mobile', _("Mobile"), $mobile)                          
                      )),
                      div('col-sm-4', array(
                          form_text('tel', _("Phone"), $tel) 
                      )) 
                  )),
                  div('row', array(
                      div('col-sm-3', array(
                          form_text('age', _("Age"), $age) 
                      )),
//                      div('col-sm-3', array(
//                          form_select('gender', _("Gender"), $genders, $gender)
//                      )),
//                      div('col-sm-6', array(
//                          form_text('hometown', _("Hometown"), $hometown) 
//                      )),
                      div('col-sm-6', array(
                              form_text('comment', _("Additional Information(Language / Profession)"), $comment)
                          ))
                      )),
                  form_info(entry_required() . ' = ' . _("Entry required!")) 
              )) 
          )),
          form_submit('submit', _("Register"))
      )) 
  ));
}

function entry_required() {
  return '<span class="text-info glyphicon glyphicon-warning-sign"></span>';
}

function guest_logout() {
  session_destroy();
  redirect(page_link_to("start"));
}

function guest_login() {
  global $user, $privileges;
  
  $nick = "";
  
  unset($_SESSION['uid']);
  
  if (isset($_REQUEST['submit'])) {
    $ok = true;
    
    if (isset($_REQUEST['nick']) && strlen(User_validate_Nick($_REQUEST['nick'])) > 0) {
      $nick = User_validate_Nick($_REQUEST['nick']);
      $login_user = sql_select("SELECT * FROM `User` WHERE `Nick`='" . sql_escape($nick) . "'");
      if (count($login_user) > 0) {
        $login_user = $login_user[0];
        if (isset($_REQUEST['password'])) {
          if (! verify_password($_REQUEST['password'], $login_user['Passwort'], $login_user['UID'])) {
            $ok = false;
            error(_("Your password is incorrect.  Please try it again."));
          }
          else { //password is okay, check confirmaiton
            if($login_user['user_account_approved'] !== '1') {
              $ok = false;
              error(_("Your account is not confirmed yet. Please click the link in the mail we sent you. If you didn't get an eMail, ask a dispatcher."));
            }
          }
        } else {
          $ok = false;
          error(_("Please enter a password."));
        }
      } else {
        $ok = false;
        error(_("No user was found with that Nickname. Please try again. If you are still having problems, ask an Dispatcher."));
      }
    } else {
      $ok = false;
      error(_("Please enter a nickname."));
    }
    
    if ($ok) {
      $_SESSION['uid'] = $login_user['UID'];
      $_SESSION['locale'] = $login_user['Sprache'];
      
      redirect(page_link_to('shifts'));
    }
  }
  
  if (in_array('register', $privileges)) {
    $register_hint = join('', array(
        '<p>' . _("Please sign up, if you want to help us!") . '</p>',
        buttons(array(
            button(page_link_to('register'), register_title() . ' &raquo;') 
        )) 
    ));
  } else {
    $register_hint = join('', array(
        error(_('Registration is disabled.'), true) 
    ));
  }
  
  return page_with_title(login_title(), array(
      msg(),
      '<div class="row"><div class="col-md-6">',
      form(array(
          form_text('nick', _("Nick"), $nick),
          form_password('password', _("Password")),
          form_submit('submit', _("Login")),
          buttons(array(
              button(page_link_to('user_password_recovery'), _("I forgot my password")) 
          )),
          info(_("Please note: You have to activate cookies!"), true) 
      )),
      '</div></div>' 
  ));
}

function user_activate_account_controller () {
  if(!isset($_GET['token'])) {
    error(_("Invalid confirmation token."));
    redirect('?');
    die();
  }
  
  $token = $_GET['token'];

  $checkQuery = 'SELECT COUNT(`UID`) as `exists`
                 FROM `User`
                 WHERE `mailaddress_verification_token` = "' . sql_escape($token) . '"';
  $checkResult = sql_select_single_cell($checkQuery);

  if($checkResult === '1') {
    $confirmSql = 'UPDATE `User`
                   SET `user_account_approved` = "1"
                   WHERE `mailaddress_verification_token` = "' . sql_escape($token) . '"';
    sql_query($confirmSql);
    success(_('Your account is confirmed now. You might want to log in:'));
    redirect(page_link_to('login'));
  }
  else {
    error(_("Invalid confirmation token."));
    redirect('?');
    die();    
  }
}

?>
