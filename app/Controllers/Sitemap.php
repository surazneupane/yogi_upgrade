<?php

namespace App\Controllers;

use App\Models\SiteMapModel;
use CodeIgniter\Database\Config;
use Config\Services;

class Sitemap extends BaseController
{

	public $db;
	public $articles = [];
	public $categories = [];
	public $siteMapModel;

	public function __construct()
	{

		// We load the url helper to be able to use the base_url() function
		// $this->load->helper('url');
		// $this->load->helper('articles');
		// $this->load->helper('categories');

		// $this->load->model('sitemap_model');

		$this->db = Config::connect();
		$this->siteMapModel = new SiteMapModel();

		$siteConfiguration = siteConfiguration();

		if ($siteConfiguration['site_caching']) {
			Services::response()->setCache([
				'max-age' => $siteConfiguration['site_caching_time'] * 60,
			]);
		}

		// ARTICLES LIST FOR XML GENERATION
		if (session()->get('lang') == 'fr') {
			$qry = 'SELECT `id`, `category_ids`, `title_fr` AS `title`, `alias`, `article_layout`, `description_fr` AS `description`, `image`, `media_images`, `media_image_main`, `created_date`, `updated_date` FROM `articles` WHERE `status` = 1'; // select data from db
		} else {
			$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `updated_date` FROM `articles` WHERE `status` = 1'; // select data from db
		}
		$articles = $this->db->query($qry)->getResultArray();
		
		// Array of some articles for demonstration purposes
		$this->articles = array();
		foreach ($articles as $article) {
			$article_link = generateArticleLink($article['category_ids'], $article['alias']);
			if ($article['updated_date'] == '0000-00-00 00:00:00') {
				$article['updated_date'] = $article['created_date'];
			}
			$this->articles[] = array(
				'loc' => $article_link,
				'lastmod' => date('Y-m-d', strtotime($article['updated_date'])),
				'changefreq' => 'monthly',
				'priority' => 0.5
			);
		}


		// CATEGORIES LIST FOR XML GENERATION
		$qry = 'SELECT `id`, `title`, `alias`, `description`, `image`, `created_date` FROM `categories` WHERE `status` = 1'; // select data from db
		$categories = $this->db->query($qry)->getResultArray();

		// Array of some articles for demonstration purposes
		$this->categories = array();
		foreach ($categories as $category) {
			$category_link = generateCategoryLink($category['id']);
			$this->categories[] = array(
				'loc' => $category_link,
				'lastmod' => date('Y-m-d', strtotime($category['created_date'])),
				'changefreq' => 'monthly',
				'priority' => 0.5
			);
		}
	}

	/**
	 * Generate a sitemap index file
	 * More information about sitemap indexes: http://www.sitemaps.org/protocol.html#index
	 */
	public function index()
	{

		$this->siteMapModel->add(base_url('sitemap/general'), date('Y-m-d', time()));
		$this->siteMapModel->add(base_url('sitemap/articles'), date('Y-m-d', time()));
		$this->siteMapModel->add(base_url('sitemap/categories'), date('Y-m-d', time()));
		return $this->siteMapModel->output('sitemapindex');
	}

	/**
	 * Generate a sitemap both based on static urls and an array of urls
	 */
	public function general()
	{

		$this->siteMapModel->add(base_url(), NULL, 'monthly', 1);
		$this->siteMapModel->add(base_url('contact'), NULL, 'monthly', 0.9);


		// CATEGORIES XML PARSER
		foreach ($this->categories as $categories) {
			$this->siteMapModel->add($categories['loc'], $categories['lastmod'], $categories['changefreq'], $categories['priority']);
		}


		// ARTICLES XML PARSER
		foreach ($this->articles as $article) {
			$this->siteMapModel->add($article['loc'], $article['lastmod'], $article['changefreq'], $article['priority']);
		}


		return $this->siteMapModel->output();
	}

	/**
	 * Generate a articles sitemap only on an array of urls
	 */
	public function articles($lang = 'en')
	{


		// ARTICLES LIST FOR XML GENERATION
		if ($lang == 'fr') {
			$qry = 'SELECT `id`, `category_ids`, `title_fr` AS `title`, `alias_fr` AS `alias`, `article_layout`, `description_fr` AS `description`, `image`, `media_images`, `media_image_main`, `created_date`, `updated_date` FROM `articles` WHERE `status` = 1'; // select data from db
		} else {
			$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `updated_date` FROM `articles` WHERE `status` = 1'; // select data from db
		}
		$articles = $this->db->query($qry)->getResultArray();

		$article_arr = array();
		// Array of some articles for demonstration purposes		
		foreach ($articles as $article) {
			$article_link = generateArticleLink($article['category_ids'], $article['alias']);
			if ($article['updated_date'] == '0000-00-00 00:00:00') {
				$article['updated_date'] = $article['created_date'];
			}
			$article_arr[] = array(
				'loc' => $article_link,
				'lastmod' => date('Y-m-d', strtotime($article['updated_date'])),
				'changefreq' => 'monthly',
				'priority' => 0.5
			);
		}

		foreach ($article_arr as $article) {
			$this->siteMapModel->add($article['loc'], $article['lastmod'], $article['changefreq'], $article['priority']);
		}
		return $this->siteMapModel->output();
	}

	/**
	 * Generate a categories sitemap only on an array of urls
	 */
	public function categories()
	{
		foreach ($this->categories as $category) {
			$this->siteMapModel->add($category['loc'], $category['lastmod'], $category['changefreq'], $category['priority']);
		}
		return $this->siteMapModel->output();
	}
}
