<?php
function credits_title() {
  return _("Imprint - Impressum");
}

function guest_credits() {
  return template_render('../templates/guest_imprint.html', array());
}
?>