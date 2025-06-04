<?php
namespace App\Controllers\administrator;




class Reports extends Admin_Controller {


   /**
	 * Cpanel Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/administrator
	 *	- or -
	 * 		http://example.com/index.php/administrator/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/administrator/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
   
    public function __construct() {
        parent::__construct();
        
        $siteConfiguration = siteConfiguration();
		$this->data['page_title'] = $siteConfiguration['site_title'];
        
		$this->load->library('Pdf');
		
		if(!$this->ion_auth->in_group('admin'))	{
			$this->session->set_flashdata('message_type', 'warning');
			$this->session->set_flashdata('message','You are not allowed to visit the Reports page.');
			redirect('administrator','refresh');
		}
		
    }
    

    public function articles_reports() {
    	    	    	
    	$this->data['page_title'] = $this->data['page_title'].' Administrator : Articles Reports';
    	
    	$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
  		  		
  		$filter = $this->input->post('filter');
  		$search = $this->input->post('search');

  		//if(!empty($filter) && $filter['status'] != '' || $filter['category_id'] != '' || $filter['tag_id'] != '') {
  		
		$this->session->set_userdata('articles_reports_filter', $filter);
			
		$where = ' WHERE 1';
  		$articles_reports_filter = $this->session->userdata('articles_reports_filter');
  		  		
		if($articles_reports_filter['status'] != '') {
			$where .= ' AND `status` = '.$articles_reports_filter['status'];
		}
		if($articles_reports_filter['start_date'] != '' && $articles_reports_filter['end_date'] != '') { 
			$where .= ' AND `created_date` BETWEEN "'.$articles_reports_filter['start_date'].'" AND "'.$articles_reports_filter['end_date'].'"';
		}
		if($articles_reports_filter['category_id'] != '') { 
			$where .= ' AND `category_ids` LIKE ("%'.$articles_reports_filter['category_id'].'%")';
		}
		if($articles_reports_filter['tag_id'] != '') { 
			$where .= ' AND `tag_ids` LIKE ("%'.$articles_reports_filter['tag_id'].'%")';
		}
		if($articles_reports_filter['usergroup_id'] != '' && $articles_reports_filter['user_id'] != '') { 
			$where .= ' AND `created_by` = "'.$articles_reports_filter['user_id'].'"';
		}
  		
  		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/
		
       	$qry ='Select * from `articles`'.$where; // select data from db
       				
       	$this->data['articles'] = $this->db->query($qry)->result_array();
  		
    	$this->data['categorieslist'] = $this->fetchCategoryTree('0', '-', '', '', ' `status` = 1');
       	$this->data['taglist'] = $this->taglist();
       	$this->data['usergrouplist'] = $this->usergrouplist();
       	       	
        $this->render('administrator/reports/articles_reports_view');
    }
    

    
    public function videos_reports() {
    	    	    	
    	$this->data['page_title'] = $this->data['page_title'].' Administrator : Videos Reports';
    	
    	$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
  		  		
  		$filter = $this->input->post('filter');
  		$search = $this->input->post('search');

  		//if(!empty($filter) && $filter['status'] != '' || $filter['category_id'] != '' || $filter['tag_id'] != '') {
  		
		$this->session->set_userdata('videos_reports_filter', $filter);
			
		$where = ' WHERE 1';
  		$videos_reports_filter = $this->session->userdata('videos_reports_filter');
  		
		if($videos_reports_filter['start_date'] != '' && $videos_reports_filter['end_date'] != '') { 
			$where .= ' AND `created_date` BETWEEN "'.$videos_reports_filter['start_date'].'" AND "'.$videos_reports_filter['end_date'].'"';
		}
		  		
  		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/
		
       	$qry ='Select * from `videos`'.$where; // select data from db
       				
       	$this->data['videos'] = $this->db->query($qry)->result_array();
  		
        $this->render('administrator/reports/videos_reports_view');
    }


    
    
    public function categories_reports() {
    	    	    	
    	$this->data['page_title'] = $this->data['page_title'].' Administrator : Categories Reports';
    	
    	$current_user = $this->ion_auth->user()->row();
  		$this->load->helper('form');
  		  		
  		$filter = $this->input->post('filter');

  		//if(!empty($filter) && $filter['status'] != '' || $filter['category_id'] != '' || $filter['tag_id'] != '') {
  		
		$this->session->set_userdata('categories_reports_filter', $filter);
			
		$where = ' 1';
  		$categories_reports_filter = $this->session->userdata('categories_reports_filter');
  		  		  		
		if($categories_reports_filter['status'] != '') {
			$where .= ' AND `status` = '.$categories_reports_filter['status'];
		}
		if($categories_reports_filter['start_date'] != '' && $categories_reports_filter['end_date'] != '') { 
			$where .= ' AND `created_date` BETWEEN "'.$categories_reports_filter['start_date'].'" AND "'.$categories_reports_filter['end_date'].'"';
		}
  		
  		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/
		  		
    	$this->data['categories'] = $this->fetchCategoryTree('0', '-', '', '', $where);
       	       	       	
        $this->render('administrator/reports/categories_reports_view');
    }

    
    
    public function fetchCategoryTree($parent_id = 0, $spacing = '-', $category_tree_array = '', $exclude_id = '', $where) {
 		
		if (!is_array($category_tree_array))
				
			$category_tree_array = array();
		
			$qry = "SELECT * FROM `categories` WHERE ".$where." AND `parent_id` = $parent_id ORDER BY id DESC";
			$categorieslist = $this->db->query($qry)->result_array();
			
			if (!empty($categorieslist)) {
				foreach ($categorieslist AS $categorylist) {
					if($categorylist['id'] != $exclude_id) {
						$category_tree_array[] = array("id" => $categorylist['id'], "title" => $spacing . ' '.$categorylist['title'], "parent_id" => $categorylist['parent_id'], "alias" => $categorylist['alias'], "status" => $categorylist['status'], "ordering" => $categorylist['ordering']);
						$category_tree_array = $this->fetchCategoryTree($categorylist['id'], $spacing . '-', $category_tree_array, $exclude_id, $where);
					}
				}
			}
		
		return $category_tree_array;
	}
	
	
	public function taglist() {
				
       	$qry ='Select * from tags where status = 1'; // select data from db
       	$tags = $this->db->query($qry)->result_array();
       	
       	return $tags;
	}
	
	
	public function usergrouplist() {
				
       	$qry ='Select * from groups where id != 5'; // select data from db
       	$usergroups = $this->db->query($qry)->result_array();
       	
       	return $usergroups;
	}
	
	
	public function download_articles_reports() {
		
		$articles_reports_filter = $this->session->userdata('articles_reports_filter');
		
		$where = ' WHERE 1';
  		  		
		if(!empty($articles_reports_filter['status'])) {
			$where .= ' AND `status` = '.$articles_reports_filter['status'];
		}
		if(!empty($articles_reports_filter['start_date']) && !empty($articles_reports_filter['end_date'])) { 
			$where .= ' AND `created_date` BETWEEN "'.$articles_reports_filter['start_date'].'" AND "'.$articles_reports_filter['end_date'].'"';
		}
		if(!empty($articles_reports_filter['category_id'])) { 
			$where .= ' AND `category_ids` LIKE ("%'.$articles_reports_filter['category_id'].'%")';
		}
		if(!empty($articles_reports_filter['tag_id'])) { 
			$where .= ' AND `tag_ids` LIKE ("%'.$articles_reports_filter['tag_id'].'%")';
		}		
		if(!empty($articles_reports_filter['usergroup_id']) && !empty($articles_reports_filter['user_id'])) { 
			$where .= ' AND `created_by` = "'.$articles_reports_filter['user_id'].'"';
		}
  				
       	$qry ='Select * from `articles`'.$where; // select data from db
       				
       	$articles = $this->db->query($qry)->result_array();
       	
		// create new PDF document
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		
		// set document information
		//$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetCreator('Yawmiyati');
		$pdf->SetAuthor('DOTCOM');
		$pdf->SetTitle('Yawmiyati - Articles Report');
		$pdf->SetSubject('Articles Report');
		$pdf->SetKeywords('Yawmiyati, Articles Report, DOTCOM');
		
		// set default header data
		//$pdf->SetHeaderData('http://localhost/yawmiyati/assets/site/images/logo.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
		$pdf->SetHeaderData(site_url().'assets/site/images/logo.png', 10, 'Yawmiyati - Articles Report', 'by YAWMIYATI - www.yawmiyati.com');
		
		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setHeaderFont(Array('helvetica', '', '10'));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setFooterFont(Array('helvetica', '', '10'));
		
		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetDefaultMonospacedFont('courier');
		
		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetMargins( 5, 20, 5);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetHeaderMargin(5);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetFooterMargin(10);
		
		// set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetAutoPageBreak(TRUE, 5);
		
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setImageScale(1.25);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('dejavusans', '', 7);
		
				
		if(!empty($articles)) {
	       	$articlesArr = array_chunk($articles, 16);
			$i = 1;
			foreach ($articlesArr AS $articles) {
			
				// add a page
				$pdf->AddPage();
				
				// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
				// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
				
				// create some HTML content
				$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
				
				$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
					$html .= '<thead>';
						$html .= '<tr>';
							$html .= '<th width="5%" align="center">#</th>';
							$html .= '<th width="65%">Title</th>';
							$html .= '<th width="8%" align="center">Views</th>';
							$html .= '<th width="11%" align="center">Created Date</th>';
							$html .= '<th width="11%" align="center">Publish Date</th>';
						$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
						foreach ($articles AS $article) {
						$html .= '<tr>';
							$html .= '<td width="5%" align="center">'.$i.'</td>';
							$html .= '<td width="65%">';
								$html .= '<div style="font-size: 11px;">'.$article['title'].'</div><br/>';
								$categorylist = explode(',', $article['category_ids']);
								$html .= 'Category : '.getCategoryName($categorylist);
								if($article['tag_ids'] != '') {
								$taglist = explode(',', $article['tag_ids']);
								$html .= '<br/>Tags : '.getTagName($taglist);
								} else {
									$html .= '<br/>Tags : --- ';	
								}
							$html .= '</td>';
							$html .= '<td width="8%" align="center">'.$article['hits'].'</td>';
							$html .= '<td width="11%" align="center">'.date('Y-m-d', strtotime($article['created_date'])).'</td>';
							$html .= '<td width="11%" align="center">'.date('Y-m-d', strtotime($article['published_date'])).'</td>';
						$html .= '</tr>';
						$i++;
						}
					$html .= '</tbody>';
				$html .= '</table>';
		
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			
			}
		} else {
			
			// add a page
			$pdf->AddPage();
			
			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			// create some HTML content
			$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
			
			$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
				$html .= '<thead>';
					$html .= '<tr>';
						$html .= '<th width="5%" align="center">#</th>';
						$html .= '<th width="65%">Title</th>';
						$html .= '<th width="8%" align="center">Views</th>';
						$html .= '<th width="11%" align="center">Created Date</th>';
						$html .= '<th width="11%" align="center">Publish Date</th>';
					$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
					$html .= '<tr>';
						$html .= '<td colspan="5" width="100%" align="center">Sorry. No Records at the moment.</td>';
					$html .= '</tr>';
				$html .= '</tbody>';
			$html .= '</table>';
		
			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		}
		
				
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// reset pointer to the last page
		$pdf->lastPage();

		$pdf->Output('Yawmiyati-Articles-Report.pdf', 'I');
		exit;
		
	}
	
    
	public function download_categories_reports() {
		
		$where = ' 1';
  		$categories_reports_filter = $this->session->userdata('categories_reports_filter');
  		  		  		
		if($categories_reports_filter['status'] != '') {
			$where .= ' AND `status` = '.$categories_reports_filter['status'];
		}
		if($categories_reports_filter['start_date'] != '' && $categories_reports_filter['end_date'] != '') { 
			$where .= ' AND `created_date` BETWEEN "'.$categories_reports_filter['start_date'].'" AND "'.$categories_reports_filter['end_date'].'"';
		}
  		
  		/*if(!$this->ion_auth->in_group('admin')) {
	  		$where .= ' AND created_by = '.$current_user->id;
  		}*/
		  		
