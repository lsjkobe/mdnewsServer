<?php
require_once '../include.php';

$uid = $_GET['uid'];

if(isset($_GET['page'])){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$link = db_connect();
$countSql = "SELECT count(*) FROM message WHERE uid = {$uid}  group by mid";
$pageRow = getAllDataById($countSql);

//每次返回的数量
$pagesize=10;
$pageOffset = $pagesize * ($page-1);
$pageCount = ceil(mysql_num_rows($pageRow)/$pagesize);
// $sql = "select * from message where uid = 1 ";
//获取关注人和自己的信息列表
// $sql = "select * from message where uid in (select followid from relations where relations.uid = {$_SESSION[USER_ID]} ) or uid = {$_SESSION[USER_ID]} order by mCreateDate";

//获取关注人和自己的信息列表(内连接优化查询)
//$sql  = "SELECT *,message.uid as nuid  FROM message INNER JOIN relations ON (relations.followid = message.uid or message.uid = {$_SESSION[USER_ID]}) and relations.uid = {$_SESSION[USER_ID]} order by mCreateDate desc";
$sql  = "SELECT * FROM message WHERE uid = {$uid} group by mid order by mCreateDate desc limit {$pageOffset},{$pagesize}";
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
	}else if($row['mType'] != 0){ //不是原创
		$imgSql = "select * from images where mid = '{$row['sid']}' ";
		$imgResult = getAllDataById($imgSql);
		if($imgResult){
			while($rowImg = mysql_fetch_array($imgResult)){
				$rowImgArray = array(
					'imgsrc' => $rowImg['imgsrc']
				);
				array_push($imgLists, $rowImgArray);
			}
		}
	}
	$userSql = "select * from user where uid = {$row['uid']} limit 1 ";
	$userResult = getOneFromDB($userSql);
	$uName = ($userResult['uid'] == "{$_SESSION[USER_ID]}") ? '我' : $userResult['uName'];
	//是否赞
	$starSql = " SELECT * FROM star where uid = {$_SESSION[USER_ID]} and mid = {$row['mid']}";
	$is_star = getOneFromDB($starSql) ? '1' : '0';

	//是否收藏
	$collectSql = " SELECT * FROM collection where uid = {$_SESSION[USER_ID]} and mid = {$row['mid']}";
	$is_collect = getOneFromDB($collectSql) ? '1' : '0';
	//原创圈子
	if($row['mType'] == 0){
		$rowArray = array(
			'uid' => $userResult['uid'],
			'uHeadImg' => $userResult['uHeadImg'],
			'uName' => $uName,
			'mid' => $row['mid'],
			'content' => $row['mContent'],
			'date' => $row['mCreateDate'],
			'location' => $row['mLocation'],
			'is_star' => $is_star,
			'is_collect' => $is_collect ,
			'mType' => $row['mType'],
			'imglists' => $imgLists
		);
		array_push($listArray, $rowArray);
	}else{
		//源圈子内容
		$forwordSql = "select * from message where mid = {$row['sid']} limit 1 ";
		$forwordResult = getOneFromDB($forwordSql);

		//源圈子发布人信息
		$sourceUserSql = "select * from user where uid = {$forwordResult['uid']} limit 1 ";
		$sourceUserResult = getOneFromDB($sourceUserSql);
		$sName = ($sourceUserResult['uid'] == "{$_SESSION[USER_ID]}") ? '我' : $sourceUserResult['uName'];

		//源圈子是否赞
		$s_starSql = " SELECT * FROM star where uid = {$_SESSION[USER_ID]} and mid = {$row['sid']}";
		$s_is_star = getOneFromDB($s_starSql) ? '1' : '0';

		$rowArray = array(
			'uid' => $userResult['uid'],
			'uHeadImg' => $userResult['uHeadImg'],
			'uName' => $uName,
			'mid' => $row['mid'],
			'content' => $row['mContent'],
			'date' => $row['mCreateDate'],
			'location' => $row['mLocation'],
			'is_star' => $is_star,
			'is_collect' => $is_collect ,
			'mType' => $row['mType'],
			'sourceContent' => $forwordResult['mContent'],
			'sName' => $sName,
			'sid' => $row['sid'],
			'suid' => $sourceUserResult['uid'],
			's_is_star' => $s_is_star,
			'imglists' => $imgLists
		);
		array_push($listArray, $rowArray);
	}
	
}
if($listArray){
	$resultArray = array(
		'lists' => $listArray ,
		'pageCount' => $pageCount,
		'resultCode' => '1'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}else{
	$resultArray = array(
		'resultCode' => '0'
		);
	echo json_encode($resultArray, JSON_UNESCAPED_UNICODE);
}


