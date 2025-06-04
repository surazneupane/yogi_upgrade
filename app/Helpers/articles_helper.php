<?php



// GET CATEGORY NAME

use CodeIgniter\Config\Services;
use CodeIgniter\Database\Config;

if (! function_exists('getCategoryName')) {
	function getCategoryName($category_id)
	{

		$db = Config::connect();

		if (is_array($category_id)) {
			$category_id = implode('","', $category_id);
			$qry = 'Select title from categories where id IN ("' . $category_id . '")'; // select data from db
		} else {
			$qry = 'Select title from categories where id = ' . $category_id . ')'; // select data from db
		}
		$category = $db->query($qry)->getResultArray();

		if (count($category) > 1) {
			$category_arr = array();
			foreach ($category as $cat) {
				$category_arr[] = $cat['title'];
			}
		} else {
			$category_arr = array($category[0]['title']);
		}

		return implode(', ', $category_arr);
	}
}


// GET CATEGORY NAME
if (! function_exists('getTagName')) {
	function getTagName($tag_id)
	{

		$db = Config::connect();

		if (is_array($tag_id)) {
			$tag_id = implode('","', $tag_id);
			$qry = 'Select title from tags where id IN ("' . $tag_id . '")'; // select data from db
		} else {
			$qry = 'Select title from tags where id = ' . $tag_id . ')'; // select data from db
		}
		$tag = $db->query($qry)->getResultArray();

		if (count($tag) > 1) {
			$tag_arr = array();
			foreach ($tag as $ta) {
				$tag_arr[] = $ta['title'];
			}
		} else {
			$tag_arr = array($tag[0]['title']);
		}

		return implode(', ', $tag_arr);
	}
}


// GET FEATURED ARTICLES
if (! function_exists('getFeaturedArticles')) {
	function getFeaturedArticles($category_id = '', $orderby = 'id', $ordering = 'DESC', $limit_start = '0', $limit_end = '*')
	{

		$db = Config::connect();

		$where = ' `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND `featured` = "1"';
		if ($category_id != '') {
			$where .= ' AND FIND_IN_SET(' . $category_id . ', `category_ids`)';
		}
		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date`, `published_date` FROM `articles` WHERE ' . $where . ' ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db		
		}
		$featured_articles = $db->query($qry)->getResultArray();

		return $featured_articles;
	}
}


// GET PUBLISHED ARTICLES
if (! function_exists('getArticlesByCatID')) {
	function getArticlesByCatID($category_id = '', $status = '')
	{

		$db = Config::connect();

		$where = ' 1';
		if ($status != '') {
			$where .= ' AND `status` = "' . $status . '"';
		}
		if ($category_id != '') {
			$where .= ' AND FIND_IN_SET(' . $category_id . ', `category_ids`)';
		}
		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM `articles` WHERE ' . $where . ' ORDER BY `id` DESC'; // select data from db

		$articles = $db->query($qry)->getResultArray();

		return $articles;
	}
}



// GET AUTHOR ARTICLES
if (! function_exists('getAuthorArticles')) {
	function getAuthorArticles($author, $orderby = 'id', $ordering = 'DESC', $limit_start = '0', $limit_end = '*')
	{

		$db = Config::connect();

		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM `articles` WHERE `created_by` = (SELECT `id` FROM `users` WHERE `username` = "' . $author . '" ) AND `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" ORDER BY `' . $orderby . '` ' . $ordering; // select data from db		
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		}

		$author_articles = $db->query($qry)->getResultArray();

		return $author_articles;
	}
}


// GET MOSTREAD ARTICLES
if (! function_exists('getMostreadArticles')) {
	function getMostreadArticles($category_id = '', $orderby = 'hits', $ordering = 'DESC', $limit_start = '0', $limit_end = '*')
	{

		$db = Config::connect();

		$where = ' `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '"';
		if ($category_id != '') {
			$where .= ' AND FIND_IN_SET(' . $category_id . ', `category_ids`)';
		}
		$daybeforeoneweek = date("Y-m-d H:i:s", strtotime("-2 week"));
		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE ' . $where . ' AND `published_date` >= "' . $daybeforeoneweek . '" ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		}

		$mostread_articles = $db->query($qry)->getResultArray();

		return $mostread_articles;
	}
}



