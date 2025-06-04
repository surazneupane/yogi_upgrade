<?php


if (!function_exists("show_current_class")) {

	function show_current_class($args = "", $class = "active")
	{

		$uri = service('uri');
		$segments = $uri->getSegments();
		// $route_obj = new CI_Router;
		// $segments = $route_obj->uri->segments;

		// VIDEOS PAGE SUBMENU SELECTION PATCH
		if ($args == 'all') {
			$args = rawurldecode('فيديوهات');
		}

		if (in_array(rawurlencode($args), $segments)) {
			return $class;
		}
	}
}
