<?php

namespace App\Controllers;

use App\Libraries\Breadcrumbs;
use App\Libraries\IonAuth;
use App\Models\IonAuthModel;
use CodeIgniter\Database\Config;
use Config\Services;

class Articles extends CustomController
{

	public $ionAuthLibrary;
	public $breadcrumLibrary;
	public $ionAuthModal;
	public $db;
	function __construct()
	{
		parent::__construct();

		// $this->load->library('ion_auth');
		// $this->load->library('user_agent');

		// $this->load->library('Breadcrumbs');
		// $this->load->helper('articles');
		// $this->load->helper('tags');
		// $this->load->helper('categories');
		$this->ionAuthLibrary = new IonAuth();
		$this->breadcrumLibrary = new Breadcrumbs();
		$this->ionAuthModal = new IonAuthModel();
		$this->db = Config::connect();

		$siteConfiguration = siteConfiguration();

		if ($siteConfiguration['site_caching']) {
			// $this->output->cache($siteConfiguration['site_caching_time']);
			Services::response()->setCache([
				'max-age' => $siteConfiguration['site_caching_time'] * 60,
			]);
		}

		$this->clang = session()->get("lang");

		$this->data['current_user'] = $this->ionAuthModal->user()->getRow();
		$this->data['current_user_menu'] = '';
		$this->data['site_title'] = $siteConfiguration['site_title'];
		$this->data['site_description'] = $siteConfiguration['site_description'];
	}

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($maincategory_alias = NULL, $subcategory_alias = NULL, $article_alias = NULL)
	{

		$where = '1';
		// 5 REGISTERED GROUP USER ID
		$group = '5';
		if (!$this->ionAuthLibrary->logged_in()) {
			$where = 'article.status = 1 AND article.published_date <= "' . date('Y-m-d H:i:s') . '"';
		} else if ($this->ionAuthLibrary->logged_in() && $this->ionAuthLibrary->in_group($group)) {
			$where = 'article.status = 1 AND article.published_date <= "' . date('Y-m-d H:i:s') . '"';
		}

		if (is_null($article_alias) || empty($article_alias)) {

			if (empty($subcategory_alias)) {
				$subcategory_alias = $maincategory_alias;
			}

			//echo $subcategory_alias; exit;

			//$this->data['heading'] = 'Error';
			//$this->data['message'] = '<p>Invalid or wrong requested URL.</p>';
			//$this->render('errors/html/error_404');
			if ($this->clang == 'fr') {
				$qry = 'SELECT article.*, article.title_fr AS title, article.description_fr AS description, article.image_fr AS image, user.username AS `created_by_username`, user.first_name AS `created_by_first_name`, user.last_name AS `created_by_last_name` FROM `articles` AS article LEFT JOIN `users` AS user ON article.created_by  = user.id WHERE ' . $where . ' AND article.alias_fr = "' . rawurldecode($subcategory_alias) . '"'; // select data from db
			} else {
				$qry = 'SELECT article.*, user.username AS `created_by_username`, user.first_name AS `created_by_first_name`, user.last_name AS `created_by_last_name` FROM `articles` AS article LEFT JOIN `users` AS user ON article.created_by  = user.id WHERE ' . $where . ' AND article.alias = "' . rawurldecode($subcategory_alias) . '"'; // select data from db
			}
		} else {

			if ($this->clang == 'fr') {
				$qry = 'SELECT article.*, article.title_fr AS title, article.description_fr AS description, article.image_fr AS image, user.username AS `created_by_username`, user.first_name AS `created_by_first_name`, user.last_name AS `created_by_last_name` FROM `articles` AS article LEFT JOIN `users` AS user ON article.created_by  = user.id WHERE ' . $where . ' AND article.alias_fr = "' . rawurldecode($article_alias) . '"'; // select data from db
			} else {
				$qry = 'SELECT article.*, user.username AS `created_by_username`, user.first_name AS `created_by_first_name`, user.last_name AS `created_by_last_name` FROM `articles` AS article LEFT JOIN `users` AS user ON article.created_by  = user.id WHERE ' . $where . ' AND article.alias = "' . rawurldecode($article_alias) . '"'; // select data from db
			}
		}

		$article = $this->db->query($qry)->getResultArray();

		//echo "<pre>"; print_r($article); exit;

		if (!empty($article)) {

			$question_title = '';
			$question_result = '';
			if (isset($_REQUEST['article_id']) && $_REQUEST['article_id'] != '') {

				$questions_test_answer_data = json_decode($article[0]['questions_test_answer_data']);
				$questions_test_answer_titles = json_decode($article[0]['questions_test_answer_titles']);

				$answers_data = array();
				$answers_data['question_1'] = $this->request->getPost('question_1');
				$answers_data['question_2'] = $this->request->getPost('question_2');
				$answers_data['question_3'] = $this->request->getPost('question_3');
				$answers_data['question_4'] = $this->request->getPost('question_4');
				$answers_data['question_5'] = $this->request->getPost('question_5');
				$answers_data['question_6'] = $this->request->getPost('question_6');
				$answers_data['question_7'] = $this->request->getPost('question_7');

				$a = 0;
				$b = 0;
				$c = 0;

				foreach ($answers_data as $k) {
					if ($k == 'A') {
						$a = $a + 1;
					}
					if ($k == 'B') {
						$b = $b + 1;
					}
					if ($k == 'C') {
						$c = $c + 1;
					}
				}

				$a = (($a * 100) / 7);
				$b = (($b * 100) / 7);
				$c = (($c * 100) / 7);

				if ($a > 70) {
					$question_title = $questions_test_answer_titles->{1}->title;
					$question_result = $questions_test_answer_data->{1}->answer;
				} elseif ($b > 70) {
					$question_title = $questions_test_answer_titles->{2}->title;
					$question_result = $questions_test_answer_data->{2}->answer;
				} elseif ($c > 70) {
					$question_title = $questions_test_answer_titles->{3}->title;
					$question_result = $questions_test_answer_data->{3}->answer;
				} else {
					$question_title = $questions_test_answer_titles->{4}->title;
					$question_result = $questions_test_answer_data->{4}->answer;
				}
			}

			// INCREMENT +1 when article view
			increaseArticlesViews($article[0]['id']);
			
			$maincategory_details = getCategoryDetailsByAlias($maincategory_alias);
			if ($subcategory_alias != '') {
				$subcategory_details = getCategoryDetailsByAlias($subcategory_alias);
			} else {
				$subcategory_details = '';
			}
			
			// BREADCRUMBS
			$this->breadcrumLibrary->add('الصفحة الرئيسية', base_url());
			if (is_null($article_alias) || empty($article_alias)) {
				$this->breadcrumLibrary->add($maincategory_details['title'] ?? "", base_url('الفئة/' . $maincategory_alias));
				if ($subcategory_details != '') {
					$this->breadcrumLibrary->add($subcategory_details['title'] ?? "", base_url('الفئة/' . $subcategory_alias . '/' . $maincategory_alias));
				}
			} else {
				if ($subcategory_details != '') {
					$this->breadcrumLibrary->add($subcategory_details['title'] ?? "", base_url('الفئة/' . $subcategory_alias));
				}
				$this->breadcrumLibrary->add($maincategory_details['title'] ?? "", base_url('الفئة/' . $subcategory_alias . '/' . $maincategory_alias));
				$this->breadcrumLibrary->add($article[0]['title'] ?? "", base_url('الفئة/' . $subcategory_alias . '/' . $maincategory_alias . '/' . $article_alias));
			}

			if ($this->ionAuthLibrary->logged_in()) {
				$current_logged_id = $this->ionAuthLibrary->get_user_id();
				$checkmyfavoritelist = $this->Checkmyfavoritelist($current_logged_id, $article[0]['id']);
			} else {
				$checkmyfavoritelist = 0;
			}

			$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();
			$this->data['article'] = $article[0];
			$this->data['ismyfavarticle'] = $checkmyfavoritelist;
			$this->data['maincategory_details'] = $maincategory_details;
			$this->data['subcategory_details'] = $subcategory_details;
			$this->data['question_title'] = $question_title;
			$this->data['question_result'] = $question_result;

			// RELATED ARTICLES BASED ON CURRENT ARTICLE CATEGORY
			$categories = explode(',', $article[0]['category_ids']);
			$related_articles = getRelatedArticles($categories, $article[0]['id'], $orderby = 'rand', $ordering = 'DESC', $limit_start = '0', $limit_end = '3');
			$this->data['related_articles'] = $related_articles;

			// ARTICLE TAGS
			$article_tags_array = explode(',', $article[0]['tag_ids']);
			$article_tags = getTagDetailsByID($article_tags_array, $orderby = 'title', $ordering = 'ASC', $limit_start = '0', $limit_end = '*');
			$this->data['article_tags'] = $article_tags;

			// LATEST VIDEOS FROM MANGOMOLO CATEGORY
			//$category_details = getCategoryDetailsByAlias($maincategory_alias);
			//$mangoLatestVideos = $this->mangomolo->GetMangomoloLatestVideosListByCatID($category_details['mangomolo_category_id'], 1, 4);
			$mangoLatestVideos = '';
			$this->data['latest_videos'] = $mangoLatestVideos;

			// MOSTREAD ARTICLES FROM ARTICLE HELPER
			$mostread_articles = getMostreadArticles('', 'hits', 'DESC', 0, 3);
			$this->data['mostread_articles'] = $mostread_articles;

			if ($article[0]['meta_title'] == '') {
				$page_title = $this->data['page_title'] . ' : ' . $article[0]['title'];
			} else {
				if ($this->clang == 'fr') {
					$page_title = strip_tags($article[0]['meta_title_fr']);
				} else {
					$page_title = strip_tags($article[0]['meta_title']);
				}
			}

			if ($this->clang == 'fr') {
				$page_description = $article[0]['meta_description_fr'];
			} else {
				$page_description = $article[0]['meta_description'];
			}

			$page_title = str_replace('"', '`', $page_title);
			$page_title = str_replace("'", "'", $page_title);

			$page_description = str_replace('"', '`', $page_description);
			$page_description = str_replace("'", "'", $page_description);

			$this->data['page_title'] = $page_title;
			$this->data['page_description'] = $page_description;




			/********************************************/
			//              ADS BLOCKS
			/********************************************/


			if ($this->request->getUserAgent()->isMobile()) {

				$this->data['before_head'] = "";

				$this->data['billboard'] = "";
				$this->data['mpu'] = "";
				$this->data['outstream'] = "";
				$this->data['leaderboard'] = "";
			} else {

				$this->data['before_head'] = "";

				$this->data['billboard'] = "";
				$this->data['mpu'] = "";
				$this->data['outstream'] = "";
				$this->data['leaderboard'] = "";
			}



			$this->data['fb_og_type'] = 'Article';
			$this->data['fb_og_url'] = generateArticleLink($article[0]['category_ids'], $article[0]['alias']);
			if ($article[0]['image'] == '') {
				$fb_og_image = base_url() . 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$fb_og_image = base_url() . 'assets/media/' . $article[0]['image'];
			}
			if ($article[0]['article_layout'] == 'text_mulitplemedia' || $article[0]['article_layout'] == 'text_mulitplemedia_6' || $article[0]['article_layout'] == 'fashion') {
				$media_images = json_decode($article[0]['media_images']);
				$fb_og_image = base_url() . 'assets/media/' . $media_images[$article[0]['media_image_main']];
			}
			$this->data['fb_og_image'] = $fb_og_image;

			$article_title = str_replace('"', '`', $article[0]['title']);
			$article_title = str_replace("'", "'", $article_title);
			$this->data['fb_og_title'] = $article_title;
			$this->data['fb_og_description'] = $page_description;
			$this->data['fb_og_published_time'] = date('m/d/Y H:i:s A', strtotime($article[0]['published_date']));

			//echo "<pre>"; print_r($this->data); exit;

			$this->render('articles/index_view');
		} else {

			$this->data['heading'] = 'Notice';
			$this->data['message'] = '<p>There\'s no article.</p>';

			$this->data['fb_og_type'] = 'Website';
			$this->data['fb_og_url'] = base_url();
			$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
			$this->data['fb_og_title'] = $this->data['site_title'] . ' - 404';
			$this->data['fb_og_description'] = 'Page not found.';
			$this->data['fb_og_published_time'] = '';

			$this->render('errors/html/error_404');
		}
	}


