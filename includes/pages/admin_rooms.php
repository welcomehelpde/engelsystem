<?php

function admin_rooms_title()
{
    return _("Locations");
}

function admin_rooms()
{
    global $user;

    global $user, $enable_frab_import;
    $rooms_source = sql_select("SELECT * FROM `Room` ORDER BY `Name`");
    $rooms = array();
    foreach ($rooms_source as $room)
        $rooms[] = array(
            'name' => $room['Name'],
            'from_pentabarf' => $room['FromPentabarf'] == 'Y' ? '&#10003;' : '',
            'public' => $room['show'] == 'Y' ? '&#10003;' : '',
            'actions' => buttons(array(
                button(page_link_to('admin_rooms') . '&show=edit&id=' . $room['RID'], _("edit"), 'btn-xs'),
                button(page_link_to('admin_rooms') . '&show=delete&id=' . $room['RID'], _("delete"), 'btn-xs')
            ))
        );

    if (isset($_REQUEST['show'])) {
        $msg = "";
        $name = "";
        $location = "";
        $lat = "";
        $long = "";
        $from_pentabarf = "";
        $public = 'Y';
        $number = "";

        $angeltypes_source = sql_select("SELECT * FROM `AngelTypes` ORDER BY `name`");
        $angeltypes = array();
        $angeltypes_count = array();
        foreach ($angeltypes_source as $angeltype) {
            $angeltypes[$angeltype['id']] = $angeltype['name'];
            $angeltypes_count[$angeltype['id']] = 0;
        }

        if (test_request_int('id')) {
            $room = sql_select("SELECT * FROM `Room` WHERE `RID`='" . sql_escape($_REQUEST['id']) . "'");
            if (count($room) > 0) {
                $id = $_REQUEST['id'];
                $name = $room[0]['Name'];
                $location = $room[0]['location'];
                $lat = $room[0]['lat'];
                $long = $room[0]['long'];
                $from_pentabarf = $room[0]['FromPentabarf'];
                $public = $room[0]['show'];
                $needed_angeltypes = sql_select("SELECT * FROM `NeededAngelTypes` WHERE `room_id`='" . sql_escape($id) . "'");
                foreach ($needed_angeltypes as $needed_angeltype)
                    $angeltypes_count[$needed_angeltype['angel_type_id']] = $needed_angeltype['count'];
            } else
                redirect(page_link_to('admin_rooms'));
        }

        if ($_REQUEST['show'] == 'edit') {
            if (isset($_REQUEST['submit'])) {
                $ok = true;

                if (isset($_REQUEST['name']) && strlen(strip_request_item('name')) > 0) {
                    $name = strip_request_item('name');
                } else {
                    $ok = false;
                    $msg .= error(_("Please enter a name."), true);
                }

                if (isset($_REQUEST['location']) && strlen(strip_request_item('location')) > 0) {
                    $location = strip_request_item('location');
                } else {
                    $ok = false;
                    $msg .= error(_("Please enter a location."));
                }

                if (isset($_REQUEST['lat']) && isset($_REQUEST['long'])) {
                    $lat = $_REQUEST['lat'];
                    $long = $_REQUEST['long'];
                } else {
                    $ok = false;
                    $msg .= error(_("Please enter a location - no lat long values found."));
                }

                $from_pentabarf = isset($_REQUEST['from_pentabarf']) ? 'Y' : '';
                $public = isset($_REQUEST['public']) ? 'Y' : '';

                if (isset($_REQUEST['number'])) {
                    $number = strip_request_item('number');
                } else {
                    $ok = false;
                }
                foreach ($angeltypes as $angeltype_id => $angeltype) {
                    if (isset($_REQUEST['angeltype_count_' . $angeltype_id]) && preg_match("/^[0-9]{1,4}$/", $_REQUEST['angeltype_count_' . $angeltype_id]))
                        $angeltypes_count[$angeltype_id] = $_REQUEST['angeltype_count_' . $angeltype_id];
                    else {
                        $ok = false;
                        $msg .= error(sprintf(_("Please enter needed angels for type %s.", $angeltype)), true);
                    }
                }

                if ($ok) {
                    if (isset($id)) {
                        sql_query(
                            sprintf(
                                "UPDATE `Room` SET `Name`='%s', `FromPentabarf`='%s', `show`='%s', `Number`='%s', `location` = '%s', `lat` = '%s', `long` = '%s' WHERE `RID`='%s' LIMIT 1",
                                sql_escape($name),
                                sql_escape($from_pentabarf),
                                sql_escape($public),
                                sql_escape($number),
                                sql_escape($location),
                                sql_escape($lat),
                                sql_escape($long),
                                sql_escape($id)
                            )
                        );
                        engelsystem_log("Location updated: " . $name . ", pentabarf import: " . $from_pentabarf . ", public: " . $public . ", number: " . $number);
                    } else {
                        $id = Room_create($name, $from_pentabarf, $public, $location, $lat, $long);
                        if ($id === false)
                            engelsystem_error("Unable to create location.");
                        engelsystem_log("Location created: " . $name . ", pentabarf import: " . $from_pentabarf . ", public: " . $public . ", number: " . $number);
                    }

                    sql_query("DELETE FROM `NeededAngelTypes` WHERE `room_id`='" . sql_escape($id) . "'");
                    $needed_angeltype_info = array();
                    foreach ($angeltypes_count as $angeltype_id => $angeltype_count) {
                        $angeltype = AngelType($angeltype_id);
                        if ($angeltype === false)
                            engelsystem_error("Unable to load angeltype.");
                        if ($angeltype != null) {
                            sql_query(
                                sprintf(
                                    "INSERT INTO `NeededAngelTypes` SET `room_id`='%s', `angel_type_id`='%s', `count`='%s'",
                                    sql_escape($id),
                                    sql_escape($angeltype_id),
                                    sql_escape($angeltype_count)
                                )
                            );
                            $needed_angeltype_info[] = $angeltype['name'] . ": " . $angeltype_count;
                        }
                    }

                    engelsystem_log("Set needed angeltypes of location " . $name . " to: " . join(", ", $needed_angeltype_info));
                    success(_("Location saved."));
                    redirect(page_link_to("admin_rooms"));
                }
            }
            $angeltypes_count_form = array();
            foreach ($angeltypes as $angeltype_id => $angeltype) {
                $angeltypes_count_form[] = div('col-lg-4 col-md-6 col-xs-6', array(
                    form_spinner('angeltype_count_' . $angeltype_id, $angeltype, $angeltypes_count[$angeltype_id])
                ));
            }

            $form_elements = [];
            $form_elements[] = form_text('name', _("Name"), $name);
            $form_elements[] = form_text('location', _("Location"), $location);
            $form_elements[] = form_text('lat', _("Latitude"), $lat, false, true);
            $form_elements[] = form_text('long', _("Longitude"), $long, false, true);
            if ($enable_frab_import) {
                $form_elements[] = form_checkbox('from_pentabarf', _("Frab import"), $from_pentabarf);
            }
            $form_elements[] = form_checkbox('public', _("Public"), $public);
            $form_elements[] = form_text('number', _("Room number"), $number);

            return page_with_title(admin_rooms_title(), array(
                buttons(array(
                    button(page_link_to('admin_rooms'), _("back"), 'back')
                )),
                $msg,
                form(array(
                    div('row', array(
                        div('col-md-6', $form_elements),
                        div('col-md-6', array(
                            div('row', array(
                                div('col-md-12', array(
                                    form_info(_("Needed angels:"))
                                )),
                                join($angeltypes_count_form)
                            ))
                        )),
                        script("
                            jQuery(function ($) {
                                var input = $(\"input[id='form_location']\");
                                var inputElement = document.getElementById(input.attr('id'));
                                var searchBox = new google.maps.places.SearchBox(inputElement);
                                searchBox.addListener('places_changed', function() {
                                    var places = searchBox.getPlaces();
                                    if (places.length == 0) {
                                      return;
                                    }

                                    var place = places.pop();
                                    var lat = place.geometry.location.lat();
                                    var long = place.geometry.location.lng();

                                    $(\"input[id='form_lat']\").val(lat);
                                    $(\"input[id='form_long']\").val(long);
                                });

                                // suppress form submit on enter
                                input.keypress(function (event) {
                                    if (event.keyCode === 13) {
                                        return false;
                                    }
                                });
                            });
                        ")
                    )),
                    form_submit('submit', _("Save"))
                ))
            ));
        } elseif ($_REQUEST['show'] == 'delete') {
            if (isset($_REQUEST['ack'])) {
                sql_query("DELETE FROM `Room` WHERE `RID`='" . sql_escape($id) . "' LIMIT 1");
                sql_query("DELETE FROM `NeededAngelTypes` WHERE `room_id`='" . sql_escape($id) . "' LIMIT 1");

                engelsystem_log("Location deleted: " . $name);
                success(sprintf(_("Location %s deleted."), $name));
                redirect(page_link_to('admin_rooms'));
            }

            return page_with_title(admin_rooms_title(), array(
                buttons(array(
                    button(page_link_to('admin_rooms'), _("back"), 'back')
                )),
                sprintf(_("Do you want to delete location %s?"), $name),
                buttons(array(
                    button(page_link_to('admin_rooms') . '&show=delete&id=' . $id . '&ack', _("Delete"), 'delete')
                ))
            ));
        }
    }


    $table_columns = array(
        'name' => _("Name"),
        'from_pentabarf' => _("Frab import"),
        'public' => _("Public"),
        'actions' => ""
    );
    if (!$enable_frab_import) {
        unset($table_columns['from_pentabarf']);
    }

    return page_with_title(admin_rooms_title(), array(
        buttons(array(
            button(page_link_to('admin_rooms') . '&show=edit', _("add"))
        )),
        msg(),
        table($table_columns, $rooms)
    ));
}
