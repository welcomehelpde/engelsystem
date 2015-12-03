<?php
function admin_groups_title() {
  return _("Grouprights");
}

function admin_groupmembers_title($groupname) {
  return sprintf(_('Members of "%s"'), $groupname);
}

function admin_groups() {
  global $user;
  
  $html = "";
  $groups = sql_select("SELECT * FROM `Groups` ORDER BY `Name`");
  if (! isset($_REQUEST["action"])) {
    $groups_table = array();
    foreach ($groups as $group) {
      $privileges = sql_select("SELECT * FROM `GroupPrivileges` JOIN `Privileges` ON (`GroupPrivileges`.`privilege_id` = `Privileges`.`id`) WHERE `group_id`='" . sql_escape($group['UID']) . "'");
      $privileges_html = array();
      
      foreach ($privileges as $priv)
        $privileges_html[] = $priv['name'];
      
      $groups_table[] = array(
          'name' => $group['Name'],
          'privileges' => join(', ', $privileges_html),
          'actions' => button(page_link_to('admin_groups') . '&action=edit&id=' . $group['UID'], _("edit"), 'btn-xs'), 
          'action2' => button(page_link_to('admin_groups') . '&action=showmembers&id=' . $group['UID'], _("show members"), 'btn-xs')
      );
    }
    
    return page_with_title(admin_groups_title(), array(
        table(array(
            'name' => _("Name"),
            'privileges' => _("Privileges"),
            'actions' => '' ,
            'action2' => '' 
        ), $groups_table) 
    ));
  } else {
    switch ($_REQUEST["action"]) {
      case 'edit':
        if (isset($_REQUEST['id']) && preg_match("/^-[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error("Incomplete call, missing Groups ID.", true);
        
        $room = sql_select("SELECT * FROM `Groups` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
        if (count($room) > 0) {
          list($room) = $room;
          $privileges = sql_select("SELECT `Privileges`.*, `GroupPrivileges`.`group_id` FROM `Privileges` LEFT OUTER JOIN `GroupPrivileges` ON (`Privileges`.`id` = `GroupPrivileges`.`privilege_id` AND `GroupPrivileges`.`group_id`='" . sql_escape($id) . "') ORDER BY `Privileges`.`name`");
          $privileges_html = "";
          $privileges_form = array();
          foreach ($privileges as $priv) {
            $privileges_form[] = form_checkbox('privileges[]', $priv['desc'] . ' (' . $priv['name'] . ')', $priv['group_id'] != "", $priv['id']);
            $privileges_html .= sprintf('<tr><td><input type="checkbox" ' . 'name="privileges[]" value="%s" %s />' . '</td> <td>%s</td> <td>%s</td></tr>', $priv['id'], ($priv['group_id'] != "" ? 'checked="checked"' : ''), $priv['name'], $priv['desc']);
          }
          
          $privileges_form[] = form_submit('submit', _("Save"));
          $html .= page_with_title(_("Edit group"), array(
              form($privileges_form, page_link_to('admin_groups') . '&action=save&id=' . $id) 
          ));
        } else
          return error("No Group found.", true);
        break;
      
      case 'save':
        if (isset($_REQUEST['id']) && preg_match("/^-[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error("Incomplete call, missing Groups ID.", true);
        
        $room = sql_select("SELECT * FROM `Groups` WHERE `UID`='" . sql_escape($id) . "' LIMIT 1");
        if (! is_array($_REQUEST['privileges']))
          $_REQUEST['privileges'] = array();
        if (count($room) > 0) {
          list($room) = $room;
          sql_query("DELETE FROM `GroupPrivileges` WHERE `group_id`='" . sql_escape($id) . "'");
          $privilege_names = array();
          foreach ($_REQUEST['privileges'] as $priv) {
            if (preg_match("/^[0-9]{1,}$/", $priv)) {
              $group_privileges_source = sql_select("SELECT * FROM `Privileges` WHERE `id`='" . sql_escape($priv) . "' LIMIT 1");
              if (count($group_privileges_source) > 0) {
                sql_query("INSERT INTO `GroupPrivileges` SET `group_id`='" . sql_escape($id) . "', `privilege_id`='" . sql_escape($priv) . "'");
                $privilege_names[] = $group_privileges_source[0]['name'];
              }
            }
          }
          engelsystem_log("Group privileges of group " . $room['Name'] . " edited: " . join(", ", $privilege_names));
          redirect(page_link_to("admin_groups"));
        } else
          return error("No Group found.", true);
        break;
        
      case 'showmembers':
        if (isset($_REQUEST['id']) && preg_match("/^-[0-9]{1,11}$/", $_REQUEST['id']))
          $id = $_REQUEST['id'];
        else
          return error("Incomplete call, missing Groups ID.", true);
          
        // Lets get the humanreadable Groupname for title and coloumn
        $groupinfo = sql_select("SELECT * FROM `Groups` where `UID` = '" . sql_escape($id) . "' limit 1");
        if (count($groupinfo) > 0)
          $groupinfo = $groupinfo[0];
        else
          return error("No Group found.", true);
        
        $members = sql_select("
        SELECT g.`name` as `gname`, ug.`group_id`, u.`UID`, u.`email`, u.`Vorname`, u.`Name`, u.`UID`
        FROM UserGroups ug 
          JOIN User u on (u.uid = ug.uid) 
          JOIN Groups g on (g.UID = ug.group_id) 
        WHERE ug.group_id = '" . sql_escape($id) . "'"
        );
        
        if (count($members) > 0) {
          // list($members) = $members;
          foreach($members as $member) {
            $members_table[] = array(
              'fname' => $member['Vorname'],
              'lname' => $member['Name'],
              'email' => $member['email'],
              'group' => $member['gname'],
              'actions' => button(page_link_to('admin_user') . '&action=remove_group&id=' . $member['UID'] . '&group_id=' . sql_escape($id), _("remove"), 'btn-xs')
            );
          }

          $html .= page_with_title(admin_groupmembers_title($groupinfo['Name']), array(table(array(
            'fname' => _("Vorname"),
            'lname' => _("Nachname"),
            'email' => _("E-Mail"),
            'group' => _("Gruppe"),
            'actions' => '' 
        ), $members_table) 
    ));
        
        
        } else
          return error("No Members in group.", true);
        break;
    }
  }
  return $html;
}
?>
