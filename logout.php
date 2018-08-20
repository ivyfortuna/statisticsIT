<?php

    session_start();

    //un st the value for the session and redirect to the login
    unset($_SESSION["login_user"]);
    header("Location: index.html");

?>