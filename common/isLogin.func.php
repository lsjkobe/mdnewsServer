<?php

function isLogin(){
	return isset($_SESSION[USER_SESSION]);
}