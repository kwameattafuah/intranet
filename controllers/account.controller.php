<?php  
	/*
	*/
	class Account
	{
		protected $func;

		function __construct()
		{
			$this->func = new myFunc;
		}


	// ACCOUNT DETAILS FETCH
		public function index($id){
			$account = $this->func->myQuery("SELECT * FROM staff WHERE id = ?","s",array($id),"fetch");
			return $account;
		}


	// ACCOUNT DETAILS UPDATE
		public function update($name,$email,$username,$password,$id){
			$result = $this->func->myQuery("SELECT * FROM staff WHERE id = ?","s",array($id),"fetch");

			if (!password_verify($password,$result['password']))
				return "password";

			return $this->func->myQuery("UPDATE staff SET full_name = ?, email = ?, username = ? WHERE id = ?","ssss",array($name,$email,$username,$id),"action");
		}


	// PASSWORD CHANGE
		public function pass($old,$new,$id){
			$result = $this->func->myQuery("SELECT * FROM staff WHERE id = ?","s",array($id),"fetch");

			if (!password_verify($old,$result['password']))
				return "password";

			$new = password_hash($new, PASSWORD_DEFAULT);

			return $this->func->myQuery("UPDATE staff SET password= ? WHERE id = ?","ss",array($new,$id),"action");
		}
	}
?>