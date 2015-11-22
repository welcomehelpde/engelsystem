<?php
function credits_title() {
  return _("Privacy - Datenschutzbedingungen");
}

function guest_credits() {
  global $customization;
  return $customization['privacy_content'];
}
?>
