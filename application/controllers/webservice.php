<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class WebService extends REST_Controller
{
	function __construct()
	{
        parent::__construct();
        $this->load->model('webservice_model');
        if(!$this->db->table_exists('logs'))
		{
        	$this->webservice_model->createTableLogs();
        }
	}

	function index_get()
	{
		$this->load->view('web_service_list_view');
	}

	function getAllRecords_get($table)
	{
		$data = $this->webservice_model->getAllRecords($table);
		$this->response($data);
	}

	function qps_get()
	{
		// parent_id is QPS, category_id = 92
		$qps = $this->webservice_model->getQpsCategoryList();
		$this->response($qps);
	}

	function qpsProgramList_get()
	{
		$data['programs'] = array(array('id'=>"93",
							'parent_id'=>"92",
							'level'=>"2",
							'path'=>"qps/hpic",
							'title'=>"HPIC",
							'published'=>"1"),
					array('id'=>"96",
						'parent_id'=>"92",
						'level'=>"2",
						'path'=>"qps/eoc",
						'title'=>"EOC",
						'published'=>"1"),
					array('id'=>"297",
						'parent_id'=>"92",
						'level'=>"2",
						'path'=>"qps/patient-safety-walkrounds",
						'title'=>"Patient Safety Walkrounds",
						'published'=>"1"),
					array('id'=>"102",
						'parent_id'=>"92",
						'level'=>"2",
						'path'=>"qps/dcc",
						'title'=>"DCC",
						'published'=>"1"),
					array('id'=>"95",
						'parent_id'=>"92",
						'level'=>"2",
						'path'=>"qps/qmt-meeting",
						'title'=>"QMT",
						'published'=>"1"),
					array('id'=>"316",
						'parent_id'=>"92",
						'level'=>"2",
						'path'=>"qps/patient-and-family-engagement-advisor",
						'title'=>"Patient and Family Engagement",
						'published'=>"1")
					);
		// echo json_encode($data);
		$this->response($data);
	}

	function qpsNews_get()
	{
		// catid: 330, path: qps/news
		$content = $this->webservice_model->getArticles('330');
		$category = $this->webservice_model->getCategory('330');

		foreach($content as $key => $value)
		{
			$news[$key] = array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}

		$data['news'] = $news;
		$this->response($data);
	}

	function qpsAnnouncements_get()
	{
		// catid: 107, path: qps/announcement
		$content = $this->webservice_model->getArticles('107');
		$category = $this->webservice_model->getCategory('107');

		foreach($content as $key => $value)
		{
			$announcement[$key] = array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}

		$data['announcement'] = $announcement;
		$this->response($data);
	}

	function qpsSubCategories_get($parent_id)
	{
		$data['sub_categories'] = $this->webservice_model->getSubCategories($parent_id);
		$this->response($data);
	}

	function qpsArticle_get($catid)
	{
		$result = $this->webservice_model->getArticles($catid);
		$category = $this->webservice_model->getCategory($catid);

		foreach($result as $key => $value)
		{
			$article[$key] =  array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}
		$data['article'] = $article;
		$this->response($data);
	}

	function qpsContactUs_get($id)
	{
		// For Contact's SubCategory cat_id = 335
		// $contact_us = $this->webservice_model->getSubCategories($id);

		//Direct Contacts
		$mobile = $this->qpsMobile_get($id);
		$dr_dondi= array(array('title'=>'Dr. Dondi',
					'email' => 'acdizon@stluke.com.ph',
					'email_subject' => 'Dear Dr. Dondi',
					'user_email' => 'assoc-annonymous@stluke.com.ph',
					'user_from_name' => 'QC Intranet Auto Mail'));
		$dr_tapia = array(array('title'=>'Dr. Tapia',
					'email' => 'cltapia@stluke.com.ph',
					'email_subject' => 'Dear Dr. Tapia',
					'user_email' => 'assoc-annonymous@stluke.com.ph',
					'user_from_name' => 'QC Intranet Auto Mail'));
		$data['contact_us'] = array_merge($mobile,$dr_dondi,$dr_tapia);
		$this->response($data);
	}

	function qpsMobile_get($id)
	{
		// QPS MOBILE = 336
		$result = $this->webservice_model->getArticles($id);
		$category = $this->webservice_model->getCategory($id);

		foreach($result as $key => $value)
		{
			$mobile[$key] =  array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}
		// $data['mobile'] = $mobile;
		// $this->response($data);
		return $mobile;
	}

	function qpsDownloadableMaterials_get($id)
	{
		// DOWNLOADABLE MATERIALS = 106
		$result = $this->webservice_model->getArticles($id);
		$category = $this->webservice_model->getCategory($id);

		foreach($result as $key => $value)
		{
			$downloadable[$key] =  array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}
		$data['downloadable'] = $downloadable;
		$this->response($data);
	}

	function qpsTips_get()
	{
		// TIPS = 327
		$content = $this->webservice_model->getArticles('327');
		$category = $this->webservice_model->getCategory('327');
		foreach($content as $key => $value)
		{
			$tips[$key] = array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}
		$data['tips'] = $tips;
		// echo json_encode($data);
		$this->response($data);
	}

	function qpsExperience_get()
	{
		// EXPERIENCE = 334
		$content = $this->webservice_model->getArticles('334');
		$category = $this->webservice_model->getCategory('334');
		foreach($content as $key => $value)
		{
			$experience[$key] = array('id' => $value['id'],
								'title' => $value['title'],
								'content' => $value['introtext'],
								'date_created' => $value['created'],
								'created_by' => $value['name'],
								'category' => $category[0]);
		}
		$data['experience'] = $experience;
		// echo json_encode($data);
		$this->response($data);
	}

	function qpsGalleryList_get($id)
	{
		// QPS GALLERY/ALBUM LIST = 19
		$gallery = $this->webservice_model->getGalleryList($id);

		foreach($gallery as $key => $value)
		{
			$gallery[$key]['image'] = $this->getImagesList_get($value['id']);
		}

		$data['gallery'] = $gallery;
		$this->response($data);
	}

	function getImagesList_get($id)
	{
		$image_list = $this->webservice_model->getImagesList($id);

		foreach($image_list as $key => $value)
		{
			$image_list[$key]['path'] = $this->getAlbumPath_get($value['id'],$value['filename']);
		}

		return $image_list;
	}

	function getAlbumPath_get($image_id, $filename)
	{
		$original_path = '/images/igallery/original/';
		$first_digit = $image_id[0];
		$first_digit_range = $first_digit+1;

		if(strlen($image_id) < 3)
		{
			$album_range = $first_digit.'-'.$first_digit.'00/';
		}
		else
		{
			$album_range = $first_digit.'01-'.$first_digit_range.'00/';
		}

		$album_path = $original_path.$album_range.$filename;
		return stripslashes($album_path);
	}
}
?>