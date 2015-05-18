<?php
class WebService_model extends CI_Model
{
	function createTableLogs()
	{
		$this->db->query('CREATE TABLE `logs` (
			  `id` int(11) NOT NULL AUTO_INCREMENT,
			  `uri` varchar(255) NOT NULL,
			  `method` varchar(6) NOT NULL,
			  `params` text DEFAULT NULL,
			  `api_key` varchar(40) NOT NULL,
			  `ip_address` varchar(45) NOT NULL,
			  `time` int(11) NOT NULL,
			  `rtime` float DEFAULT NULL,
			  `authorized` tinyint(1) NOT NULL,
			  `response_code` smallint(3) NOT NULL,
			  PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	}

	function createTableDevices()
	{
		$this->db->query('CREATE TABLE `devices` (
			`id` int(11) NOT NULL AUTO_INCREMENT,
			`app_name` varchar(255) NOT NULL,
			`app_version` varchar(255) NOT NULL,
			`device_token` varchar(255) NOT NULL UNIQUE,
			`device_name` varchar(255) NOT NULL,
			`device_model` varchar(255) NOT NULL,
			`device_os` varchar(255) NOT NULL,
			`device_version` varchar(255) NOT NULL,
			`date_created` DATETIME DEFAULT NULL,
			`date_updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;');
	}

	function getAllRecords($table)
	{
		$query = $this->db->get($table);

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getContent($category_id,$select='')
	{
		$query = $this->db->select($select);
		$query = $this->db->where('catid',$category_id);
		$query = $this->db->get('qamp5_content');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getCategory($category_id,$select='')
	{
		$query = $this->db->select('id,title,path');
		$query = $this->db->where('id',$category_id);
		$query = $this->db->get('qamp5_categories');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getUser($user_id,$select='')
	{
		$query = $this->db->select('name');
		$query = $this->db->where('id',$user_id);
		$query = $this->db->get('qamp5_users');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getSubCategories($parent_id)
	{
		$this->db->select('id,parent_id,level,path,title,published');
		$this->db->where('parent_id',$parent_id);
		$this->db->where('published','1');
		$query = $this->db->get('qamp5_categories');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getArticles($catid)
	{
		$this->db->select('qamp5_content.id, qamp5_content.title, qamp5_content.introtext, qamp5_content.created, qamp5_users.name');
		$this->db->from('qamp5_content');
		$this->db->join('qamp5_users','$qamp5_users.id = qamp5_content.created_by','left');
		$this->db->order_by("qamp5_content.created", "desc");
		$this->db->where('qamp5_content.catid',$catid);
		$this->db->where('state','1');
		$query = $this->db->get(); 

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getGalleryList($id)
	{
		$this->db->select('id,ordering,name,alias,profile,parent,date');
		$this->db->where('parent',$id);
		$this->db->where('published','1');
		$query = $this->db->get('qamp5_igallery');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function getImagesList($id)
	{
		$this->db->select('id,gallery_id,ordering,date,filename,description');
		$this->db->where('gallery_id',$id);
		$this->db->where('published','1');
		$query = $this->db->get('qamp5_igallery_img');

		if($query->num_rows())
		{
			return $query->result_array();
		}
		else
		{
			return FALSE;
		}
	}

	function registerDevice($data)
	{
		$this->db->set($data);
		$this->db->insert('devices');
		return $query = $this->db->insert_id();
	}
}
?>