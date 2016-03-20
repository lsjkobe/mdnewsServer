<?php
require_once '../include.php';
if(!isLogin()){
	//未登录或session过期
	$resultArray = array(
		'resultCode' => '400'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
	exit();
}

$link = db_connect();

// $sql = "select * from message where uid = 1 ";
//获取关注人和自己的信息列表
// $sql = "select * from message where uid in (select followid from relations where relations.uid = {$_SESSION[USER_ID]} ) or uid = {$_SESSION[USER_ID]} order by mCreateDate";

//获取关注人和自己的信息列表(内连接优化查询)
//$sql  = "SELECT *,message.uid as nuid  FROM message INNER JOIN relations ON (relations.followid = message.uid or message.uid = {$_SESSION[USER_ID]}) and relations.uid = {$_SESSION[USER_ID]} order by mCreateDate desc";
$sql  = "SELECT *, message.uid as nuid FROM message INNER JOIN relations ON (relations.uid = message.uid and relations.followid = {$_SESSION[USER_ID]})  or message.uid = {$_SESSION[USER_ID]} group by message.mid order by mCreateDate desc";
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
	$userSql = "select * from user where uid = {$row['nuid']} limit 1 ";
	$userResult = getOneFromDB($userSql);

	$uName = ($userResult['uid'] == "{$_SESSION[USER_ID]}") ? '我' : $userResult['uName'];
	$rowArray = array(
		'uid' => $userResult['uid'],
		'uHeadImg' => $userResult['uHeadImg'],
		'uName' => $uName,
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
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}