    	$categories = $this->fetchCategoryTree('0', '-', '', '', $where);
       	
		// create new PDF document
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		
		// set document information
		//$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetCreator('Yawmiyati');
		$pdf->SetAuthor('DOTCOM');
		$pdf->SetTitle('Yawmiyati - Categories Report');
		$pdf->SetSubject('Categories Report');
		$pdf->SetKeywords('Yawmiyati, Categories Report, DOTCOM');
		
		// set default header data
		//$pdf->SetHeaderData('http://localhost/yawmiyati/assets/site/images/logo.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
		$pdf->SetHeaderData(site_url().'assets/site/images/logo.png', 10, 'Yawmiyati - Categories Report', 'by YAWMIYATI - www.yawmiyati.com');
		
		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setHeaderFont(Array('helvetica', '', '10'));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setFooterFont(Array('helvetica', '', '10'));
		
		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetDefaultMonospacedFont('courier');
		
		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetMargins( 5, 20, 5);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetHeaderMargin(5);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetFooterMargin(10);
		
		// set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetAutoPageBreak(TRUE, 5);
		
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setImageScale(1.25);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('dejavusans', '', 7);
		
				
		if(!empty($categories)) {
	       	$categoriesArr = array_chunk($categories, 43);
			$i = 1;
			foreach ($categoriesArr AS $categories) {
			
				// add a page
				$pdf->AddPage();
				
				// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
				// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
				
				// create some HTML content
				$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
				
				$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
					$html .= '<thead>';
						$html .= '<tr>';
							$html .= '<th width="5%" align="center">#</th>';
							$html .= '<th width="80%">Title</th>';
							$html .= '<th width="15%" align="center">Total Articles</th>';
						$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
						foreach ($categories AS $category) {
							
						$total_articles = getArticlesByCatID($category['id'], ''); 
						$total_articles_count = count($total_articles);	
							
						$html .= '<tr>';
							$html .= '<td width="5%" align="center">'.$i.'</td>';
							$html .= '<td width="80%">'.$category['title'].'</td>';
							$html .= '<td width="15%" align="center">'.$total_articles_count.'</td>';
						$html .= '</tr>';
						$i++;
						}
					$html .= '</tbody>';
				$html .= '</table>';
		
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			
			}
		} else {
			
			// add a page
			$pdf->AddPage();
			
			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			// create some HTML content
			$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
			
			$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
				$html .= '<thead>';
					$html .= '<tr>';
						$html .= '<th width="5%" align="center">#</th>';
						$html .= '<th width="80%">Title</th>';
						$html .= '<th width="15%" align="center">Total Articles</th>';
					$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
					$html .= '<tr>';
						$html .= '<td colspan="3" width="100%" align="center">Sorry. No Records at the moment.</td>';
					$html .= '</tr>';
				$html .= '</tbody>';
			$html .= '</table>';
		
			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		}
		
				
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// reset pointer to the last page
		$pdf->lastPage();