	// CATEGORY ARTICLES LIST PAGE
	public function articles($category_alias, $subcategory_alias)
	{

		$current_user = $this->ionAuthModal->user()->getRow();

		if ($subcategory_alias == '') {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', '404 - Page not found.');
			$this->render('/');
		}

		$category_details = getCategoryDetailsByAlias($subcategory_alias);
		if (!empty($category_details)) {

			$where = ' WHERE 1';
			$where .= ' AND `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND FIND_IN_SET(' . $category_details['id'] . ', `category_ids`)';
			$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM articles' . $where . ' ORDER BY `published_date` DESC LIMIT 0, 6'; // select data from db

			$category_articles = $this->db->query($qry)->getResultArray();
		} else {

			$category_articles = '';
		}

		$this->data['category_alias'] = $category_alias;
		$this->data['subcategory_alias'] = $subcategory_alias;
		$this->data['category'] = $category_details;
		$this->data['category_articles'] = $category_articles;
		$this->data['current_user'] = $current_user;

		if ($category_details['meta_title'] == '') {
			$page_title = $this->data['page_title'] . ' : ' . $category_details['title'];
		} else {
			$page_title = $category_details['meta_title'];
		}

		$page_description = $category_details['meta_description'];

		$page_title = str_replace('"', '`', $page_title);
		$page_title = str_replace("'", "'", $page_title);

		$page_description = str_replace('"', '`', $page_description);
		$page_description = str_replace("'", "'", $page_description);

		$this->data['page_title'] = $page_title;
		$this->data['page_description'] = $page_description;




		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
		} else {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
		}



		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = generateCategoryLink($category_details['id']);
		if ($category_details['image'] == '') {
			$category_details['image'] = 'assets/site/images/yawmiyati-default.jpg';
		}
		$this->data['fb_og_image'] = base_url() . $category_details['image'];
		$this->data['fb_og_title'] = $page_title;
		$this->data['fb_og_description'] = $page_description;
		$this->data['fb_og_published_time'] = '';

