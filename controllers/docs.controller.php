<?php  
	/**
	* controller class
	*/
	class Docs
	{
		protected $func;
		public $docs = [];
		public $cats = [];

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function index(){
			$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s, shared_category c WHERE s.type = c.id AND 1 = ? ","i",array(1),"result");
				
			if($result->num_rows > 0)
				foreach ($result as $row)
					$docs[] = $row;
			else
				$docs = false;

			$result = $this->func->myQuery("SELECT * FROM shared_category WHERE 1 = ?","i",array(1),"result");
				
			if($result->num_rows > 0)
				foreach ($result as $row)
					$cats[] = $row;
			else
				$cats = false;

			return [$cats,$docs];
		}

		public function search($type,$search){
			if(!is_null($search))
				// get doc
				$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s JOIN shared_category c ON s.type = c.id WHERE s.name LIKE CONCAT('%', ?, '%') AND s.type = ?","si",array($search,$type),"result");
			else
				$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s JOIN shared_category c ON s.type = c.id WHERE s.type = ?","i",array($type),"result");			

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$docs[] = $row;
			else
				$docs = false;		

			return $docs;
		}	

		public function docsfetch($search){
			if(!is_null($search))
				// get doc
				$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s JOIN shared_category c ON s.type = c.id WHERE s.name LIKE CONCAT('%', ?, '%')","s",array($search),"result");
			else
				$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s JOIN shared_category c ON s.type = c.id WHERE 1 = ?","i",array(1),"result");			

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$docsf[] = $row;
			else
				$docsf = false;		

			return $docsf;
		}

		public function adddoc($doc,$name,$category,$modifier){
			$cat = $this->func->myQuery("SELECT * FROM shared_category WHERE id = ?","i",array($category),"fetch");
			if ($this->func->docUpload('../docs/shared/'.$cat['name'].'/',$doc['name'],$doc['type'],$doc['tmp_name'],$doc['size']) === true) {
				return $this->func->myQuery("INSERT INTO shared_docs (source,name,type,modifier) VALUES (?,?,?,?)","ssis",array($doc['name'],$name,$category,$modifier),"action");
			}	
		}

		public function editdoc($name,$modifier,$id){
			return $this->func->myQuery("UPDATE shared_docs set name =?, modifier = ? WHERE id = ? ","ssi",array($name,$modifier,$id),"action");		
		}		

		public function delete($id){
			$result = $this->func->myQuery("SELECT s.*, c.name as category FROM shared_docs s JOIN shared_category c ON s.type = c.id WHERE s.id = ?","i",array($id),"fetch");

			if (unlink("../docs/shared/".$result['category'].'/'.$result['source']))
				return $this->func->myQuery("DELETE FROM shared_docs WHERE id = ?", "i", array($id), "action");
			else
				return false;	
		}									

	}
?>

