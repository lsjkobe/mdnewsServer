<?php
//关注或取消

require_once "../include.php";

$link = db_connect();

$uid = $_GET['uid'];
$key = $_GET['key'];

if($key == '0'){
	$sql = "INSERT INTO relations(uid,followid,rCreateDate) VALUES( {$uid}, {$_SESSION[USER_ID]}, NOW() ) ";
}else{
	$sql = "DELETE FROM relations WHERE followid={$_SESSION[USER_ID]} AND uid = {$uid} ";
}

if(oneDataInsert($sql)){
	$resultArray = array(
		'resultCode' => '1'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}