		$this->render('articles/articles_view');
	}

	// CATEGORY ARTICLES LIST PAGE AJAX
	public function articlesAjax()
	{

		$category_alias = $this->request->getPost("category_alias");
		$subcategory_alias = $this->request->getPost("subcategory_alias");
		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");

		$category_details = getCategoryDetailsByAlias($subcategory_alias);
		$where = ' WHERE 1';
		$where .= ' AND `status` = "1" AND `published_date` <= "' . date('Y-m-d H:i:s') . '" AND FIND_IN_SET(' . $category_details['id'] . ', `category_ids`)';
		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM articles' . $where . ' ORDER BY `published_date` DESC LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db

		$category_articles = $this->db->query($qry)->getResultArray();

		$data_html = '';
		foreach ($category_articles as $category_article) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($category_article['category_ids'], $category_article['alias']);
			$category_ids = explode(',', $category_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if ($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}

			if ($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			}

			$color_style = $category_details['color_style'];
			if ($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}

			$arabic_week_days = array(
				'Monday' => 'الإثنين',
				'Tuesday' => 'الثلاثاء',
				'Wednesday' => 'الأربعاء',
				'Thursday' => 'الخميس',
				'Friday' => 'الجمعه',
				'Saturday' => 'السبت',
				'Sunday' => 'الأحد'
			);
			$arabic_months = array(
				'January' => 'كانون الثاني',
				'February' => 'شباط',
				'March' => 'آذار',
				'April' => 'نيسان',
				'May' => 'أيار',
				'June' => 'حزيران',
				'July' => 'تموز',
				'August' => 'آب',
				'September' => 'أيلول',
				'October' => 'تشرين الأول',
				'November' => 'تشرين الثاني',
				'December' => 'كانون الأول'
			);
			$day = date('l', strtotime($category_article['published_date']));
			$month = date('F', strtotime($category_article['published_date']));
			$date = date('d', strtotime($category_article['published_date']));
			$year = date('Y', strtotime($category_article['published_date']));

			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
			$data_html .= '<div class="content-block">';
			$data_html .= '<div class="content-category" style="border-bottom: 1px solid ' . $category_details['color_style'] . ';">';
			$data_html .= '<span style="background: ' . $category_details['color_style'] . ';">' . $category_details['title'] . '</span>';
			$data_html .= '</div>';
			$data_html .= '<div class="img-div">';
			$data_html .= '<a href="' . $article_link . '" title="' . $category_article['title'] . '">';

			if ($category_article['image'] == '') {
				$category_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$category_article['image'] = 'assets/media/' . $category_article['image'];
			}
			if ($category_article['article_layout'] == 'text_mulitplemedia' || $category_article['article_layout'] == 'text_mulitplemedia_6' || $category_article['article_layout'] == 'fashion') {
				$media_images = json_decode($category_article['media_images']);
				if ($media_images[$category_article['media_image_main']] == '') {
					$category_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
				} else {
					$category_article['image'] = 'assets/media/' . $media_images[$category_article['media_image_main']];
				}
			}

			$file_FCPath = FCPATH . urldecode($category_article['image']);
			if (!file_exists($file_FCPath)) {
				$category_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			}

			$data_html .= '<img title="' . $category_article['title'] . '" alt="' . $category_article['title'] . '" src="' . base_url() . 'assets/site/images/yawmiyati-default.jpg" data-src="' . base_url() . $category_article['image'] . '" class="lazyload img-responsive center-block"/>';
			$data_html .= '</a>';
			$data_html .= '</div>';
			$data_html .= '<div class="content-div">';
			$data_html .= '<h4><a href="' . $article_link . '" title="' . $category_article['title'] . '">' . $category_article['title'] . '</a></h4>';
			if ($category_article['description'] != '' || $category_article['article_layout'] != 'media') {

				$description = strip_tags($category_article['description']);
				$description_string_count = mb_strlen($description);
				if ($description_string_count > 160) {
					$description = mb_substr($description, 0, 157) . '...';
				}

				$data_html .= '<p>' . $description . '</p>';
			}

			$data_html .= '<time>' . $arabic_week_days[$day] . ', ' . $date . ' ' . $arabic_months[$month] . ' ' . $year . '</time>';
			$data_html .= '</div>';
			$data_html .= '</div>';
			$data_html .= '</div>';
		}


		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['limit_start'] = $limit_start + $limit_end;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}


	// FEATURED ARTICLES LIST PAGE
	public function featuredarticles()
	{

		$current_user = $this->ionAuthModal->user()->getRow();

		// FEATURED ARTICLES FROM ARTICLE HELPER
		$featured_articles = getFeaturedArticles('', 'id', 'DESC', '0', '6');


		$this->data['featured_articles'] = $featured_articles;
		$this->data['current_user'] = $current_user;

		$this->data['page_title'] = $this->data['page_title'] . ' : مقالات مميزة';





		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_MPU', [300, 250], 'div-gpt-ad-1542802043237-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Mobile_MPU -->
			<div id='div-gpt-ad-1542802043237-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802043237-0'); });
			</script>
			</div>";
		} else {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_MPU_TOP', [300, 250], 'div-gpt-ad-1542803483809-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Desk_MPU_TOP -->
			<div id='div-gpt-ad-1542803483809-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542803483809-0'); });
			</script>
			</div>";
		}


		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url('/مقالات-مميزة');
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : مقالات مميزة';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->render('articles/featuredarticles_view');
	}
	// AUTHOR ARTICLES LIST PAGE AJAX
	public function featuredarticlesAjax()
	{

		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");

		// AUTHOR ARTICLES FROM ARTICLE HELPER
		$featured_articles = getFeaturedArticles('', 'id', 'DESC', $limit_start, $limit_end);

		$data_html = '';
		foreach ($featured_articles as $featured_article) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($featured_article['category_ids'], $featured_article['alias']);
			$category_ids = explode(',', $featured_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if ($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}

			if ($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			}

			$color_style = $category_details['color_style'];
			if ($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}

			$arabic_week_days = array(
				'Monday' => 'الإثنين',
				'Tuesday' => 'الثلاثاء',
				'Wednesday' => 'الأربعاء',
				'Thursday' => 'الخميس',
				'Friday' => 'الجمعه',
				'Saturday' => 'السبت',
				'Sunday' => 'الأحد'
			);
			$arabic_months = array(
				'January' => 'كانون الثاني',
				'February' => 'شباط',
				'March' => 'آذار',
				'April' => 'نيسان',
				'May' => 'أيار',
				'June' => 'حزيران',
				'July' => 'تموز',
				'August' => 'آب',
				'September' => 'أيلول',
				'October' => 'تشرين الأول',
				'November' => 'تشرين الثاني',
				'December' => 'كانون الأول'
			);
			$day = date('l', strtotime($featured_article['published_date']));
			$month = date('F', strtotime($featured_article['published_date']));
			$date = date('d', strtotime($featured_article['published_date']));
			$year = date('Y', strtotime($featured_article['published_date']));

			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
			$data_html .= '<div class="content-block">';
			$data_html .= '<div class="content-category" style="border-bottom: 1px solid ' . $category_details['color_style'] . ';">';
			$data_html .= '<span style="background: ' . $category_details['color_style'] . ';">' . $category_details['title'] . '</span>';
			$data_html .= '</div>';
			$data_html .= '<div class="img-div">';
			$data_html .= '<a href="' . $article_link . '" title="' . $featured_article['title'] . '">';

			if ($featured_article['image'] == '') {
				$featured_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$featured_article['image'] = 'assets/media/' . $featured_article['image'];
			}
			if ($featured_article['article_layout'] == 'text_mulitplemedia' || $featured_article['article_layout'] == 'text_mulitplemedia_6' || $featured_article['article_layout'] == 'fashion') {
				$media_images = json_decode($featured_article['media_images']);
				if ($media_images[$featured_article['media_image_main']] == '') {
					$featured_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
				} else {
					$featured_article['image'] = 'assets/media/' . $media_images[$featured_article['media_image_main']];
				}
			}

			$file_FCPath = FCPATH . urldecode($featured_article['image']);
			if (!file_exists($file_FCPath)) {
				$featured_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			}

			$data_html .= '<img title="' . $featured_article['title'] . '" alt="' . $featured_article['title'] . '" src="' . base_url() . 'assets/site/images/yawmiyati-default.jpg" data-src="' . base_url() . $featured_article['image'] . '" class="lazyload img-responsive center-block"/>';
			$data_html .= '</a>';
			$data_html .= '</div>';
			$data_html .= '<div class="content-div">';
			$data_html .= '<h4><a href="' . $article_link . '" title="' . $featured_article['title'] . '">' . $featured_article['title'] . '</a></h4>';
			if ($featured_article['description'] != '' || $featured_article['article_layout'] != 'media') {

				$description = strip_tags($featured_article['description']);
				$description_string_count = mb_strlen($description);
				if ($description_string_count > 160) {
					$description = mb_substr($description, 0, 157) . '...';
				}

				$data_html .= '<p>' . $description . '</p>';
			}

			$data_html .= '<time>' . $arabic_week_days[$day] . ', ' . $date . ' ' . $arabic_months[$month] . ' ' . $year . '</time>';
			$data_html .= '</div>';
			$data_html .= '</div>';
			$data_html .= '</div>';
		}


		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['limit_start'] = $limit_start + $limit_end;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}


	// MOST READ ARTICLES LIST PAGE
	public function mostreadarticles()
	{

		$current_user = $this->ionAuthModal->user()->getRow();

		// MOST READ ARTICLES FROM ARTICLE HELPER
		$mostread_articles = getMostreadArticles('', 'hits', 'DESC', '0', 6);


		$this->data['mostread_articles'] = $mostread_articles;
		$this->data['current_user'] = $current_user;

		$this->data['page_title'] = $this->data['page_title'] . ' : أكثر المقالات قراءة';




		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_MPU', [300, 250], 'div-gpt-ad-1542802043237-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Mobile_MPU -->
			<div id='div-gpt-ad-1542802043237-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802043237-0'); });
			</script>
			</div>";
		} else {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_MPU_TOP', [300, 250], 'div-gpt-ad-1542803483809-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Desk_MPU_TOP -->
			<div id='div-gpt-ad-1542803483809-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542803483809-0'); });
			</script>
			</div>";
		}



		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url('أكثر-المقالات-قراءة/');
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : أكثر المقالات قراءة';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->render('articles/mostreadarticles_view');
	}
	// MOST READ ARTICLES LIST PAGE AJAX
	public function mostreadarticlesAjax()
	{

		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");

		// AUTHOR ARTICLES FROM ARTICLE HELPER
		$mostread_articles = getMostreadArticles('', 'id', 'DESC', $limit_start, $limit_end);

		$data_html = '';
		foreach ($mostread_articles as $mostread_article) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($mostread_article['category_ids'], $mostread_article['alias']);
			$category_ids = explode(',', $mostread_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if ($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}

			if ($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			}

			$color_style = $category_details['color_style'];
			if ($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}

			$arabic_week_days = array(
				'Monday' => 'الإثنين',
				'Tuesday' => 'الثلاثاء',
				'Wednesday' => 'الأربعاء',
				'Thursday' => 'الخميس',
				'Friday' => 'الجمعه',
				'Saturday' => 'السبت',
				'Sunday' => 'الأحد'
			);
			$arabic_months = array(
				'January' => 'كانون الثاني',
				'February' => 'شباط',
				'March' => 'آذار',
				'April' => 'نيسان',
				'May' => 'أيار',
				'June' => 'حزيران',
				'July' => 'تموز',
				'August' => 'آب',
				'September' => 'أيلول',
				'October' => 'تشرين الأول',
				'November' => 'تشرين الثاني',
				'December' => 'كانون الأول'
			);
			$day = date('l', strtotime($mostread_article['published_date']));
			$month = date('F', strtotime($mostread_article['published_date']));
			$date = date('d', strtotime($mostread_article['published_date']));
			$year = date('Y', strtotime($mostread_article['published_date']));

			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
			$data_html .= '<div class="content-block">';
			$data_html .= '<div class="content-category" style="border-bottom: 1px solid ' . $category_details['color_style'] . ';">';
			$data_html .= '<span style="background: ' . $category_details['color_style'] . ';">' . $category_details['title'] . '</span>';
			$data_html .= '</div>';
			$data_html .= '<div class="img-div">';
			$data_html .= '<a href="' . $article_link . '" title="' . $mostread_article['title'] . '">';

			if ($mostread_article['image'] == '') {
				$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$mostread_article['image'] = 'assets/media/' . $mostread_article['image'];
			}
			if ($mostread_article['article_layout'] == 'text_mulitplemedia' || $mostread_article['article_layout'] == 'text_mulitplemedia_6' || $mostread_article['article_layout'] == 'fashion') {
				$media_images = json_decode($mostread_article['media_images']);
				if ($media_images[$mostread_article['media_image_main']] == '') {
					$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
				} else {
					$mostread_article['image'] = 'assets/media/' . $media_images[$mostread_article['media_image_main']];
				}
			}

			$file_FCPath = FCPATH . urldecode($mostread_article['image']);
			if (!file_exists($file_FCPath)) {
				$mostread_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			}

			$data_html .= '<img title="' . $mostread_article['title'] . '" alt="' . $mostread_article['title'] . '" src="' . base_url() . 'assets/site/images/yawmiyati-default.jpg" data-src="' . base_url() . $mostread_article['image'] . '" class="lazyload img-responsive center-block"/>';
			$data_html .= '</a>';
			$data_html .= '</div>';
			$data_html .= '<div class="content-div">';
			$data_html .= '<h4><a href="' . $article_link . '" title="' . $mostread_article['title'] . '">' . $mostread_article['title'] . '</a></h4>';
			if ($mostread_article['description'] != '' || $mostread_article['article_layout'] != 'media') {

				$description = strip_tags($mostread_article['description']);
				$description_string_count = mb_strlen($description);
				if ($description_string_count > 163) {
					$description = mb_substr($description, 0, 160) . '...';
				}

				$data_html .= '<p>' . $description . '</p>';
			}

			$data_html .= '<time>' . $arabic_week_days[$day] . ', ' . $date . ' ' . $arabic_months[$month] . ' ' . $year . '</time>';
			$data_html .= '</div>';
			$data_html .= '</div>';
			$data_html .= '</div>';
		}


		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['limit_start'] = $limit_start + $limit_end;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}


	// AUTHOR ARTICLES LIST PAGE
	public function authorarticles($author)
	{

		$current_user = $this->ionAuthModal->user()->getRow();

		// AUTHOR DETAILS FROM GLOBAL HELPER
		$author_details = GetUserDetailsByUsername($author);

		// AUTHOR ARTICLES FROM ARTICLE HELPER
		$author_articles = getAuthorArticles($author, 'id', 'DESC', '0', '6');

		// BREADCRUMBS
		$this->breadcrumLibrary->add('الصفحة الرئيسية', base_url());
		$this->breadcrumLibrary->add($author, base_url('مقالات-المؤلف/' . $author));

		$this->data['breadcrumbs'] = $this->breadcrumLibrary->render();


		$mangoLatestVideos = '';
		$this->data['latest_videos'] = $mangoLatestVideos;

		$this->data['author_articles'] = $author_articles;
		$this->data['current_user'] = $current_user;
		$this->data['author_details'] = $author_details;
		$this->data['author'] = $author;

		$this->data['page_title'] = $this->data['page_title'] . ' : جميع مقالات الكاتب';





		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_MPU', [300, 250], 'div-gpt-ad-1542802043237-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Mobile_MPU -->
			<div id='div-gpt-ad-1542802043237-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802043237-0'); });
			</script>
			</div>";
		} else {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_MPU_TOP', [300, 250], 'div-gpt-ad-1542803483809-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Desk_MPU_TOP -->
			<div id='div-gpt-ad-1542803483809-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542803483809-0'); });
			</script>
			</div>";
		}




		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url('مقالات-المؤلف/' . $author_details['username']);
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : جميع مقالات الكاتب';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->render('articles/authorarticles_view');
	}

	// AUTHOR ARTICLES LIST PAGE AJAX
	public function authorarticlesAjax()
	{

		$author = $this->request->getPost("author");
		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");

		// AUTHOR ARTICLES FROM ARTICLE HELPER
		$author_articles = getAuthorArticles($author, 'id', 'DESC', $limit_start, $limit_end);

		$data_html = '';
		foreach ($author_articles as $author_article) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($author_article['category_ids'], $author_article['alias']);
			$category_ids = explode(',', $author_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if ($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}

			if ($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			}

			$color_style = $category_details['color_style'];
			if ($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}

			$arabic_week_days = array(
				'Monday' => 'الإثنين',
				'Tuesday' => 'الثلاثاء',
				'Wednesday' => 'الأربعاء',
				'Thursday' => 'الخميس',
				'Friday' => 'الجمعه',
				'Saturday' => 'السبت',
				'Sunday' => 'الأحد'
			);
			$arabic_months = array(
				'January' => 'كانون الثاني',
				'February' => 'شباط',
				'March' => 'آذار',
				'April' => 'نيسان',
				'May' => 'أيار',
				'June' => 'حزيران',
				'July' => 'تموز',
				'August' => 'آب',
				'September' => 'أيلول',
				'October' => 'تشرين الأول',
				'November' => 'تشرين الثاني',
				'December' => 'كانون الأول'
			);
			$day = date('l', strtotime($author_article['published_date']));
			$month = date('F', strtotime($author_article['published_date']));
			$date = date('d', strtotime($author_article['published_date']));
			$year = date('Y', strtotime($author_article['published_date']));

			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
			$data_html .= '<div class="content-block">';
			$data_html .= '<div class="content-category" style="border-bottom: 1px solid ' . $category_details['color_style'] . ';">';
			$data_html .= '<span style="background: ' . $category_details['color_style'] . ';">' . $category_details['title'] . '</span>';
			$data_html .= '</div>';
			$data_html .= '<div class="img-div">';
			$data_html .= '<a href="' . $article_link . '" title="' . $author_article['title'] . '">';

			if ($author_article['image'] == '') {
				$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$author_article['image'] = 'assets/media/' . $author_article['image'];
			}
			if ($author_article['article_layout'] == 'text_mulitplemedia' || $author_article['article_layout'] == 'text_mulitplemedia_6' || $author_article['article_layout'] == 'fashion') {
				$media_images = json_decode($author_article['media_images']);
				if ($media_images[$author_article['media_image_main']] == '') {
					$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
				} else {
					$author_article['image'] = 'assets/media/' . $media_images[$author_article['media_image_main']];
				}
			}

			$file_FCPath = FCPATH . urldecode($author_article['image']);
			if (!file_exists($file_FCPath)) {
				$author_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			}

			$data_html .= '<img title="' . $author_article['title'] . '" alt="' . $author_article['title'] . '" src="' . base_url() . 'assets/site/images/yawmiyati-default.jpg" data-src="' . base_url() . $author_article['image'] . '" class="lazyload img-responsive center-block"/>';
			$data_html .= '</a>';
			$data_html .= '</div>';
			$data_html .= '<div class="content-div">';
			$data_html .= '<h4><a href="' . $article_link . '" title="' . $author_article['title'] . '">' . $author_article['title'] . '</a></h4>';
			if ($author_article['description'] != '' || $author_article['article_layout'] != 'media') {

				$description = strip_tags($author_article['description']);
				$description_string_count = mb_strlen($description);
				if ($description_string_count > 155) {
					$description = mb_substr($description, 0, 150) . '...';
				}

				$data_html .= '<p>' . $description . '</p>';
			}

			$data_html .= '<time>' . $arabic_week_days[$day] . ', ' . $date . ' ' . $arabic_months[$month] . ' ' . $year . '</time>';
			$data_html .= '</div>';
			$data_html .= '</div>';
			$data_html .= '</div>';
		}


		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['author'] = $author;
		$response['limit_start'] = $limit_start + $limit_end;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}



	// LATEST VIDEOS LIST PAGE AJAX
	public function latestvideosAjax()
	{

		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");


		$mangoLatestVideos = '';

		$data_html = '';

		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['limit_start'] = $limit_start + 4;
		$response['limit_end'] = $response['limit_start'] + 4;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}



	// ADD TO MY LIST
	public function addtomyfavoritelist($article_id)
	{

		$current_logged_id = $this->ionAuthLibrary->get_user_id();
		$created_date = date('Y-m-d H:i:s');
		$checkmyfavoritelist = $this->Checkmyfavoritelist($current_logged_id, $article_id);

		if ($checkmyfavoritelist == 0) {
			$sql = "Insert into users_favoritearticlelist (`user_id`, `article_id`, `created_date`) VALUES ('" . addslashes($current_logged_id) . "', '" . addslashes($article_id) . "', '" . addslashes($created_date) . "')";

			$val = $this->db->query($sql);
			if ($val) {
				$message_type = 'success';
				$message = 'Article successfully added in your favorite list.';
			} else {
				$message_type = 'danger';
				$message = 'Error! When Article adding in your favorite list.';
			}
		} else {
			$message_type = 'warning';
			$message = 'This Article is already in your favorite list.';
		}

		session()->setFlashdata('message_type', $message_type);
		session()->setFlashdata('message', $message);

		redirect(urldecode(Services::request()->getServer('HTTP_REFERER')));
	}


	// REMOVE FROM MY LIST
	public function removefrommyfavoritelist($article_id)
	{

		$current_logged_id = $this->ionAuthLibrary->get_user_id();
		$created_date = date('Y-m-d H:i:s');

		$sql = "DELETE FROM users_favoritearticlelist WHERE `user_id` = '" . $current_logged_id . "' AND `article_id` = '" . $article_id . "'";

		$val = $this->db->query($sql);
		if ($val) {
			$message_type = 'success';
			$message = 'Article successfully removed from your favorite list.';
		} else {
			$message_type = 'danger';
			$message = 'Error! When Article removing from your favorite list.';
		}

		session()->setFlashdata('message_type', $message_type);
		session()->setFlashdata('message', $message);

		redirect(urldecode(Services::request()->getServer('HTTP_REFERER')));
	}


	// ADD TO MY LIST
	public function Checkmyfavoritelist($current_logged_id, $article_id)
	{
		if ($current_logged_id == '') {
			$current_logged_id = 0;
		}
		$qry = 'SELECT `id` FROM `users_favoritearticlelist` WHERE `user_id` = ' . $current_logged_id . ' AND `article_id` = ' . $article_id; // select data from db
		$results = $this->db->query($qry)->getResultArray();

		return count($results);
	}

	// FAVORITE ARTICLES LIST PAGE
	public function favoritearticlelist()
	{

		$current_user = $this->ionAuthModal->user()->getRow();
		$current_logged_id = $this->ionAuthLibrary->get_user_id();

		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM `articles` WHERE `id` IN (SELECT `article_id` FROM `users_favoritearticlelist` WHERE `user_id` = ' . $current_logged_id . ') AND `published_date` <= "' . date('Y-m-d H:i:s') . '" LIMIT 0, 6'; // select data from db
		$favorite_articles = $this->db->query($qry)->getResultArray();

		$this->data['favorite_articles'] = $favorite_articles;
		$this->data['current_user'] = $current_user;

		$this->data['page_title'] = $this->data['page_title'] . ' : مقالاتي المفضلة';








		/********************************************/
		//              ADS BLOCKS
		/********************************************/


		if ($this->request->getUserAgent()->isMobile()) {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_Billboard', [[320, 100], [320, 120]], 'div-gpt-ad-1542802129681-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Mobile_MPU', [300, 250], 'div-gpt-ad-1542802043237-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Mobile_Billboard -->
			<div id='div-gpt-ad-1542802129681-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802129681-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Mobile_MPU -->
			<div id='div-gpt-ad-1542802043237-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542802043237-0'); });
			</script>
			</div>";
		} else {

			$this->data['before_head'] = "<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_Billbaord_TOP', [[970, 250], [728, 90]], 'div-gpt-ad-1543493299817-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>
			<script>
			  googletag.cmd.push(function() {
			    googletag.defineSlot('/23636148/Yawmiyati_Desk_MPU_TOP', [300, 250], 'div-gpt-ad-1542803483809-0').addService(googletag.pubads());
			    googletag.pubads().enableSingleRequest();
			    googletag.pubads().collapseEmptyDivs();
			    googletag.enableServices();
			  });
			</script>";

			$this->data['billboard'] = "<!-- /23636148/Yawmiyati_Desk_Billbaord_TOP -->
			<div id='div-gpt-ad-1543493299817-0'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1543493299817-0'); });
			</script>
			</div>";
			$this->data['mpu'] = "<!-- /23636148/Yawmiyati_Desk_MPU_TOP -->
			<div id='div-gpt-ad-1542803483809-0' style='height:250px; width:300px; margin: 0 auto;'>
			<script>
			googletag.cmd.push(function() { googletag.display('div-gpt-ad-1542803483809-0'); });
			</script>
			</div>";
		}






		$this->data['fb_og_type'] = 'Website';
		$this->data['fb_og_url'] = base_url();
		$this->data['fb_og_image'] = base_url() . 'assets/site/images/yawmiyati-default.jpg';
		$this->data['fb_og_title'] = $this->data['page_title'] . ' : مقالاتي المفضلة';
		$this->data['fb_og_description'] = $this->data['site_description'];
		$this->data['fb_og_published_time'] = '';

		$this->render('articles/favoritearticles_view');
	}

	// FAVORITE ARTICLES LIST PAGE AJAX
	public function favoritearticlelistAjax()
	{

		$current_logged_id = $this->ionAuthLibrary->get_user_id();
		$limit_start = $this->request->getPost("limit_start");
		$limit_end = $this->request->getPost("limit_end");

		// AUTHOR ARTICLES FROM ARTICLE HELPER
		$qry = 'SELECT `id`, `category_ids`, `title`, `alias`, `article_layout`, `description`, `image`, `media_images`, `media_image_main`, `created_date`, `published_date` FROM `articles` WHERE `id` IN (SELECT `article_id` FROM `users_favoritearticlelist` WHERE `user_id` = ' . $current_logged_id . ') AND `published_date` <= "' . date('Y-m-d H:i:s') . '" LIMIT ' . $limit_start . ', ' . $limit_end; // select data from db
		$favorite_articles = $this->db->query($qry)->getResultArray();

		$data_html = '';
		foreach ($favorite_articles as $favorite_article) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_link = generateArticleLink($favorite_article['category_ids'], $favorite_article['alias']);
			$category_ids = explode(',', $favorite_article['category_ids']);
			$category_idsCnt = count($category_ids);
			if ($category_idsCnt == 1) {
				$category_details = getCategoryDetails($category_ids[0]);
			} else {
				$category_details = getCategoryDetails($category_ids[1]);
			}

			if ($category_details['parent_id'] != 0) {
				$parent_category_details = getParentCategoryDetails($category_details['id']);
			}

			$color_style = $category_details['color_style'];
			if ($color_style == '') {
				$category_details['color_style'] = $parent_category_details['color_style'];
			}

			$arabic_week_days = array(
				'Monday' => 'الإثنين',
				'Tuesday' => 'الثلاثاء',
				'Wednesday' => 'الأربعاء',
				'Thursday' => 'الخميس',
				'Friday' => 'الجمعه',
				'Saturday' => 'السبت',
				'Sunday' => 'الأحد'
			);
			$arabic_months = array(
				'January' => 'كانون الثاني',
				'February' => 'شباط',
				'March' => 'آذار',
				'April' => 'نيسان',
				'May' => 'أيار',
				'June' => 'حزيران',
				'July' => 'تموز',
				'August' => 'آب',
				'September' => 'أيلول',
				'October' => 'تشرين الأول',
				'November' => 'تشرين الثاني',
				'December' => 'كانون الأول'
			);
			$day = date('l', strtotime($favorite_article['published_date']));
			$month = date('F', strtotime($favorite_article['published_date']));
			$date = date('d', strtotime($favorite_article['published_date']));
			$year = date('Y', strtotime($favorite_article['published_date']));

			$data_html .= '<div class="col-md-4 content-block-col" data-mh="heightConsistancy">';
			$data_html .= '<div class="content-block">';
			$data_html .= '<div class="content-category" style="border-bottom: 1px solid ' . $category_details['color_style'] . ';">';
			$data_html .= '<span style="background: ' . $category_details['color_style'] . ';">' . $category_details['title'] . '</span>';
			$data_html .= '</div>';
			$data_html .= '<div class="img-div">';
			$data_html .= '<a href="' . $article_link . '" title="' . $favorite_article['title'] . '">';

			if ($favorite_article['image'] == '') {
				$favorite_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			} else {
				$favorite_article['image'] = 'assets/media/' . $favorite_article['image'];
			}
			if ($favorite_article['article_layout'] == 'text_mulitplemedia' || $favorite_article['article_layout'] == 'text_mulitplemedia_6' || $favorite_article['article_layout'] == 'fashion') {
				$media_images = json_decode($favorite_article['media_images']);
				if ($media_images[$favorite_article['media_image_main']] == '') {
					$favorite_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
				} else {
					$favorite_article['image'] = 'assets/media/' . $media_images[$favorite_article['media_image_main']];
				}
			}

			$file_FCPath = FCPATH . urldecode($favorite_article['image']);
			if (!file_exists($file_FCPath)) {
				$favorite_article['image'] = 'assets/site/images/yawmiyati-default.jpg';
			}

			$data_html .= '<img title="' . $favorite_article['title'] . '" alt="' . $favorite_article['title'] . '" src="' . base_url() . 'assets/site/images/yawmiyati-default.jpg" data-src="' . base_url() . $favorite_article['image'] . '" class="lazyload img-responsive center-block"/>';
			$data_html .= '</a>';
			$data_html .= '</div>';
			$data_html .= '<div class="content-div">';
			$data_html .= '<h4><a href="' . $article_link . '" title="' . $favorite_article['title'] . '">' . $favorite_article['title'] . '</a></h4>';
			if ($favorite_article['description'] != '' || $favorite_article['article_layout'] != 'media') {

				$description = strip_tags($favorite_article['description']);
				$description_string_count = mb_strlen($description);
				if ($description_string_count > 160) {
					$description = mb_substr($description, 0, 157) . '...';
				}

				$data_html .= '<p>' . $description . '</p>';
			}

			$data_html .= '<time>' . $arabic_week_days[$day] . ', ' . $date . ' ' . $arabic_months[$month] . ' ' . $year . '</time>';
			$data_html .= '</div>';
			$data_html .= '</div>';
			$data_html .= '</div>';
		}


		$response = array();
		$response['csrf_test_name'] = csrf_hash();
		$response['limit_start'] = $limit_start + $limit_end;
		$response['data'] = $data_html;

		echo json_encode($response);
		exit;
	}
}
