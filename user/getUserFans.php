<?php

require_once '../include.php';

$key = $_GET['key'];
$link = db_connect();
if($key == 0){
	//粉丝
	$sql = "SELECT *,user.uid AS uuid FROM user INNER JOIN relations ON (user.uid = relations.followid AND relations.uid={$_SESSION[USER_ID]})";
}else{
	//关注
	$sql = "SELECT *,user.uid AS uuid FROM user INNER JOIN relations ON (user.uid = relations.uid AND relations.followid={$_SESSION[USER_ID]})";
}

$userData = getAllDataById($sql);
if(mysql_num_rows($userData) !=0 ){
	$userLists = array();
	while($row = mysql_fetch_array($userData)){
		//是否关注
		$rSql1 = "SELECT * FROM relations WHERE uid = {$row['uuid']} AND followid = {$_SESSION[USER_ID]}";
		//是否被关注
		$rSql2 = "SELECT * FROM relations WHERE uid = {$_SESSION[USER_ID]} AND followid = {$row['uuid']}";
		if( $row['uuid'] == $_SESSION[USER_ID] ){
			$key = '-1';
		}else if(getOneFromDB($rSql1) && getOneFromDB($rSql2)){
			$key = '2';
		}else if(getOneFromDB($rSql1)){
			$key = '1';
		}else{
			$key = '0';
		}
		$rowArray = array(
			'uid' => $row['uuid'],
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