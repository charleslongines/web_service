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
        if(!$this->db->table_exists('devices'))
        {
        	$this->webservice_model->createTableDevices();
        }

        $this->image_domain = 'src="http://qc-intranet-training.stluke.com.ph/';
        $this->href_domain = 'href="http://qc-intranet-training.stluke.com.ph/';
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
		$content = $this->webservice_model->getArticles('624');
		$category = $this->webservice_model->getCategory('624');

		foreach($content as $key => $value)
		{
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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

	function qpsContactUs_get()
	{
		// For Contact's SubCategory cat_id = 336
		// $contact_us = $this->webservice_model->getSubCategories($id);

		//Direct Contacts
		$mobile = $this->qpsMobile_get('635');
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
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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

	function qpsDownloadableMaterials_get()
	{
		// DOWNLOADABLE MATERIALS = 106
		$result = $this->webservice_model->getArticles('106');
		$category = $this->webservice_model->getCategory('106');

		foreach($result as $key => $value)
		{
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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
		$content = $this->webservice_model->getArticles('373');
		$category = $this->webservice_model->getCategory('373');
		foreach($content as $key => $value)
		{
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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
		$content = $this->webservice_model->getArticles('632');
		$category = $this->webservice_model->getCategory('632');
		foreach($content as $key => $value)
		{
			if(strpos($value['introtext'], 'src="') || strpos($value['introtext'], 'href="'))
			{
				$value['introtext'] = str_replace('src="', $this->image_domain, $value['introtext']);
				$value['introtext'] = str_replace('href="', $this->href_domain, $value['introtext']);
			
			}

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

	function qpsGalleryList_get()
	{
		// QPS GALLERY/ALBUM LIST = 19
		$gallery = $this->webservice_model->getGalleryList('19');

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
		$original_path = 'http://qc-intranet-training.stluke.com.ph/images/igallery/original/';
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

	function registerDevice_post()
	{
		unset($_POST['submit']);
		$data = $_POST;
		$data['date_created'] = date('Y-m-d');
		$data['date_updated'] = date('Y-m-d H:i:s');

		$result['result'] = $this->webservice_model->registerDevice($data);
		$this->response($result);
	}
}
?>