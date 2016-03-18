<?php
header("content-type:text/html;charset=utf-8");
date_default_timezone_set("PRC");
session_start();
define("ROOT",dirname(__FILE__));
set_include_path(".".PATH_SEPARATOR.ROOT."/configs".PATH_SEPARATOR.ROOT."/common");
require_once 'config.def.php';
require_once 'mysql.func.php';
require_once 'isLogin.func.php';
