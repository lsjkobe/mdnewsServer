<?php
require_once "../include.php";

$link = db_connect();

$nickName = $_POST['nickname'];
$stateContent = $_POST['statecontent'];
$qq = $_POST['qq'];

$updateSql = "UPDATE user SET uName='{$nickName}', uStateContent='{$stateContent}', uQQ='$qq' WHERE uid={$_SESSION[USER_ID]} ";
if(updateOneData($updateSql)){
	echo "1";
}else{
	echo '0';
}