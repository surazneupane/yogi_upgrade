<?php

use CodeIgniter\Config\Services;
use CodeIgniter\Database\Config;

if (!function_exists("siteConfiguration")) {

	function siteConfiguration()
	{
		$db = Config::connect();
		$session = Services::session();
		$clang = $session->get("lang");

		if ($clang == 'fr') {
			$qry = 'SELECT site_title_fr AS site_title, site_description_fr AS site_description, site_offline, site_offlinemessage, site_offlineimage, from_name, from_email, site_caching, site_caching_time FROM site_configuration'; // select data from db		
		} else {
			$qry = 'SELECT * FROM site_configuration'; // select data from db		
		}
		$site_configurations = $db->query($qry)->getResultArray();

		return $site_configurations[0];
	}
}

if (!function_exists("homeConfiguration")) {

	function homeConfiguration($lang = '')
	{

		$db = Config::connect();


		if ($lang == 'fr') {
			$qry = 'SELECT *, section_1_data_fr AS section_1_data, section_2_data_fr AS section_2_data, section_3_data_fr AS section_3_data FROM home_configuration'; // select data from db		
		} else {
			$qry = 'SELECT * FROM home_configuration'; // select data from db		
		}
		$home_configurations = $db->query($qry)->getResultArray();

		return $home_configurations[0];
	}
}

if (!function_exists("GetUserDetailsByUsername")) {

	function GetUserDetailsByUsername($username)
	{

		$db = Config::connect();


		$qry = 'SELECT * FROM users WHERE username = "' . $username . '"'; // select data from db		
		$user = $db->query($qry)->getResultArray();

		return $user[0];
	}
}

if (!function_exists("GetTeacherDetailsById")) {

	function GetTeacherDetailsById($id)
	{

		$db = Config::connect();


		$qry = 'SELECT * FROM teachers WHERE id = "' . $id . '"'; // select data from db		
		$teacher = $db->query($qry)->getResultArray();

		return $teacher[0];
	}
}

if (!function_exists("GetArticleCounterByUserId")) {

	function GetArticleCounterByUserId($id)
	{

		$db = Config::connect();


		$qry = 'SELECT count(*) AS total FROM articles WHERE created_by = ' . $id; // select data from db		
		$records = $db->query($qry)->getResultArray();

		return $records[0]['total'];
	}
}

if (!function_exists("dateToFrench")) {

	// Convert a date or timestamp into French.
	function dateToFrench($date, $format)
	{
		$english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
		$french_days = array('lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi', 'dimanche');
		$english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
		$french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
		return str_replace($english_months, $french_months, str_replace($english_days, $french_days, date($format, strtotime($date))));
	}
}
