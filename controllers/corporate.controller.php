<?php  
	/**
	* controller class
	*/
	class Mgt
	{
		// define variables
		protected $func;
		public $person = array();
		public $directors = array();

		function __construct()
		{
			$this->func = new myFunc;
		}

		// default view
		public function index(){
			// query result
			$result = $this->func->myQuery("SELECT * FROM management WHERE rank < ?","i",array(1),"result");

			// check returned result if empty
			if ($result->num_rows > 0)
				foreach ($result as $row)
					$directors[] = $row;
			else
				$directors = false;

			// query result
			$result = $this->func->myQuery("SELECT * FROM management WHERE rank > ?","i",array(-1),"result");

			// check returned result if empty
			if ($result->num_rows > 0)
				foreach ($result as $row)
					$person[] = $row;
			else
				$person = false;


			return [$directors,$person];
		}

	}
?>