<?php



// GET CATEGORY NAME

use CodeIgniter\Database\Config;

if (! function_exists('getCategoryDetails')) {
	function getCategoryDetails($category_id)
	{

		if ($category_id != '') {

			$db = Config::connect();

			$qry = 'SELECT `id`, `parent_id`, `title`, `alias`, `color_style`, `description`, `image`, `slider_data`, `meta_title`, `meta_description` FROM categories WHERE `id` = ' . $category_id; // select data from db
			$category = $db->query($qry)->getResultArray();
			$category = $category[0];
		} else {

			$category = '';
		}

		return $category;
	}
}


// GET CATEGORY NAME
if (! function_exists('getCategoryDetailsByAlias')) {
	function getCategoryDetailsByAlias($category_alias, $lang = '')
	{


		if ($category_alias != '') {

			$db = Config::connect();
			
			if ($lang == 'fr') {
				$qry = 'SELECT `id`, `parent_id`, title_fr AS title, `alias`, `color_style`, `description`, `image`, `slider_data`, `meta_title`, `meta_description` FROM categories WHERE `alias_fr` = "' . urldecode($category_alias) . '"'; // select data from db
			} else {
				$qry = 'SELECT `id`, `parent_id`, `title`, `alias`, `color_style`, `description`, `image`, `slider_data`, `meta_title`, `meta_description` FROM categories WHERE `alias` = "' . urldecode($category_alias) . '"'; // select data from db
			}

			$category = $db->query($qry)->getResultArray();
			@$category = $category[0];
		} else {

			$category = '';
		}

		return $category;
	}
}



// GET CATEGORY NAME
if (! function_exists('getParentCategoryDetails')) {
	function getParentCategoryDetails($category_id)
	{

		if ($category_id != '') {

			$db = Config::connect();

			$qry = 'SELECT * FROM `categories` WHERE id = (SELECT `parent_id` FROM `categories` WHERE `id` = ' . $category_id . ')'; // select data from db
			$category = $db->query($qry)->getResultArray();

			$category = $category[0];
		} else {

			$category = '';
		}

		return $category;
	}
}



// GENERATE CATEGORY LINK
if (! function_exists('generateCategoryLink')) {
	function generateCategoryLink($category_id)
	{

		$category_details = getCategoryDetails($category_id);

		if ($category_details['parent_id'] != 0) {
			$parent_category_details = getParentCategoryDetails($category_details['id']);
		}

		if ($category_details['parent_id'] == 0) {
			$category_page_link = site_url('الفئة/' . rawurldecode($category_details['alias']));
		} else {
			$category_page_link = site_url('الفئة/' . rawurldecode($category_details['alias'] . '/' . $parent_category_details['alias']));
		}

		return $category_page_link;
	}
}
