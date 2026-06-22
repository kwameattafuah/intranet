<?php  
	/**
	* 
	*/
	class Forum
	{
		protected $func;
		public $recents = [];
		public $mine = [];

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function index($id){
			$result = $this->func->myQuery("SELECT f.*, s.full_name as membername FROM forum_topic f JOIN staff s ON s.id = f.initiator_id WHERE 1 = ? ORDER BY id DESC","i",array(1),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$recents[] = $row;
			else
				$recents = false;

			$result = $this->func->myQuery("SELECT f.* FROM forum_topic f WHERE f.initiator_id = ? ORDER BY id DESC","s",array($id),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$mine[] = $row;
			else
				$mine = false;

			return [$recents,$mine];
		}

		public function search($id){
			$result = $this->func->myQuery("SELECT f.*, s.full_name as membername FROM forum_topic f JOIN staff s ON s.id = f.initiator_id WHERE f.topic LIKE CONCAT('%', ?, '%') ORDER BY id DESC","s",array($id),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$recents[] = $row;
			else
				$recents = false;

			return $recents;
		}

		public function view($id){
			$lead = $this->func->myQuery("SELECT f.*, s.full_name as membername FROM forum_topic f JOIN staff s ON s.id = f.initiator_id WHERE f.id = ?","i",array($id),"fetch");
			//get replies
			$result = $this->func->myQuery("SELECT f.*, s.full_name as membername FROM forum f JOIN forum_topic t ON f.topic = t.id AND t.request != f.id JOIN staff s ON s.id = f.member_id WHERE f.topic = ? ORDER BY id DESC","i",array($id),"result");

			if ($result->num_rows > 0)
				foreach ($result as $row)
					$recents[] = $row;
			else
				$recents = false;

			return[$lead,$recents];
		}

		// new comment
		public function comment($id,$topic,$comment){
			return $this->func->myQuery("INSERT INTO forum (member_id,topic,comment) VALUES (?,?,?)", "sis", array($id,$topic,$comment), "action");
		}	

		// new topic
		public function new($id,$topic,$comment){
			return $this->func->myQuery("INSERT INTO forum_topic (initiator_id,topic,request) VALUES (?,?,?)", "sss", array($id,$topic,$comment), "action");
		}					
	}
?>