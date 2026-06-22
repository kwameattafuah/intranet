<?php  

  class Login
  {
    protected $func;
    public $credentials = [];

    function __construct()
    {
      $this->func = new myFunc;
    }

    public function login($mail,$pass){ 
      $credentials = $this->func->myQuery("SELECT * FROM ldc_register WHERE email = ? OR phone = ?","ss",array($mail,$mail),"fetch");
      if (password_verify($pass,$credentials['password'])){
        return $credentials;
      }else{
        return false;
      }
    }

    public function authenticate($user,$password){
      $credentials = $this->func->myQuery("SELECT * FROM ldc_staff WHERE email = ? OR phone = ?","ss",array($user,$user),"fetch");
      if (password_verify($password,$credentials['password'])){
        return $credentials;
      }else{
        return false;
      }
    }

    public function register($name,$sex,$email,$phone,$position,$sid,$course,$dept,$pass,$cpass){
      if ( $this->func->myQuery("SELECT * FROM ldc_history WHERE individual_id = (SELECT id FROM ldc_register WHERE Staff_id = ?)","s",array($sid),"fetch") ) {
        return "present";
      } else { 
        date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

        if ($pass !== $cpass) {
          return "pass";
        }else{
          $password = password_hash($pass,PASSWORD_DEFAULT);
          if ( $this->func->myQuery("INSERT INTO ldc_register (name,sex,email,phone,position,staff_id,dept_id,password,date_reg) VALUES (?,?,?,?,?,?,?,?,?)","sssssssss",array($name,$sex,$email,$phone,$position,$sid,$dept,$password,$dated),"action") !== false ) {
              $pid = $this->func->myQuery("SELECT * FROM ldc_register WHERE staff_id = ?","s",array($sid),"fetch");
			  $pid = $pid['id'];
			  if ($this->func->myQuery("INSERT INTO ldc_history (course_id,individual_id,date_reg) VALUES (?,?,?)", "iss", array($course,$pid,$dated), "action") !== false) {
              return $this->func->myQuery("UPDATE ldc_courses SET registered = registered + 1 where id = ?", "i", array($course), "action");
            }
          }
        }
      }
    }

    public function index($id){
      $account = $this->func->myQuery("SELECT * FROM ldc_staff WHERE id = ?","s",array($id),"fetch");
      return $account;
    }

    public function update($name,$position,$phone,$password,$id){
      $result = $this->func->myQuery("SELECT * FROM ldc_staff WHERE id = ?","s",array($id),"fetch");

      if (!password_verify($password,$result['password']))
        return "password";

      return $this->func->myQuery("UPDATE ldc_staff SET name = ?, position = ?, phone = ? WHERE id = ?","ssss",array($name,$position,$phone,$id),"action");
    }

    public function pass($old,$new,$id){
      $result = $this->func->myQuery("SELECT * FROM ldc_staff WHERE email = ?","s",array($id),"fetch");

      if (!password_verify($old,$result['password']))
        return "password";

      $new = password_hash($new, PASSWORD_DEFAULT);

      return $this->func->myQuery("UPDATE ldc_staff SET password= ? WHERE email = ?","ss",array($new,$id),"action");
    }            

  }
?>