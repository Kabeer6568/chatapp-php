<?php

include "includes/header.php";
include "classes/user.php";

$user = new User;

session_start();

$_SESSION = [];

session_destroy();

header("location: {$user->route}");