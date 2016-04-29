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

$mid = $_GET['mid'];

$link = db_connect();


$forwardArray = array();
//类似多叉树获取转发
function visit($id)
{
	global $forwardArray;
    $recordSql = "SELECT mid,uid FROM message WHERE mLastId = {$id} ORDER BY mCreateDate DESC";  //搜索nodeid的所有下层节点
    $forwardRow = getAllDataById($recordSql);
    if(mysql_num_rows($forwardRow) == 0){
    	return ;
    }
    while ($row = mysql_fetch_array($forwardRow)) {
        	visit($row['mid']);
        	array_push($forwardArray,$row['uid']);
    }
}
visit($mid);
rsort($forwardArray);
$forwardListArray = array();
foreach ($forwardArray as $value) {
	$forwardUserSql = "SELECT * FROM user WHERE uid = {$value} limit 1 ";
		$forwardUserResult = getOneFromDB($forwardUserSql);
		$forwardRowArray = array(
			'uid' => $forwardUserResult['uid'],
			'uName' =>$forwardUserResult['uName'],
			'uHeadImg' => $forwardUserResult['uHeadImg']
		);
		array_push($forwardListArray, $forwardRowArray);
}

//获取赞
$starSql = " SELECT * FROM star where mid = {$mid}";
$starLists = getAllDataById($starSql);
$starListArray = array();
if(mysql_num_rows($starLists) != 0){
	
	while ($starRow = mysql_fetch_array($starLists)){
		$starUserSql = "select * from user where uid = {$starRow['uid']} limit 1 ";
		$starUserResult = getOneFromDB($starUserSql);
		$StarRowArray = array(
			'sid' => $starRow['sid'],
			'uid' => $starRow['uid'],
			'mid' => $starRow['mid'],
			'uName' =>$starUserResult['uName'],
			'uHeadImg' => $starUserResult['uHeadImg']
		);
		array_push($starListArray, $StarRowArray);
	}
}

//获取评论
$commentSql = " SELECT * FROM comment where mid = {$mid} ORDER BY cCreateDate DESC";
$commentLists = getAllDataById($commentSql);
$listArray = array();
if(mysql_num_rows($commentLists) != 0){
	while ($row = mysql_fetch_array($commentLists)){

		$userSql = "select * from user where uid = {$row['uid']} limit 1 ";
		$userResult = getOneFromDB($userSql);

		$rowArray = array(
			'cid' => $row['cid'],
			'uid' => $row['uid'],
			'mid' => $row['mid'],
			'uName' =>$userResult['uName'],
			'uHeadImg' => $userResult['uHeadImg'],
			'cCreateDate' => $row['cCreateDate'],
			'content' =>$row['cContent'],
			'cIsImage' => $row['cIsImage'],
			'commentImg' => $row['cImgSrc']
		);
		array_push($listArray, $rowArray);
	}
	
}

if($listArray || $starListArray || $forwardListArray){
	$allListArray = array(
			'forwardLists' => $forwardListArray,
			'commentLists' => $listArray,
			'starLists' => $starListArray
		);
	$arraylists = array(
			'resultCode' => '1',
			'allDataArray' => $allListArray
		);
	echo json_encode($arraylists,JSON_UNESCAPED_UNICODE);
}else{
	$arraylists = array(
			'resultCode' => '0'
		);
	echo json_encode($arraylists);
}