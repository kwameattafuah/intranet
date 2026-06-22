<?php  
	/**
	* Update controller class
	*/
	class Update
	{
		protected $func;
		public $media = array();
		public $alerts = array();
		public $news = array();
		public $links = array();
		public $depts = array();
		public $docs = array();
		public $cats = array();
		public $roles = array();

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function settopstory($id){
			if ($this->func->myQuery("UPDATE news SET topstory = ?", "i", array(0), "action"))
				return $this->func->myQuery("UPDATE news SET topstory = ? AND visible = ? WHERE id = ?", "iii", array(1,1,$id), "action");
		}

		public function newsedit($headline,$content,$url,$visible,$top,$id){
			if ($top == 1){
				self::settopstory($id);
			}
			return $this->func->myQuery("UPDATE news set headline =?, content = ?, url = ?, visible = ?, topstory = ? WHERE id = ? ","sssiii",array($headline,$content,$url,$visible,$top,$id),"action");		
		}		

		public function newsadd($files,$headline,$content,$url,$visible,$top){
		$images = $this->func->reArrayFiles($files);
		if ($this->func->myQuery("INSERT INTO news (headline,content,url,visible,topstory) VALUES (?,?,?,?,?)","sssii",array($headline,$content,$url,$visible,$top),"action")) {
			$result = $this->func->myQuery("SELECT id FROM news WHERE headline = ?","s",array($headline),"fetch");
			$id = $result['id'];
			if ($top == 1){
				self::settopstory($id);
			}			
			foreach ($images as $pic) {
				if ($this->func->imgUpload('../media/news_gallery/',$pic['name'],$pic['tmp_name'],$pic['size']) === true) {
					$check = $this->func->myQuery("INSERT INTO news_galleries (caption,frame,news_id,gallery_category) VALUES (?,?,?,?)","ssii",array($headline,$pic['name'],$id,1),"action");
				}
				if ($check === false)
					return false;
			}
				return true;					
			}
		}

		public function newsdelete($id){
			$result = $this->func->myQuery('SELECT * FROM news_galleries WHERE news_id = ?', "i", array($id),"result");

			if($result->num_rows > 0)
		        foreach ($result as $row)
		          $images[] = $row;
		      else
	        $images = false;

	    	if ($images !== false){
				foreach ($images as $pic) {
					if ($pic['gallery_category'] == 1) {
						if (unlink("../media/news_gallery/".$pic['frame']))
							$check = $this->func->myQuery("DELETE FROM news_galleries WHERE id = ?", "i", array($pic['id']), "action");
					}else{
						$check = $this->func->myQuery("DELETE FROM news_galleries WHERE id = ?", "i", array($pic['id']), "action");
					}	

					if ($check === false)
						return false;
				}
					return $this->func->myQuery("DELETE FROM news WHERE id = ?", "i", array($id), "action");					
			}	
		}

		public function newspicdelete($id){
			$result = $this->func->myQuery('SELECT * FROM news_galleries WHERE id = ?', "i", array($id),"fetch");

			if (unlink("../media/news_gallery/".$result['frame']))
				return $this->func->myQuery("DELETE FROM news_galleries WHERE id = ?", "i", array($id), "action");
			else
				return false;
		}		

		public function linkedit($name,$url,$id){
			return $this->func->myQuery("UPDATE links set name =?, url = ? WHERE id = ? ","ssi",array($name,$url,$id),"action");		
		}

		public function linkadd($name,$url){
			return $this->func->myQuery("INSERT INTO links (name,url) VALUES (?,?)","ss",array($name,$url),"action");		
		}

		public function linkdelete($id){
			return $this->func->myQuery("DELETE FROM links WHERE id = ?", "i", array($id), "action");
		}	

	    public function linksfetch(){
	      // get links
	      $result = $this->func->myQuery("SELECT * FROM links WHERE 1 = ?","i",array(1),"result");

	      if($result->num_rows > 0)
	        foreach ($result as $row)
	          $links[] = $row;
	      else
	        $links = false;

	    	return $links;
	    }	

	    public function newsfetch(){
	      // get news
	      $result = $this->func->myQuery("SELECT * FROM news WHERE 1 = ? ORDER BY id DESC","i",array(1),"result");

	      if($result->num_rows > 0)
	        foreach ($result as $row)
	          $news[] = $row;
	      else
	          $news = false;

	      return $news;
	    }	

	    public function fetch_newsitem($id){
	      return $this->func->myQuery("SELECT * FROM news WHERE id = ?","i",array($id),"fetch");  
	    }	    

	    public function fetch_newspics($id){
	      // get news pictures
	      $result = $this->func->myQuery("SELECT * FROM news_galleries WHERE news_id = ? AND gallery_category = ?","ii",array($id,1),"result");

	      if($result->num_rows > 0)
	        foreach ($result as $row)
	          $news[] = $row;
	      else
	          $news = false;

	      return $news;
	    }	    

	    public function alertsfetch(){
	      // get alerts
	    	$result = $this->func->myQuery("SELECT * FROM infos WHERE 1 =  ?", "i", array(1), "result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$alerts[] = $row;
			else
				$alerts = false; 

			return $alerts;
		}

	    public function mgtsfetch(){
	      // get management
	    	$result = $this->func->myQuery("SELECT * FROM management WHERE 1 =  ?", "i", array(1), "result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$mgts[] = $row;
			else
				$mgts = false; 

			return $mgts;
		}		

		public function editalert($subject,$details,$authority,$id){
			return $this->func->myQuery("UPDATE infos set subject =?, details = ?, authority = ? WHERE id = ? ","sssi",array($subject,$details,$authority,$id),"action");		
		}

		public function addalert($subject,$details,$authority){
			return $this->func->myQuery("INSERT INTO infos (subject,details,authority) VALUES (?,?,?)","sss",array($subject,$details,$authority),"action");		
		}

		public function alertdelete($id){
			return $this->func->myQuery("DELETE FROM infos WHERE id = ?", "i", array($id), "action");
		}						   					
		
	    public function usersfetch(){
	      // get users
	    	$result = $this->func->myQuery("SELECT * FROM staff WHERE role !=  ?", "s", array("PROG"), "result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$users[] = $row;
			else
				$users = false; 

	      // get depts
	    	$result = $this->func->myQuery("SELECT * FROM departments WHERE 1 =  ?", "i", array(1), "result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$depts[] = $row;
			else
				$depts = false; 	

	      // get roles
	    	$result = $this->func->myQuery("SELECT * FROM roles WHERE 1 =  ?", "i", array(1), "result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$roles[] = $row;
			else
				$roles = false;						

			return [$users,$depts,$roles];
		}

		public function resetuser($fullname,$email,$username,$staff_id,$passphrase,$dept,$role,$id){
			$password = password_hash($passphrase,PASSWORD_DEFAULT);
			return $this->func->myQuery("UPDATE staff set full_name =?, email = ?, username = ?,staff_id = ?,password = ?,dept = ?,role = ? WHERE id = ? ","ssssssss",array($fullname,$email,$username,$staff_id,$password,$dept,$role,$id),"action");		
		}

		public function adduser($fullname,$email,$username,$staff_id,$passphrase,$dept,$role){
			$password = password_hash($passphrase,PASSWORD_DEFAULT);
			return $this->func->myQuery("INSERT INTO staff (full_name,email,username,staff_id,password,dept,role) VALUES (?,?,?,?,?,?,?)","sssssss",array($fullname,$email,$username,$staff_id,$password,$dept,$role),"action");		
		}

		public function edituser($fullname,$email,$username,$staff_id,$dept,$role,$id){
			return $this->func->myQuery("UPDATE staff set full_name =?, email = ?, username = ?, staff_id = ?, dept = ?,role = ? WHERE id = ? ","sssssss",array($fullname,$email,$username,$staff_id,$dept,$role,$id),"action");		
		}		

		public function userdelete($id){
			return $this->func->myQuery("DELETE FROM staff WHERE id = ?", "i", array($id), "action");
		}

		public function addmgt($name,$position,$rank){
			return $this->func->myQuery("INSERT INTO management (name,position,rank) VALUES (?,?,?)","sss",array($name,$position,$rank),"action");		
		}

		public function editmgt($name,$position,$rank,$id){
			return $this->func->myQuery("UPDATE management set name =?, position = ?, rank = ? WHERE id = ? ","sssi",array($name,$position,$rank,$id),"action");		
		}		

		public function mgtdelete($id){
			return $this->func->myQuery("DELETE FROM management WHERE id = ?", "i", array($id), "action");
		}

	    public function viewNewspic($id){
	      // get news image
	      return $this->func->myQuery("SELECT * FROM news_galleries WHERE id = ?","i",array($id),"fetch");
	    } 				

	}
?>