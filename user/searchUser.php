<?php

require_once '../include.php';

$keyWord = $_GET['keyWord'];

$link = db_connect();

$sql = "SELECT * FROM user WHERE uName LIKE '%{$keyWord}%' ";

$userData = getAllDataById($sql);
if(mysql_num_rows($userData) !=0 ){
	$userLists = array();
	while($row = mysql_fetch_array($userData)){

		//是否关注
		$rSql1 = "SELECT * FROM relations WHERE uid = {$row['uid']} AND followid = {$_SESSION[USER_ID]}";
		//是否被关注
		$rSql2 = "SELECT * FROM relations WHERE uid = {$_SESSION[USER_ID]} AND followid = {$row['uid']}";
		if( $row['uid'] == $_SESSION[USER_ID] ){
			$key = '-1';
		}else if(getOneFromDB($rSql1) && getOneFromDB($rSql2)){
			$key = '2';
		}else if(getOneFromDB($rSql1)){
			$key = '1';
		}else{
			$key = '0';
		}
		$rowArray = array(
			'uid' => $row['uid'],
			'uName' => $row['uName'],
			'uImg' => $row['uHeadImg'],
			'uFansCount' => $row['uFansCount'],
			'key' => $key
		);
		array_push($userLists, $rowArray);
	}
	$userArray = array(
		'resultCode' => '1',
		'userLists' => $userLists
	);
	echo json_encode($userArray,JSON_UNESCAPED_UNICODE);
}else{
	$arraylists = array(
			'resultCode' => '0'
		);
	echo json_encode($arraylists);
}