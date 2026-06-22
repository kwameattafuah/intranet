<?php  

  class Login
  {
    protected $func;
    public $credentials = [];

    function __construct()
    {
      $this->func = new myFunc;
    }

    public function login($username,$password){
      $credentials = $this->func->myQuery("SELECT * FROM staff WHERE username = ? OR email = ?","ss",array($username,$username),"fetch");
      if (password_verify($password,$credentials['password'])){
        return $credentials;
      }else{
        return false;
      }
    }
  }
?>