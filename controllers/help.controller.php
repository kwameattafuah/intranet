<?php 
	/**
	* help controller class
	*/
	class Help
	{
		protected $func;
		public $infos = [];

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function index(){
			// get info
			$result = $this->func->myQuery("SELECT * FROM tips WHERE 1 = ? ORDER BY heading ASC","i",array(1),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$infos[] = $row;
			else
				$infos = false;		

			return $infos;
		}

		public function search($search){
			// get info
			$result = $this->func->myQuery("SELECT * FROM tips WHERE heading LIKE CONCAT('%', ?, '%') OR body LIKE CONCAT('%', ?, '%') ORDER BY heading ASC","ss",array($search,$search),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$infos[] = $row;
			else
				$infos = false;		

			return $infos;
		}

		// send request
		public function send($id,$name,$extension,$subject,$message){
			date_default_timezone_set("Africa/Accra");
			$date = date("Y-m-d H:i:s");

			return $this->func->myQuery("INSERT INTO help(date_created,requester,rName,rExt,subject,description) VALUES (?,?,?,?,?,?)", "ssssss", array($date,$id,$name,$extension,$subject,$message), "action");
		}

		public function view($id){
			// get info
			return $this->func->myQuery("SELECT * FROM tips WHERE id = ?","i",array($id),"fetch");
		}

		public function requestfetch(){
			// get requests
			$result = $this->func->myQuery("SELECT * FROM help WHERE 1 = ?","i",array(1),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$infos[] = $row;
			else
				$infos = false;		

			return $infos;			
		}

		public function jobadd($name,$id){
			// assign
			return $this->func->myQuery("UPDATE help SET treated = ? WHERE id = ?", "si", array($name,$id), "action");
		}

		public function requestdelete($id){
			// get info
			return $this->func->myQuery("DELETE FROM help WHERE id = ?","i",array($id),"action");
		}																	
		
	}

?>