<?php
require_once '../include.php';

$link = db_connect();
$mid = $_GET['mid'];

$forwardArray = array();
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
echo json_encode($forwardListArray,JSON_UNESCAPED_UNICODE);