// GET NEW ADDED ARTICLES (Optionally - you can also get them categorywise)
if (! function_exists('getNewAddedArticles')) {
	function getNewAddedArticles($category_id = '', $orderby = 'id', $ordering = 'DESC', $limit_start = '0', $limit_end = '*', $lang = '')
	{

		$db = Config::connect();


		$where = ' `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '"';
		if ($category_id != '') {
			$where .= ' AND FIND_IN_SET(' . $category_id . ', `category_ids`)';
		}
		if ($lang == 'fr') {
			$qry = 'SELECT `id`, `category_ids`, title_fr AS title, alias_fr AS alias, `article_layout`, description_fr AS description, image_fr AS image, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE ' . $where . ' ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
		} else {
			$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE ' . $where . ' ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
		}
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		}

		$newadded_articles = $db->query($qry)->getResultArray();

		return $newadded_articles;
	}
}



// GET RELATED ARTICLES
if (! function_exists('getRelatedArticles')) {
	function getRelatedArticles($categories, $except_article_id = NULL, $orderby = 'hits', $ordering = 'DESC', $limit_start = '0', $limit_end = '*')
	{

		$db = Config::connect();
		$session = Services::session();

		$clang = $session->get("lang");

		if (!is_array($categories)) {
			$categories = array($categories);
		}

		$where = '';
		$categoryCnt = count($categories);
		$i = 1;
		$where .= '(';
		foreach ($categories as $category) {
			if ($i == $categoryCnt) {
				$where .= 'FIND_IN_SET(' . $category . ', `category_ids`) ';
			} else {
				$where .= 'FIND_IN_SET(' . $category . ', `category_ids`) OR ';
			}
			$i++;
		}
		$where .= ')';
		if ($except_article_id != '') {
			$where .= ' AND `id` != ' . $except_article_id;
		}

		$db = Config::connect();

		if ($orderby == 'rand') {
			if ($clang == 'fr') {
				$qry = 'SELECT `id`, `category_ids`, `title_fr` AS title, `alias_fr` AS alias, `article_layout`, `description_fr` AS description, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND ' . $where . ' ORDER BY rand()'; // select data from db
			} else {
				$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND ' . $where . ' ORDER BY rand()'; // select data from db
			}
		} else {
			if ($clang == 'fr') {
				$qry = 'SELECT `id`, `category_ids`, `title_fr` AS title, `alias_fr` AS alias, `article_layout`, `description_fr` AS description, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND ' . $where . ' ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
			} else {
				$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `hits`, `created_date`, `published_date` FROM `articles` WHERE `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND ' . $where . ' ORDER BY `' . $orderby . '` ' . $ordering; // select data from db
			}
		}
		if ($limit_end != '*') {
			$qry = $qry . ' LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		}

		$related_articles = $db->query($qry)->getResultArray();

		return $related_articles;
	}
}


// INCREMENT VIEWS OF ARTICLES
if (! function_exists('increaseArticlesViews')) {
	function increaseArticlesViews($article_id = NULL)
	{

		if (!empty($article_id)) {
			$db = Config::connect();

			$qry = 'UPDATE `articles` SET `hits` = `hits` + 1 WHERE `id` = ' . $article_id; // select data from db			
			$db->query($qry);
		}
	}
}


// GENERATE ARTICLE LINK
if (! function_exists('generateArticleLink')) {
	function generateArticleLink($category_ids, $article_alias)
	{

		$category_ids = explode(',', $category_ids);

		$category_idsCnt = count($category_ids);
		if ($category_idsCnt == 1) {
			$category_details = getCategoryDetails($category_ids[0]);
		} else {
			$category_details = getCategoryDetails($category_ids[1]);
		}

		if ($category_details['parent_id'] != 0) {
			$parent_category_details = getParentCategoryDetails($category_details['id']);
		}

		if ($category_details['parent_id'] == 0) {
			$article_preview_link = site_url('article/' . rawurldecode($category_details['alias'] . '/' . $article_alias));
		} else {
			$article_preview_link = site_url('article/' . rawurldecode($category_details['alias'] . '/' . $parent_category_details['alias'] . '/' . $article_alias));
		}

		return $article_preview_link;
	}
}
