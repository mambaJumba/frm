<?php

namespace app\core;

class Router {
	
	protected static $routes = [];
	protected static $route = [];
	protected static $params = [];

	public static function add($regexp, $route = []) {
		self::$routes[trim($regexp, '/')] = $route;
	}

	public static function getRoutes() {
		return self::$routes;
	}

	public static function getRoute() {
		return self::$route;
	}

	public static function getParams() {
		return self::$params;
	}

	private static function matchRoute($url){
		foreach (self::$routes as $pattern => $route) {
			$pattern = self::convertPattern($pattern);
			if (preg_match("#^$pattern$#i", $url, $matches)) {
				if (!in_array('action', array_keys($route))) {
					$route['action'] = 'index';
				}

				self::$route = $route;

				unset($matches[0]);
				self::$params = array_values($matches);

				return true;
			}		
		}
		return false;
	}

	public static function dispatch($url) {
		$url = self::removeQuery($url);

		if (self::matchRoute($url)) {
			$controller = 'app\controllers\\' . self::$route['controller'];

			if (class_exists($controller)) {
				$cObj = new $controller;
				$action = self::$route['action'];
				if (method_exists($cObj, $action)) {
					call_user_func_array([$cObj, $action], self::$params);
				} else {
					echo "$controller::$action doesnt exists";	
				}
			} else {
				echo "controller $controller doesnt exists";
			}
		} else {
			http_response_code(404);
			echo 404;
		}
	}

	private static function convertPattern($pattern) {
		return preg_replace_callback("#\{[A-z0-9]+\}#", function () {
			return '([A-z0-9]+)';
		}, $pattern);	
	}

	public static function removeQuery($url) {
		if ($url) {
			$params = explode('&', $url, 2);
			if (strpos($params[0], '=') == false) {
				return rtrim($params[0], '/');
			} else {
				return '';
			}
		}

		return $url;
	}

}