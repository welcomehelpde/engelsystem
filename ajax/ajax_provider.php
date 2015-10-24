<?php
require_once realpath(__DIR__ . '/../includes/engelsystem_provider.php');

if (isset($_POST['action']) && $_POST['action'] == 'getAllPlaces') echo getPlacesAsJSON();

?>
