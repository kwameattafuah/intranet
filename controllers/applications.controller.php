<?php  
	/**
	* transaction controller class
	*/
	class Transactions
	{

		protected $func;
		public $transactions = [];
		
		function __construct()
		{
			$this->func = new myFunc;
		}

		public function index($id){
			// query transactions
			$result = $this->func->myQuery("SELECT * FROM parking_transactions WHERE client_code = ? AND cleared = ? ORDER BY id DESC", "ss", array($id,'0'), "result");

			// put results in array
			if($result->num_rows > 0)
				foreach ($result as $row)
					$transactions[] = $row;
			else
				$transactions = false;

			return $transactions;
		}

		public function search($id,$search){
			// query transaction
			$result = $this->func->myQuery("SELECT * FROM parking_transactions WHERE (customer_username LIKE CONCAT('%', ?, '%') OR vehicle_make LIKE CONCAT('%', ?, '%') OR vehicle_model LIKE CONCAT('%', ?, '%') OR vehicle_number LIKE CONCAT('%', ?, '%') OR parked_by LIKE CONCAT('%', ?, '%') OR fetched_by LIKE CONCAT('%', ?, '%')) AND client_code = ? ORDER BY id DESC","sssssss",array($search,$search,$search,$search,$search,$search,$id),"result");

			// put results in array
			if($result->num_rows > 0)
				foreach ($result as $row)
					$transactions[] = $row;
			else
				$transactions = false;

			return $transactions;
		}

	}
?>