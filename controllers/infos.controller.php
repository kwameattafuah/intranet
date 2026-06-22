<?php  
	/**
	* controller class
	*/
	class Docs
	{
		protected $func;
		public $infos = [];
		public $newx = [];

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function index(){
			$result = $this->func->myQuery("SELECT * FROM notices_info WHERE 7 = ?","i",array(7),"result");
				
			if($result->num_rows > 0)
				foreach ($result as $row)
					$infos[] = $row;
			else
				$infos = false;

			$result = $this->func->myQuery("SELECT * FROM news WHERE 7 = ?","i",array(7),"result");

			if($result->num_rows > 0)
				foreach ($result as $row)
					$newx[] = $row;
			else
				$newx = false;

			return [$newx,$infos];
		}

		public function infosearch($search){
			// get doc
			$result = $this->func->myQuery("SELECT * FROM notices_info WHERE name LIKE CONCAT('%', ?, '%') OR modifier LIKE CONCAT('%', ?, '%') ORDER BY id DESC","ss",array($search,$search),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$docs[] = $row;
			else
				$docs = false;		

			return $docs;
		}	

		public function newssearch($search){
			// get doc
			$result = $this->func->myQuery("SELECT * FROM news WHERE headline LIKE CONCAT('%', ?, '%') OR content LIKE CONCAT('%', ?, '%') ORDER BY id DESC","ss",array($search,$search),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$news[] = $row;
			else
				$news = false;		

			return $news;
		}

		public function fetch(){
			// get all docs
			$result = $this->func->myQuery("SELECT * FROM notices_info WHERE 7 = ? ORDER BY id ASC","i",array(7),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$docs[] = $row;
			else
				$docs = false;		

			return $docs;
		}	

		public function infoadd($doc,$name,$type,$modifier){
			if ($this->func->docUpload('../docs/information/',$doc['name'],$doc['type'],$doc['tmp_name'],$doc['size']) === true) {
				return $this->func->myQuery("INSERT INTO notices_info (source,name,modifier) VALUES (?,?,?)","sss",array($doc['name'],$name,$modifier),"action");
			}	
		}

		public function editdoc($name,$modifier,$id){
			return $this->func->myQuery("UPDATE notices_info set name =?, modifier = ? WHERE id = ? ","ssi",array($name,$modifier,$id),"action");		
		}		

		public function delete($id){
			$result = $this->func->myQuery("SELECT source FROM notices_info WHERE id = ?","i",array($id),"fetch");

			if (unlink("../docs/information/".$result['source']))
				return $this->func->myQuery("DELETE FROM notices_info WHERE id = ?", "i", array($id), "action");
			else
				return false;	
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

	}
?>

