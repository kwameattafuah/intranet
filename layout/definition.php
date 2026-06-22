<?php
    session_name('Aejay');
    session_start();

    date_default_timezone_set("Africa/Accra");
    $copyright = date("Y");

    // define root directory
    define("__ROOT__", str_replace("layout", "", __dir__), TRUE);

    // load environment config
    require_once(__ROOT__ . 'config.php');

    // connect to database and load helpers
    require_once(__ROOT__ . 'core/functions.php');

    define("__URL__",    APP_URL,          TRUE);
    define("__ASSETS__", APP_URL.'/assets', TRUE);
    define("__MEDIA__",  APP_URL.'/media',  TRUE);
    define("__DOCS__",   APP_URL.'/docs/',  TRUE);

    ini_set('display_errors', 0);
?>
