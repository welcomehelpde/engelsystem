<?php
function credits_title() {
  return _("Imprint - Impressum");
}

function guest_credits() {
  global $customization;
  return $customization['imprint_content'];
}
?>