		$pdf->Output('Yawmiyati-Categories-Report.pdf', 'I');
		exit;
		
	}
	
	
	public function download_videos_reports() {
		
		$videos_reports_filter = $this->session->userdata('videos_reports_filter');
		
		$where = ' WHERE 1';
  		  		
		if(!empty($videos_reports_filter['start_date']) && !empty($videos_reports_filter['end_date'])) { 
			$where .= ' AND `created_date` BETWEEN "'.$videos_reports_filter['start_date'].'" AND "'.$videos_reports_filter['end_date'].'"';
		}
  				
       	$qry ='Select * from `videos`'.$where; // select data from db
       				
       	$videos = $this->db->query($qry)->result_array();
       	
		// create new PDF document
		//$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
		
		// set document information
		//$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetCreator('Yawmiyati');
		$pdf->SetAuthor('DOTCOM');
		$pdf->SetTitle('Yawmiyati - Videos Report');
		$pdf->SetSubject('Videos Report');
		$pdf->SetKeywords('Yawmiyati, Videos Report, DOTCOM');
		
		// set default header data
		//$pdf->SetHeaderData('http://localhost/yawmiyati/assets/site/images/logo.png', PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 006', PDF_HEADER_STRING);
		$pdf->SetHeaderData(site_url().'assets/site/images/logo.png', 10, 'Yawmiyati - Videos Report', 'by YAWMIYATI - www.yawmiyati.com');
		
		// set header and footer fonts
		//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setHeaderFont(Array('helvetica', '', '10'));
		//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->setFooterFont(Array('helvetica', '', '10'));
		
		// set default monospaced font
		//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
		$pdf->SetDefaultMonospacedFont('courier');
		
		// set margins
		//$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetMargins( 5, 20, 5);
		//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetHeaderMargin(5);
		//$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
		$pdf->SetFooterMargin(10);
		
		// set auto page breaks
		//$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		$pdf->SetAutoPageBreak(TRUE, 5);
		
		// set image scale factor
		//$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
		$pdf->setImageScale(1.25);
		
		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    require_once(dirname(__FILE__).'/lang/eng.php');
		    $pdf->setLanguageArray($l);
		}
		
		// ---------------------------------------------------------
		
		// set font
		$pdf->SetFont('dejavusans', '', 7);
		
				
		if(!empty($videos)) {
	       	$videosArr = array_chunk($videos, 43);
			$i = 1;
			foreach ($videosArr AS $videos) {
			
				// add a page
				$pdf->AddPage();
				
				// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
				// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
				
				// create some HTML content
				$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
				
				$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
					$html .= '<thead>';
						$html .= '<tr>';
							$html .= '<th width="5%" align="center">#</th>';
							$html .= '<th width="85%">Title</th>';
							$html .= '<th width="10%" align="center">Views</th>';
						$html .= '</tr>';
					$html .= '</thead>';
					$html .= '<tbody>';
						foreach ($videos AS $video) {
					
						if($video['video_details'] != '') { $video_details = json_decode($video['video_details']); } else { $video_details = ''; }
						$html .= '<tr>';
							$html .= '<td width="5%" align="center">'.$i.'</td>';
							$html .= '<td width="85%">'.$video['title'].'</td>';
							if($video_details != '') {
								$html .= '<td width="10%" align="center">'.$video_details->today_views.'</td>';
							} else {
								$html .= '<td width="10%" align="center">0</td>';
							}
						$html .= '</tr>';
						$i++;
						}
					$html .= '</tbody>';
				$html .= '</table>';
		
				// output the HTML content
				$pdf->writeHTML($html, true, false, true, false, '');
			
			}
		} else {
			
			// add a page
			$pdf->AddPage();
			
			// writeHTML($html, $ln=true, $fill=false, $reseth=false, $cell=false, $align='')
			// writeHTMLCell($w, $h, $x, $y, $html='', $border=0, $ln=0, $fill=0, $reseth=true, $align='', $autopadding=true)
			
			// create some HTML content
			$html  = '<style>'.file_get_contents(site_url().'assets/administrator/css/pdf_report_css.css').'</style>';
			
			$html .= '<table width="100%" class="data-table" border="1" cellpadding="5">';
				$html .= '<thead>';
					$html .= '<tr>';
						$html .= '<th width="5%" align="center">#</th>';
						$html .= '<th width="85%">Title</th>';
						$html .= '<th width="10%" align="center">Views</th>';
					$html .= '</tr>';
				$html .= '</thead>';
				$html .= '<tbody>';
					$html .= '<tr>';
						$html .= '<td colspan="3" width="100%" align="center">Sorry. No Records at the moment.</td>';
					$html .= '</tr>';
				$html .= '</tbody>';
			$html .= '</table>';
		
			// output the HTML content
			$pdf->writeHTML($html, true, false, true, false, '');
		}
		
				
		// - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
		
		// reset pointer to the last page
		$pdf->lastPage();

		$pdf->Output('Yawmiyati-Videos-Report.pdf', 'I');
		exit;
		
	}

}

/* End of file dashboard.php */
/* Location: ./application/controllers/administrator/dashboard.php */