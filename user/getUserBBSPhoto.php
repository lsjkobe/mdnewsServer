<?php
require_once "../include.php";

$uid = $_GET['uid'];

if(isset($_GET['page'])){
	$page = $_GET['page'];
}else{
	$page = 1;
}
$link = db_connect();
$countSql = "SELECT count(*) FROM message WHERE uid = {$uid} AND uImageCount > 0  group by mid";
$pageRow = getAllDataById($countSql);

//每次返回的数量
$pagesize=20;
$pageOffset = $pagesize * ($page-1);
$pageCount = ceil(mysql_num_rows($pageRow)/$pagesize);

$midSql = "SELECT * FROM message WHERE uid = {$uid} AND uImageCount > 0  group by mid ORDER BY mCreateDate DESC";

$mID = getAllDataById($midSql);


if( mysql_num_rows($mID) != 0 ){

	$photoLists = array();
	while($row = mysql_fetch_array($mID)){
		$photoSql = "SELECT * FROM images WHERE mid = {$row['mid']}";
		$photoData = getAllDataById($photoSql);
		while($photoRow = mysql_fetch_array($photoData)) {

			$rowArray = array(
				'imgSrc' => $photoRow['imgsrc']
			);
			array_push($photoLists, $rowArray);
		}
	}
	$photoArray = array(
		'resultCode' => '1',
		'pageCount' => $pageCount,
		'photoLists' => $photoLists
	);
	echo json_encode($photoArray,JSON_UNESCAPED_UNICODE);

}else{
	$arraylists = array(
			'resultCode' => '0'
		);
	echo json_encode($arraylists);
}