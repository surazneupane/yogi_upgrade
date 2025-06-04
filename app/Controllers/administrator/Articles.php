<?php

namespace App\Controllers\administrator;

use App\Libraries\Slug;
use CodeIgniter\Database\Config;

class Articles extends AdminController
{

	public $db;
	public $slugLibrary;

	function __construct()
	{
		parent::__construct();
		//if(!$this->ion_auth->in_group('admin'))
		if (!$this->ionAuthLibrary->in_access('article_access', 'access')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to visit the Articles page.');
			header('Location: ' . site_url('administrator'));
			exit;
		}

		$siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];


		$config = array(
			'field' => 'alias',
			'title' => 'title',
			'table' => 'articles',
			'id' => 'id',
		);

		$this->slugLibrary = new Slug($config);
		$this->db = Config::connect();
		// $this->load->helper('categories');
		// $this->load->helper('Articles');
		// $this->load->library('slug', $config);
		//$this->load->library('guzzle');

	}


	public function index()
	{
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Articles';
		$current_user = $this->ionAuthModel->user()->getRow();
		// $this->load->helper('form');

		$task = $this->request->getPost('task');
		$filter = $this->request->getPost('filter');

		$article_ids = $this->request->getPost('article_id');

		if ($task == 'publish') {
			return $this->publish($article_ids);
		} elseif ($task == 'unpublish') {
			return $this->unpublish($article_ids);
		} elseif ($task == 'delete') {
			return $this->delete($article_ids);
		} elseif ($task == 'featured') {
			return $this->featured($article_ids);
		} elseif ($task == 'unfeatured') {
			return $this->unfeatured($article_ids);
			//} elseif($task == 'imagearrange') {
			//	$this->imagearrange($article_ids);
		}

		if (!empty($filter)) {
			session()->set('articles_filter', $filter);
		}

		$where = ' WHERE 1';
		$articles_filter = session()->get('articles_filter');

		if (isset($articles_filter['status']) && $articles_filter['status'] != '') {
			$where .= ' AND status = ' . $articles_filter['status'];
		}
		if (!empty($articles_filter['category_id'])) {
			$where .= ' AND category_ids LIKE ("%' . $articles_filter['category_id'] . '%")';
		}
		if (!empty($articles_filter['tag_id'])) {
			$where .= ' AND tag_ids LIKE ("%' . $articles_filter['tag_id'] . '%")';
		}

		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/

		$qry = 'Select * from articles' . $where . ' LIMIT 0, 10'; // select data from db

		$this->data['articles'] = $this->db->query($qry)->getResultArray();
		$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', '');
		$this->data['taglist'] = $this->taglist();
		$this->data['current_user'] = $current_user;
		$this->render('administrator/articles/list_articles_view');
	}



	public function fetchArticleData()
	{

		$draw = $this->request->getGet('draw');
		$length = $this->request->getGet('length');
		$start = $this->request->getGet('start');
		$columns = $this->request->getGet('columns');
		$order = $this->request->getGet('order');
		$search = $this->request->getGet('search');
		$status = $this->request->getGet('status');
		$category_id = $this->request->getGet('category_id');
		$tag_id = $this->request->getGet('tag_id');

		if (isset($order)) {
			$orderfield = $columns[$order[0]['column']]['name'];
			$orderby = $order[0]['dir'];
		} else {
			$orderfield = 'id';
			$orderby = 'desc';
		}

		$result = array('data' => array());

		$qry = 'Select count(*) AS record_count from articles'; // select data from db 
		$data_count = $this->db->query($qry)->getResultArray();

		$where = ' WHERE 1';
		if ($search['value'] != '') {
			$where .= ' AND `title` LIKE "%' . $search['value'] . '%"';
		}

		if ($status != '') {
			$where .= ' AND status = "' . $status . '"';
		}
		if ($category_id != '') {
			$where .= ' AND category_ids LIKE ("%' . $category_id . '%")';
		}
		if ($tag_id != '') {
			$where .= ' AND tag_ids LIKE ("%' . $tag_id . '%")';
		}

		$qry = 'Select * from `articles`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby . ' LIMIT ' . $start . ', ' . $length; // select data from db      		
		$data = $this->db->query($qry)->getResultArray();

		$qry = 'Select count(*) AS fileter_record_count from `articles`' . $where . ' ORDER BY `' . $orderfield . '` ' . $orderby; // select data from db      
		$data_filter_count = $this->db->query($qry)->getResultArray();


		$result['draw'] = $draw;
		$result['recordsTotal'] = $data_count[0]['record_count'];
		$result['recordsFiltered'] = $data_filter_count[0]['fileter_record_count'];
		$result['csrf_test_name'] = csrf_hash();

		foreach ($data as $key => $value) {

			// GENERATE ARTICLE LINKS FROM ARTICLE HELPER
			$article_preview_link = generateArticleLink($value['category_ids'], $value['alias']);

			$categorylist = explode(',', $value['category_ids']);

			$checkbox = '<input type="checkbox" id="article_id' . $key . '" name="article_id[]" value="' . $value['id'] . '" />';

			$id = $value['id'];

			$status = '<div class="btn-group center-block">';
			$statusval = $value['status'];
			if ($statusval == 1) {
				$status .= anchor('administrator/articles/unpublish/' . $value['id'], '<i class="fa fa-check"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unpublish Item'));
			} else {
				$status .= anchor('administrator/articles/publish/' . $value['id'], '<i class="fa fa-times-circle"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Publish Item'));
			}
			$statusFeatured = $value['featured'];
			if ($statusFeatured == 1) {
				$status .= anchor('administrator/articles/unfeatured/' . $value['id'], '<i class="fa fa-star"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Unfeatured Item'));
			} else {
				$status .= anchor('administrator/articles/featured/' . $value['id'], '<i class="fa fa-star-o"></i>', array('class' => 'btn btn-sm btn-default active', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Featured Item'));
			}
			$status .= '</div>';

			$title = anchor('administrator/articles/edit/' . $value['id'], $value['title'], array('class' => '', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit')) . ' <span class="small">(Alias : ' . $value['alias'] . ')</span><div class="small">Category : ' . getCategoryName($categorylist) . '</div>';

			$article_layout = $value['article_layout'];
			if ($article_layout == 'text') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-document-text"></i>&nbsp;&nbsp; Text</span>';
			} elseif ($article_layout == 'media') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-easel"></i>&nbsp;&nbsp; Media</span>';
			} elseif ($article_layout == 'recipe') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-android-restaurant"></i>&nbsp;&nbsp; Recipe</span>';
			} elseif ($article_layout == 'horoscope') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-wand"></i>&nbsp;&nbsp; Horoscope</span>';
			} elseif ($article_layout == 'text_media') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-image"></i>&nbsp;&nbsp; Text + Media</span>';
			} elseif ($article_layout == 'text_mulitplemedia') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-images"></i>&nbsp;&nbsp; Text + Multiplemedia</span>';
			} elseif ($article_layout == 'text_mulitplemedia_6') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-images"></i>&nbsp;&nbsp; Text + Multiplemedia 6++</span>';
			} elseif ($article_layout == 'fashion') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-bowtie"></i>&nbsp;&nbsp; Fashion</span>';
			} elseif ($article_layout == 'travel') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-plane"></i>&nbsp;&nbsp; Travel</span>';
			} elseif ($article_layout == 'runaway') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-plane"></i>&nbsp;&nbsp; Runway</span>';
			} elseif ($article_layout == 'questions_test') {
				$layout = '<span class="label label-info label-block"><i class="ion ion-help"></i>&nbsp;&nbsp; Questions Test</span>';
			} else {
				$layout = '<span class="label label-info label-block">' . $article_layout . '</span>';
			}

			$created_date = date('Y-m-d', strtotime($value['created_date']));

			if ($value['hits'] <= 20) {
				$hit_class = 'bg-primary';
			} elseif ($value['hits'] <= 40) {
				$hit_class = 'bg-primary';
			} elseif ($value['hits'] <= 60) {
				$hit_class = 'bg-red';
			} elseif ($value['hits'] <= 80) {
				$hit_class = 'bg-red';
			} elseif ($value['hits'] <= 100) {
				$hit_class = 'bg-green';
			} else {
				$hit_class = 'bg-green';
			}
			$hits = '<a href="javascript:void(0)" class="label ' . $hit_class . '" title="' . $value['hits'] . ' Views">' . $value['hits'] . '</a>';

			$class = 'btn btn-success';
			$buttons = anchor($article_preview_link, '<i class="fa fa-eye"></i>', array('target' => '_blank', 'class' => 'btn btn-info', 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Preview'));
			if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
				$class .= ' disabled';
			}
			$buttons .= '&nbsp;' . anchor('administrator/articles/edit/' . $value['id'], '<i class="fa fa-pencil"></i>', array('class' => $class, 'data-toggle' => 'tooltip', 'data-placement' => 'top', 'title' => 'Edit'));
			if (!in_array($value['title'], array('admin'))) {

				$class = 'btn btn-danger btn-delete';
				if (!$this->ionAuthLibrary->in_access('article_access', 'delete')) {
					$class .= ' disabled';
				}
				//$buttons .= '&nbsp;<a href="javascript:void(0)" data-url="'.site_url('administrator/tags/delete/'.$value['id']).'" class="'.$class.'" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
				$onClick = " onclick= deleteArticle('" . site_url('administrator/articles/delete/' . $value['id']) . "')";
				$buttons .= '&nbsp;<a href="javascript:void(0)" ' . $onClick . ' class="' . $class . '" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>';
			}

			$result['data'][$key] = array(
				$checkbox,
				$id,
				$status,
				$title,
				$layout,
				$created_date,
				$hits,
				$buttons
			);
		}

		echo json_encode($result);
	}


	public function articles_modal($search_val = NULL)
	{

		$filter = array();
		$filter['search_val'] = urldecode($search_val);
		session()->set('articles_modal_filter', $filter);

		$filter = $this->request->getPost('filter');

		if (!empty($filter)) {
			session()->set('articles_modal_filter', $filter);
		}

		$where = ' WHERE `status` = 1';
		$articles_modal_filter = session()->get('articles_modal_filter');

		if (!empty($articles_modal_filter['search_val'])) {
			$where .= ' AND `title` LIKE ("%' . $articles_modal_filter['search_val'] . '%") OR `description` LIKE ("%' . $articles_modal_filter['search_val'] . '%") OR `tag_ids` IN (SELECT `id` FROM `tags` WHERE `title` LIKE ("%' . $articles_modal_filter['search_val'] . '%"))';
		}

		$qry = 'SELECT `id`, `category_ids`, `title`, `alias` FROM `articles` ' . $where . ' ORDER BY `id` DESC'; // select data from db
		$this->data['articleslist_modal'] = $this->db->query($qry)->getResultArray();
		$this->render('administrator/articles/list_articles_modal_view');
	}


	public function create()
	{
		if (!$this->ionAuthLibrary->in_access('article_access', 'add')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Create Article.');
			return redirect()->to('administrator/articles');
		}

		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Create Article';

		$task = $this->request->getPost('task');

		// $this->load->library('form_validation');
		// $this->load->helper("url");
		// $this->form_validation->set_rules('title', 'Article Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Article Alias', 'trim|is_unique[articles.alias]');
		// $this->form_validation->set_rules('main_category_id', 'Category', 'required');
		//$this->form_validation->set_rules('main_subcategory_ids[]','Sub Category','required');


		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Article Title',
				'rules' => 'required'
			],
			'alias' => [
				'label' => 'Article Alias',
				'rules' => 'is_unique[articles.alias]'
			],
			'main_category_id' => [
				'label' => 'Category',
				'rules' => 'required'
			],
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}

		if ($this->request->getMethod() == "GET") {

			// $this->load->helper('form');
			$this->data['categorieslist'] = $this->fetchMainCategoryTree('0', '-', '', '');
			$this->data['userlist'] = $this->userlist();
			$this->data['videolist'] = $this->videolist();
			$this->data['taglist'] = $this->taglist();
			$this->render('administrator/articles/create_article_view');
		} else {


			$main_subcategory_ids = $this->request->getPost('main_subcategory_ids');
			if (!empty($main_subcategory_ids)) {
				$main_subcategory_ids = implode(',', $main_subcategory_ids);
				$category_ids = $this->request->getPost('main_category_id') . ',' . $main_subcategory_ids;
			} else {
				$category_ids = $this->request->getPost('main_category_id');
			}
			if (!empty($this->request->getPost('tag_ids'))) {
				$tag_ids = implode(',', $this->request->getPost('tag_ids'));
			} else {
				$tag_ids = '';
			}
			$title = strip_tags($this->request->getPost('title'));
			$title_fr = strip_tags($this->request->getPost('title_fr'));
			if ($title_fr == '') {
				$title_fr = $title;
			}
			$aliasTemp = $this->request->getPost('alias');
			if ($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
			$alias = $this->slugLibrary->create_uri($data);

			$alias_frTemp = $this->request->getPost('alias_fr');
			if ($alias_frTemp == '') {
				$data = array(
					'title' => $title_fr,
				);
			} else {
				$data = array(
					'title' => $alias_frTemp,
				);
			}
			$alias_fr = $this->slugLibrary->create_uri($data);
			$keywords = $this->request->getPost('keywords');
			$article_layout = $this->request->getPost('article_layout');
			$description = $this->request->getPost('description');
			$description_fr = $this->request->getPost('description_fr');
			if ($description_fr == '') {
				$description_fr = $description;
			}
			$image = $this->request->getPost('image');
			$image_fr = $this->request->getPost('image_fr');
			if ($image_fr == '') {
				$image_fr = $image;
			}
			$mangomolo_videoid = $this->request->getPost('mangomolo_videoid');
			$media_images = json_encode($this->request->getPost('media_images'));
			$media_texts = json_encode($this->request->getPost('media_texts'));
			$media_image_main = $this->request->getPost('media_image_main');
			$recipe_time = $this->request->getPost('recipe_time');
			$recipe_persons = $this->request->getPost('recipe_persons');
			$recipe_ingredients = json_encode($this->request->getPost('recipe_ingredients'));
			$horoscope_date = $this->request->getPost('horoscope_date');
			$horoscope_data = json_encode($this->request->getPost('horoscope_data'));
			$travel_data = json_encode($this->request->getPost('travel_data'));
			$questions_test_data = json_encode($this->request->getPost('questions_test_data'));
			$questions_test_answer_titles = json_encode($this->request->getPost('questions_test_answer_titles'));
			$questions_test_answer_data = json_encode($this->request->getPost('questions_test_answer_data'));
			$featured = $this->request->getPost('featured');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$published_date = $this->request->getPost('published_date');
			$published_by = $this->request->getPost('published_by');
			$meta_title = strip_tags($this->request->getPost('meta_title'));
			$meta_description = $this->request->getPost('meta_description');
			$meta_title_fr = strip_tags($this->request->getPost('meta_title_fr'));
			$meta_description_fr = $this->request->getPost('meta_description_fr');

			$sql = "Insert into articles (`category_ids`, `title`, `title_fr`, `alias`, `alias_fr`, `keywords`, `tag_ids`, `article_layout` , `description`, `description_fr`, `image`, `image_fr`, `mangomolo_videoid`, `media_images`, `media_texts`, `media_image_main`, `recipe_time`, `recipe_persons`, `recipe_ingredients`, `horoscope_date`, `horoscope_data`, `travel_data`, `questions_test_data`, `questions_test_answer_titles`, `questions_test_answer_data`, `featured`, `status`, `created_date`, `created_by`, `published_date`, `published_by`, `meta_title`, `meta_description`, `meta_title_fr`, `meta_description_fr`) VALUES ('" . addslashes($category_ids) . "', '" . addslashes($title) . "', '" . addslashes($title_fr) . "', '" . addslashes($alias) . "', '" . addslashes($alias_fr) . "', '" . addslashes($keywords) . "', '" . addslashes($tag_ids) . "', '" . addslashes($article_layout) . "', '" . addslashes($description) . "', '" . addslashes($description_fr) . "', '" . addslashes($image) . "', '" . addslashes($image_fr) . "', '" . addslashes($mangomolo_videoid) . "', '" . addslashes($media_images) . "', '" . addslashes($media_texts) . "', '" . addslashes($media_image_main) . "', '" . addslashes($recipe_time) . "', '" . addslashes($recipe_persons) . "', '" . addslashes($recipe_ingredients) . "', '" . addslashes($horoscope_date) . "', '" . addslashes($horoscope_data) . "', '" . addslashes($travel_data) . "', '" . addslashes($questions_test_data) . "', '" . addslashes($questions_test_answer_titles) . "', '" . addslashes($questions_test_answer_data) . "', '" . addslashes($featured) . "', '" . addslashes($status) . "', '" . addslashes($created_date) . "', '" . addslashes($created_by) . "', '" . addslashes($published_date) . "', '" . addslashes($published_by) . "', '" . addslashes($meta_title) . "', '" . addslashes($meta_description) . "', '" . addslashes($meta_title_fr) . "', '" . addslashes($meta_description_fr) . "')";

			$val = $this->db->query($sql);

			$insert_id = $this->db->insertID();

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article created successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/articles/edit/' . $insert_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/articles';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/articles/create';
			} else {
				$redirect_url = 'administrator/articles/edit/' . $insert_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function edit($article_id = NULL, $task = 'save')
	{
		if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Edit Article.');
			return redirect()->to('administrator/articles');
		}

		$article_id = $this->request->getPost('article_id') ? $this->request->getPost('article_id') : $article_id;
		$task = $this->request->getPost('task') ? $this->request->getPost('task') : $task;
		$this->data['page_title'] = $this->data['page_title'] . ' Administrator : Edit Article';
		// $this->load->library('form_validation');
		// $this->load->helper("url");

		// $this->form_validation->set_rules('title', 'Article Title', 'trim|required');
		// $this->form_validation->set_rules('alias', 'Article Alias', 'trim');
		// $this->form_validation->set_rules('main_category_id', 'Category', 'required');
		//$this->form_validation->set_rules('main_subcategory_ids[]','Sub Category','required');

		$validation = \Config\Services::validation();

		$validation->setRules([
			'title' => [
				'label' => 'Article Title',
				'rules' => 'trim|required',
			],
			'alias' => [
				'label' => 'Article Alias',
				'rules' => 'trim',
			],
			'main_category_id' => [
				'label' => 'Category',
				'rules' => 'required',
			],
		]);

		if ($this->request->getMethod() == "POST" && !$validation->withRequest($this->request)->run()) {
			return redirect()->back()->withInput()->with('errors', $validation->getErrors());
		}

		// Proceed with saving or updating the data


		if ($this->request->getMethod() == "GET") {

			// $this->load->helper('form');
			$qry = 'Select * from articles where id = ' . $article_id; // select data from db
			$article = $this->db->query($qry)->getResultArray();

			$this->data['article'] = $article[0];
			$this->data['categorieslist'] = $this->fetchMainCategoryTree('0', '-', '', '');
			$this->data['userlist'] = $this->userlist();
			$this->data['videolist'] = $this->videolist();
			$this->data['taglist'] = $this->taglist();

			//if(!$this->ion_auth->in_group('admin') && $current_user->id != $this->data['article']['created_by'])
			//{
			//	session()->setFlashdata('message_type', 'warning');
			//	session()->setFlashdata('message','You are not allowed to edit this Article.');
			//	return redirect()->to('administrator/categories','refresh');
			//}
			$this->render('administrator/articles/edit_article_view');
		} else {


			$main_subcategory_ids = $this->request->getPost('main_subcategory_ids');
			if (!empty($main_subcategory_ids)) {
				$main_subcategory_ids = implode(',', $main_subcategory_ids);
				$category_ids = $this->request->getPost('main_category_id') . ',' . $main_subcategory_ids;
			} else {
				$category_ids = $this->request->getPost('main_category_id');
			}
			if (!empty($this->request->getPost('tag_ids'))) {
				$tag_ids = implode(',', $this->request->getPost('tag_ids'));
			} else {
				$tag_ids = '';
			}
			$title = strip_tags($this->request->getPost('title'));
			$title_fr = strip_tags($this->request->getPost('title_fr'));
			if ($title_fr == '') {
				$title_fr = $title;
			}
			$aliasTemp = $this->request->getPost('alias');
			if ($aliasTemp == '') {
				$data = array(
					'title' => $title,
				);
			} else {
				$data = array(
					'title' => $aliasTemp,
				);
			}
			$alias = $this->slugLibrary->create_uri($data, $article_id);
			$alias_frTemp = $this->request->getPost('alias_fr');
			if ($alias_frTemp == '') {
				$data = array(
					'title' => $title_fr,
				);
			} else {
				$data = array(
					'title' => $alias_frTemp,
				);
			}
			$alias_fr = $this->slugLibrary->create_uri($data, $article_id);
			$keywords = $this->request->getPost('keywords');
			$article_layout = $this->request->getPost('article_layout');
			$description = $this->request->getPost('description');
			$description_fr = $this->request->getPost('description_fr');
			if ($description_fr == '') {
				$description_fr = $description;
			}
			$image = $this->request->getPost('image');
			$image_fr = $this->request->getPost('image_fr');
			if ($image_fr == '') {
				$image_fr = $image;
			}
			$mangomolo_videoid = $this->request->getPost('mangomolo_videoid');
			$media_images = json_encode($this->request->getPost('media_images'));
			$media_texts = json_encode($this->request->getPost('media_texts'));
			$media_image_main = $this->request->getPost('media_image_main');
			$recipe_time = $this->request->getPost('recipe_time');
			$recipe_persons = $this->request->getPost('recipe_persons');
			$recipe_ingredients = json_encode($this->request->getPost('recipe_ingredients'));
			$horoscope_date = $this->request->getPost('horoscope_date');
			$horoscope_data = json_encode($this->request->getPost('horoscope_data'));
			$travel_data = json_encode($this->request->getPost('travel_data'));
			$questions_test_data = json_encode($this->request->getPost('questions_test_data'));
			$questions_test_answer_titles = json_encode($this->request->getPost('questions_test_answer_titles'));
			$questions_test_answer_data = json_encode($this->request->getPost('questions_test_answer_data'));
			$featured = $this->request->getPost('featured');
			$status = $this->request->getPost('status');
			$created_date = $this->request->getPost('created_date');
			$created_by = $this->request->getPost('created_by');
			$published_date = $this->request->getPost('published_date');
			$published_by = $this->request->getPost('published_by');
			$updated_date = $this->request->getPost('updated_date');
			$updated_by = $this->request->getPost('updated_by');
			$meta_title = strip_tags($this->request->getPost('meta_title'));
			$meta_description = $this->request->getPost('meta_description');
			$meta_title_fr = strip_tags($this->request->getPost('meta_title_fr'));
			$meta_description_fr = $this->request->getPost('meta_description_fr');

			$sql = "update articles set `category_ids`='" . addslashes($category_ids) . "', `title`='" . addslashes($title) . "', `title_fr`='" . addslashes($title_fr) . "', `alias`='" . addslashes($alias) . "', `alias_fr`='" . addslashes($alias_fr) . "', `keywords`='" . addslashes($keywords) . "', `tag_ids`='" . addslashes($tag_ids) . "', `article_layout`='" . addslashes($article_layout) . "', `description`='" . addslashes($description) . "', `description_fr`='" . addslashes($description_fr) . "', `image`='" . addslashes($image) . "', `image_fr`='" . addslashes($image_fr) . "', `mangomolo_videoid`='" . addslashes($mangomolo_videoid) . "', `media_images`='" . addslashes($media_images) . "', `media_texts`='" . addslashes($media_texts) . "', `media_image_main`='" . addslashes($media_image_main) . "', `recipe_time`='" . addslashes($recipe_time) . "', `recipe_persons`='" . addslashes($recipe_persons) . "', `recipe_ingredients`='" . addslashes($recipe_ingredients) . "', `horoscope_date`='" . addslashes($horoscope_date) . "', `horoscope_data`='" . addslashes($horoscope_data) . "', `travel_data`='" . addslashes($travel_data) . "', `questions_test_data`='" . addslashes($questions_test_data) . "', `questions_test_answer_titles`='" . addslashes($questions_test_answer_titles) . "', `questions_test_answer_data`='" . addslashes($questions_test_answer_data) . "', `featured`='" . addslashes($featured) . "', `status`='" . addslashes($status) . "', `created_date`='" . addslashes($created_date) . "', `created_by`='" . addslashes($created_by) . "', `published_date`='" . addslashes($published_date) . "', `published_by`='" . addslashes($published_by) . "', `updated_date`='" . addslashes($updated_date) . "', `updated_by`='" . addslashes($updated_by) . "', `meta_title`='" . addslashes($meta_title) . "', `meta_description`='" . addslashes($meta_description) . "', `meta_title_fr`='" . addslashes($meta_title_fr) . "', `meta_description_fr`='" . addslashes($meta_description_fr) . "' where id= '" . $article_id . "'";

			$val = $this->db->query($sql);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article saved successfully.');

			if ($task == 'save') {
				$redirect_url = 'administrator/articles/edit/' . $article_id;
			} elseif ($task == 'save_close') {
				$redirect_url = 'administrator/articles';
			} elseif ($task == 'save_new') {
				$redirect_url = 'administrator/articles/create';
			} else {
				$redirect_url = 'administrator/articles/edit/' . $article_id;
			}

			return redirect()->to($redirect_url);
		}
	}


	public function unpublish($article_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Article.');
			return redirect()->to('administrator/articles');
		}

		if (is_null($article_id) || empty($article_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no article to unpublish.');
		} else {

			if (is_array($article_id)) {
				$article_id = implode('","', $article_id);
				$qry = 'update articles set `status` = 0 where id IN ( "' . $article_id . '")'; // select data from db
			} else {
				$qry = 'update articles set `status` = 0 where id = ' . $article_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article unpublished successfully.');
		}
		return redirect()->to('administrator/articles');
	}


	public function publish($article_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Article.');
			return redirect()->to('administrator/articles');
		}

		if (is_null($article_id) || empty($article_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no article to publish.');
		} else {

			if (is_array($article_id)) {
				$article_id = implode('","', $article_id);
				$qry = 'update articles set `status` = 1 where id IN ( "' . $article_id . '")'; // select data from db
			} else {
				$qry = 'update articles set `status` = 1 where id = ' . $article_id; // select data from db
			}
			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article published successfully.');
		}
		return redirect()->to('administrator/articles');
	}


	public function delete($article_id = NULL)
	{
		
		if (!$this->ionAuthLibrary->in_access('article_access', 'delete')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to Delete Article.');
			return redirect()->to('administrator/articles');
		}

		if (is_null($article_id) || empty($article_id)) {
			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no article to delete.');
		} else {

			if (is_array($article_id)) {
				$article_id = implode('","', $article_id);
				$qry = 'delete from articles where `id` IN ( "' . $article_id . '")'; // select data from db
			} else {
				$qry = 'delete from articles where `id` = ' . $article_id; // select data from db
			}

			$this->data['articles'] = $this->db->query($qry);

			$message_type = 'success';
			$message = 'Article deleted successfully.';

			session()->setFlashdata('message_type', $message_type);
			session()->setFlashdata('message', $message);
		}
		return redirect()->to('administrator/articles');
	}


	public function unfeatured($article_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Article.');
			return redirect()->to('administrator/articles');
		}

		if (is_null($article_id) || empty($article_id)) {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no article to unfeatured.');
		} else {

			if (is_array($article_id)) {
				$article_id = implode('","', $article_id);
				$qry = 'update articles set `featured` = "0" where `id` IN ( "' . $article_id . '")'; // select data from db
			} else {
				$qry = 'update articles set `featured` = "0" where `id` = ' . $article_id; // select data from db
			}

			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article unfeatured successfully.');
		}
		return redirect()->to('administrator/articles');
	}


	public function featured($article_id = NULL)
	{

		if (!$this->ionAuthLibrary->in_access('article_access', 'update')) {
			session()->setFlashdata('message_type', 'warning');
			session()->setFlashdata('message', 'You are not allowed to change status of Article.');
			return redirect()->to('administrator/articles');
		}

		if (is_null($article_id) || empty($article_id)) {

			session()->setFlashdata('message_type', 'danger');
			session()->setFlashdata('message', 'There\'s no article to featured.');
		} else {

			if (is_array($article_id)) {
				$article_id = implode('","', $article_id);
				$qry = 'update articles set `featured` = "1" where `id` IN ( "' . $article_id . '")'; // select data from db
			} else {
				$qry = 'update articles set `featured` = "1" where `id` = ' . $article_id; // select data from db
			}

			$val = $this->db->query($qry);

			session()->setFlashdata('message_type', 'success');
			session()->setFlashdata('message', 'Article featured successfully.');
		}
		return redirect()->to('administrator/articles');
	}


	public function fetchCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '')
	{

		if (!is_array($category_tree_array))

			$category_tree_array = array();

		$qry = "SELECT `id`, `title`, `parent_id` FROM `categories` WHERE 1 AND `status` = 1 AND `parent_id` = $parent_id ORDER BY id DESC";
		$categorieslist = $this->db->query($qry)->getResultArray();

		if (!empty($categorieslist)) {
			foreach ($categorieslist as $categorylist) {
				if ($categorylist['id'] != $exclude_id) {
					$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' ' . $categorylist['title'], "parent_id" => $categorylist['parent_id']);
					$category_tree_array = $this->fetchCategoryTree($categorylist['id'], $spacing . '-', $category_tree_array, $exclude_id);
				}
			}
		}

		return $category_tree_array;
	}


	public function fetchMainCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '')
	{

		if (!is_array($category_tree_array))

			$category_tree_array = array();

		$qry = "SELECT `id`, `title`, `parent_id` FROM `categories` WHERE 1 AND `status` = 1 AND `parent_id` = $parent_id ORDER BY id DESC";
		$categorieslist = $this->db->query($qry)->getResultArray();

		if (!empty($categorieslist)) {
			foreach ($categorieslist as $categorylist) {
				if ($categorylist['id'] != $exclude_id) {
					$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' ' . $categorylist['title'], "parent_id" => $categorylist['parent_id']);
				}
			}
		}

		return $category_tree_array;
	}


	public function userlist()
	{

		$qry = 'Select * from users where active = 1'; // select data from db
		$users = $this->db->query($qry)->getResultArray();

		return $users;
	}


	public function videolist()
	{

		$qry = 'Select * from videos'; // select data from db
		$videos = $this->db->query($qry)->getResultArray();

		return $videos;
	}


	public function taglist()
	{

		$qry = 'Select * from tags where status = 1'; // select data from db
		$tags = $this->db->query($qry)->getResultArray();

		return $tags;
	}
}
