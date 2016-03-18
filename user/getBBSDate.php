<?php
require_once '../include.php';
// if(!isLogin()){
// 	//未登录或session过期
// 	echo '400';
// 	exit();
// }

$link = db_connect();

if(!isLogin()){
	//未登录或session过期
	echo '400';
	exit();
}
// $sql = "select * from message where uid = 1 ";
//获取关注人和自己的信息列表
// $sql = "select * from message where uid in (select followid from relations where relations.uid = {$_SESSION[USER_ID]} ) or uid = {$_SESSION[USER_ID]} order by mCreateDate";

//获取关注人和自己的信息列表(内连接优化查询)
$sql  = "SELECT * FROM message INNER JOIN relations ON (relations.followid = message.uid or message.uid = {$_SESSION[USER_ID]}) and relations.uid = {$_SESSION[USER_ID]}";
$bbsArray =  getAllDataById($sql);
$listArray = array();
while($row = mysql_fetch_array($bbsArray)){
	$imgLists = array();
	if($row['uImageCount'] != 0){
		
		$imgSql = "select * from images where mid = '{$row['mid']}' ";
		$imgResult = getAllDataById($imgSql);
		while($rowImg = mysql_fetch_array($imgResult)){
			$rowImgArray = array(
				'imgsrc' => $rowImg['imgsrc']
			);
			array_push($imgLists, $rowImgArray);
		}
	}
	
	$rowArray = array(
		'mid' => $row['mid'],
		'content' => $row['mContent'],
		'date' => $row['mCreateDate'],
		'imglists' => $imgLists
	);
	array_push($listArray, $rowArray);
}
if($listArray){
	$resultArray = array(
		'lists' => $listArray ,
		'resultCode' => '1'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	//内容为空
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}


