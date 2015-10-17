<?php
function credits_title() {
  return _("FAQ");
}

function guest_credits() {
  return template_render('../templates/guest_faq2.html', array());
}
?>