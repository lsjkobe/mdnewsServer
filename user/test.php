<?php
require_once '../include.php';
$lifeTime = 5;
session_set_cookie_params($lifeTime);
session_start();
echo $_SESSION[USER_SESSION];