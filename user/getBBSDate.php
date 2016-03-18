<?php
require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	echo '400';
	exit();
}

$link = db_connect();

$sql = "select * from message where uid = '{$_SESSION[USER_ID]}' ";
$bbsArray =  getAllDataByUId($sql);
while($row = mysql_fetch_array($bbsArray )){
	echo $row['mContent'];
}




