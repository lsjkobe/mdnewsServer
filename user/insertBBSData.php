<?php
//插入用户发送的数据
require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	echo '400';
	exit();
}
if($_SERVER['REQUEST_METHOD'] != 'POST'){
	//不是post请求
	echo '401';
	exit();
}
$mContent = $_POST['content'];

if(!$mContent){
	//内容为空
	echo '100';
	exit();
}

$link = db_connect();
if(empty($_POST['location'])){
	$sql = "insert into message(mContent,mCreateDate,uid) values('{$mContent}',NOW(),{$_SESSION[USER_ID]})";
}else{
	$location = $_POST['location'];
	$sql = "insert into message(mContent,mCreateDate,uid,mLocation) values('{$mContent}',NOW(),{$_SESSION[USER_ID]},'{$location}')";
}
// $sql = "insert into message(mContent,mCreateDate,uid) values('{$mContent}',NOW(),{$_SESSION[USER_ID]})";
// $sql = "insert into message(uid,mContent,mCreateDate) values('{$_SESSION[USER_ID]}','{$mContent}',NOW())";
if(oneDataInsert($sql)){
	// $mid = 
	insertImages();
	$arr = array('resultCode'=>1);  
	echo json_encode($arr);
}else{
	$arr = array('resultCode'=>0);  
	echo json_encode($arr);
}

function insertImages(){
	$lastId = mysql_insert_id();
	if(!empty($_FILES["imgList"]["name"])){
	$has_picture = false;
	// 判断是否为数组
	if(is_array($_FILES["imgList"]["name"])){
		$has_picture = true;
		if(count($_FILES["imgList"]["name"]) > 9){
			$arr = array('resultCode'=>101);   //图片张数最大不能超过9张
			echo json_encode($arr);
			return ;
		}

		$count = count($_FILES["imgList"]['name']);
		for($i=0; $i<$count; $i++) {
			if($_FILES["imgList"]["error"][$i] == 0){
				//把图片file保存到images/OriginalImages里
				move_uploaded_file($_FILES["imgList"]["tmp_name"][$i],"../images/OriginalImages/".$_FILES["imgList"]["name"][$i]);
				$path = MY_HOST."/lsj/mdnews/images/OriginalImages/".$_FILES["imgList"]["name"][$i];
				$imgsql = "insert into images(mid,imgsrc) values('{$lastId}','{$path}')";
				oneDataInsert($imgsql);
			}
		}
		$updateImgCount = "update message set uImageCount = {$count} where mid = {$lastId}";
		updateOneData($updateImgCount);

	}else{
		if($_FILES["imgList"]["name"]){
			$has_picture = true;
		}
	}
}
}