<?php
// require_once '../include.php';
// $username = $_REQUEST['username'];

// if($username == MD_DB_HOST){
// 	echo "成功";
// }else{
// 	echo "失败";
// }
function db_connect(){
	$connect = mysql_connect(MD_DB_HOST, MD_DB_USER,MD_DB_PASSWORD) or die('数据库连接失败');
	mysql_select_db(MD_DB_NAME, $connect);
	mysql_query("set names UTF8MB4", $connect);
	return $connect;
}

/**
 * 完成记录插入的操作
 * @param string $table
 * @param array $array
 * @return number
 */
function insert($table,$array){
	$keys=join(",",array_keys($array));
	$vals="'".join("','",array_values($array))."'";
	$sql="insert {$table}($keys) values({$vals})";
	mysql_query($sql);
	return mysql_insert_id();
}

/**
 *插入一条记录
 * @return number
 */
function oneDataInsert($sql){

	return mysql_query($sql);
}

/**
 *得到指定一条记录
 * @param string $sql
 * @param string $result_type
 * @return multitype:
 */
function getOneFromDB($sql,$result_type=MYSQL_ASSOC){
	$result=mysql_query($sql);
	$row=mysql_fetch_array($result,$result_type);
	return $row;
}

function getAllDataById($sql){
	$result=mysql_query($sql);
	return $result;
}

function updateOneData($sql){
	return mysql_query($sql);
}