<?php

function dashboardView($viewData)
{
    return template_render('../templates/dashboard.html', $viewData);
}

function getPlacesAsJSON(){
      return json_encode(selectAllRooms());
}
