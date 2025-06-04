<?php


// GET TAG DETAILS BY ID(s)

use CodeIgniter\Database\Config;

if (! function_exists('getTagDetailsByID')) {
	function getTagDetailsByID($article_tags_array, $orderby = 'id', $ordering = 'DESC', $limit_start = '0', $limit_end = '*')
	{

		if (!is_array($article_tags_array)) {
			$article_tags_array = array($article_tags_array);
		}

		$article_tags_str = implode(',', $article_tags_array);


		if ($orderby == 'rand') {
			$qry = 'SELECT * FROM `tags` WHERE `status` = "1" AND FIND_IN_SET(`id`, "' . $article_tags_str . '") ORDER BY rand()'; // select data from db
		} else {
			$qry = 'SELECT * FROM `tags` WHERE `status` = "1" AND FIND_IN_SET(`id`, "' . $article_tags_str . '") ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
		}
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		}

		$tag_details = Config::connect()->query($qry)->getResultArray();

		return $tag_details;
	}
}



// GET TAG DETAILS BY ALIAS
if (! function_exists('getTagDetailsByAlias')) {
	function getTagDetailsByAlias($tag_alias)
	{

		$CI = get_instance();
		$qry = 'SELECT * FROM `tags` WHERE `status` = "1" AND `alias` = "' . urldecode($tag_alias) . '"'; // select data from db				
		$tag_details = Config::connect()->query($qry)->getResultArray();

		return $tag_details[0];
	}
}
