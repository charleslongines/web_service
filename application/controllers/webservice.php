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

	function getAllRecords($table)
	{
		$data = $this->webservice_model->getAllRecords($table);
		echo json_encode($data);
	}

	function qps()
	{
		// parent_id is QPS, category_id = 92
		$qps = $this->webservice_model->getQpsCategoryList();
		echo json_encode($qps);
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
		$content = $this->webservice_model->getQpsNewsList();
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
		// echo json_encode($data);
		$this->response($data);
	}

	function qpsAnnouncements_get()
	{
		// catid: 107, path: qps/announcement
		$content = $this->webservice_model->getQpsAnnouncementList();
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
		// echo json_encode($data);
		$this->response($data);
	}

	function qpsSubCategories_get($parent_id)
	{
		$sub_categories = $this->webservice_model->getSubCategories($parent_id);
		$data['sub_categories'] = $sub_categories;
		$this->response($data);
	}

	function qpsArticle_get($catid)
	{
		$result = $this->webservice_model->getArticle($catid);
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
}
?>