<?php

session_start();
session_destroy();

header("Location: http://bm-market.kz/Office/login.php");
die();

?>