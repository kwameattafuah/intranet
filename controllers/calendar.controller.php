<?php  

	class Cal
	{
		protected $func;
		public $credentials = [];

		function __construct()
		{
			$this->func = new myFunc;
		}

		public function login($username,$password){
			$credentials = $this->func->myQuery("SELECT * FROM clients WHERE client_username = ? OR client_code = ?","ss",array($username,$username),"fetch");
			if (password_verify($password,$credentials['client_password'])){
				if ($credentials['active'] == 1)
					return $credentials;
				elseif ($credentials['active'] == 0)
					return "inactive";
				else
					return "suspended";
			}else{
				return false;
			}
		}
	}
?>