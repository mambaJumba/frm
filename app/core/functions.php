<?php

function redirect($http = false)
{
	if ($http) {
		$redirect = $http;
	} else {
		$redirect = isset($_SESSION['HTTP_REFERER']) ? $_SESSION['HTTP_REFERER'] : '/';
	}

	header('Location: ' . $redirect);
	exit;
}