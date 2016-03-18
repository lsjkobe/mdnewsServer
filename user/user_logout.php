<?php
require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	echo '400';
	exit();
}
$_SESSION = array(); // 把session清空。
session_destroy();   // 彻底销毁session
echo "0";