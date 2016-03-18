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
$sql = "insert into message(mContent,mCreateDate) values('{$mContent}',NOW())";
// $sql = "insert into message(uid,mContent,mCreateDate) values('{$_SESSION[USER_ID]}','{$mContent}',NOW())";
if(oneDataInsert($sql)){
	echo '1';
}else{
	echo '0';
}
