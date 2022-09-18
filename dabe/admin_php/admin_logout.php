<?php

include '../php/db_config.php';

session_start();
session_unset();
session_destroy();

header('location:../admin_php/admin_login.php');


?>