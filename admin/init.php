<?php
    date_default_timezone_set("Africa/Nouakchott");
    require_once "MVC/init.mvc.php";

    // includes 

    $func = "includes/functions/";
    $tpl  = "includes/templates/";

    $css  = "layout/css/";
    $js   = "layout/js/";

    include $func . "functions.php";
    include $tpl . "header.php";
    
    if(!isset($noNav)):
        include $tpl . "navbar.php";
    endif;



?>