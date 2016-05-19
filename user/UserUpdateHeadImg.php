<?php
require_once "../include.php";
// if(!isLogin()){
// 	//未登录或session过期
// 	$resultArray = array(
// 		'resultCode' => '400'
// 		);
// 	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
// 	exit();
// }

$link = db_connect();
if($_FILES["imgSrc"]["error"] == 0){
	//把图片file保存到images/OriginalImages里
	move_uploaded_file($_FILES["imgSrc"]["tmp_name"],"../images/OriginalImages/".$_FILES["imgSrc"]["name"]);
	$path = MY_HOST."/lsj/mdnews/images/OriginalImages/".$_FILES["imgSrc"]["name"];
	$updateSql = "UPDATE user SET uHeadImg = '{$path}' WHERE uid = {$_SESSION[USER_ID]} ";
}else{
	echo '-1';
	exit();
}

if(updateOneData($updateSql)){
	echo '1';
}else{
	echo '0';
}