<?php
function credits_title() {
  return _("Privacy - Datenschutzbedingungen");
}

function guest_credits() {
  return template_render('../templates/guest_privacy.html', array());
}
?>