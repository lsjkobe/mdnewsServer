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
$mid = $_POST['mid'];
$content = $_POST['content'];
if(empty($_FILES['imgSrc']['name'])){
	$commentSql = "INSERT INTO comment(cCreateDate,cContent,cIsImage,uid,mid) VALUES(NOW(), '{$content}', 0, {$_SESSION[USER_ID]}, {$mid}) ";
}else{
	if($_FILES["imgSrc"]["error"] == 0){
		//把图片file保存到images/OriginalImages里
		move_uploaded_file($_FILES["imgSrc"]["tmp_name"],"../images/OriginalImages/".$_FILES["imgSrc"]["name"]);
		$path = MY_HOST."/lsj/mdnews/images/OriginalImages/".$_FILES["imgSrc"]["name"];
		$commentSql = "INSERT INTO comment(cCreateDate,cContent,cIsImage,uid,mid,cImgSrc) VALUES(NOW(), '{$content}', 1, {$_SESSION[USER_ID]}, {$mid}, '{$path}' )";
	}
}

if(oneDataInsert($commentSql)){
	echo '1';
}else{
	echo '0';
}