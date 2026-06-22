<?php  

  class Conf
  {
    protected $func;
    public $books = [];
    public $rtimes = [];
    public $ctimes = [];
    public $checks = [];

    function __construct()
    {
      $this->func = new myFunc;
    }


// FETCH ALL KINDS OF BOOKING BY DEFAULT
    public function index(){
      date_default_timezone_set("Africa/Accra");
      $dated = date("Y-m-d");
		  $timed = date("H:i:s");

      $result = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE b.end_date >= ? ORDER BY start_date ASC, start_time ASC","s",array($dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $books[] = $row;
      else
          $books = false;

      $result = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE priority = ? AND b.end_date >= ? ORDER BY start_date ASC, start_time ASC","is",array(6,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $rtimes[] = $row;
      else
          $rtimes = false; 

      $result = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE clash = ? AND b.end_date >= ? ORDER BY start_date ASC, start_time ASC","is",array(1,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $ctimes[] = $row;
      else
          $ctimes = false; 

      $result = $this->func->myQuery("SELECT * FROM conf_priority WHERE id != ?","i",array(5),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $checks[] = $row;
      else
          $checks = false; 

      $result = $this->func->myQuery("SELECT * FROM departments WHERE 1 = ? ORDER BY name ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
          $depts = false;                              
          
      $state = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE (b.start_date <= ? AND b.end_date >= ?) AND (b.end_time >= ? AND b.start_time <= ?)","ssss",array($dated,$dated,$timed,$timed),"fetch"); 

      return [$books,$rtimes,$ctimes,$state,$checks,$depts];
    }  


// FETCH ALL DEPARTMENTS
    public function generate(){
      $result = $this->func->myQuery("SELECT * FROM departments WHERE 1 = ? ORDER BY name ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
          $depts = false;        

      return $depts;
    }


// FETCH NUMBER OF CLASHES
    public function clashNumfetch(){
      return $this->func->myQuery("SELECT COUNT(*) AS clashes FROM conf_bookings WHERE clash = ?","i",array(1),"fetch");
    }


// RESOLVE TIMING CLASH
    public function resolveClashes(){
      $loop = self::clashNumfetch();
      $loop = $loop['clashes'];
      for ($i=0; $i < $loop; $i++) { 
        $comp = $this->func->myQuery("SELECT * FROM conf_bookings WHERE clash = ? ORDER BY priority DESC LIMIT 1","i",array(1),"fetch");
        $checks = $this->func->myQuery("SELECT * FROM conf_bookings WHERE clash = ? ORDER BY priority DESC","i",array(1),"result");
        
        $run = 0; $rid = null;

        if ($checks->num_rows > 1) {
          foreach ($checks as $check) {
            if ($check['start_date'] > $comp['end_date']) {
              $run = 1;
            } else {
              if ($check['start_time'] >= $comp['end_time']) {
                $run = 1;
              } else {
                if ($check['priority'] < $comp['priority'])
                  $run = 1;
              }
            }
          }
        } elseif ($checks->num_rows > 0) { 
          $run = 1;
        }  

        if ($run != 0)
          $this->func->myQuery("UPDATE conf_bookings SET clash =  ? WHERE id = ?", "ii", array(0,$comp['id']), "action");
      }
    }


// FETCH MAIL GROUPS
    public function fetchmailgroups(){
      $result = $this->func->myQuery("SELECT * FROM mail_group WHERE 7 = ? ORDER BY name ASC","i",array(7),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $checks[] = $row;
      else
          $checks = false;        

      return $checks;      
    }  


// FETCH MAIL RECEPIENTS
    public function fetchmailreceiver($val){
      if (is_null($val) || $val == 0)
        $result = $this->func->myQuery("SELECT l.*,g.name as group_name FROM mail_list l JOIN mail_group g ON l.group_id = g.id WHERE 7 = ? ORDER BY l.name ASC","i",array(7),"result");
      else
        $result = $this->func->myQuery("SELECT l.*,g.name as group_name FROM mail_list l JOIN mail_group g ON l.group_id = g.id WHERE l.group_id = ? ORDER BY l.name ASC","i",array($val),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $checks[] = $row;
      else
          $checks = false;        

      return $checks;      
    }              


// FETCH BOOKINGS
    public function fetchbookings(){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE b.end_date >= ? AND clash = ? ORDER BY start_date ASC, start_time ASC","si",array($dated,0),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $books[] = $row;
      else
          $books = false;

      return $books;  
    } 


// COUNT NUMBER OF BOOKINGS
    public function fetchbookingsNum(){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");      
      return $this->func->myQuery("SELECT COUNT(*) AS booking_number FROM conf_bookings WHERE priority != ? AND end_date >= ?","is",array(6,$dated),"fetch");
    }


// FETCH SPECIFIC BOOKING
    public function bookfetch($val){
      return $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE b.id = ? ","i",array($val),"fetch");
    }        


// FETCH BOOKING PRIORITY
    public function priorityfetch($val){
      $pf = $this->func->myQuery("SELECT id FROM conf_priority WHERE tag = ?","s",array($val),"fetch");
      $pf = intval($pf['id']);
      $pf = (is_int($pf))? $pf : 0;
      return $pf; 
    }


// FETCH PRIORITY BY ID
    public function priorityfetch_byId($val){
      $pf = $this->func->myQuery("SELECT priority FROM conf_bookings WHERE id = ?","i",array($val),"fetch");
      $pf = intval($pf['priority']);
      $pf = (is_int($pf))? $pf : 0;
      return $pf; 
    }    


// EDIT RESERVATION
    public function bookedit($description,$bookedby,$sdate,$edate,$stime,$etime,$bid){
      $priority = self::priorityfetch_byId($bid);
      $msg ='proceed';
        if ($sdate > $edate){
          $msg = 'wrong-timing';
        }elseif ($stime >= $etime) {
          $msg = 'timing-error';
        }else{
          $checks = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE end_date >= ? AND b.id != ? ORDER BY start_time ASC","si",array($sdate,$bid),"result");
          
          if ($checks->num_rows == 0) {
            $msg = 'edit-booking';
          } else {
            foreach ($checks as $check) {
              if ($check['end_time'] > $stime && $check['start_time'] <= $stime ) {
                if ($check['priority'] >= $priority){
                  var_dump($check);
                  $msg = 'timing-clash';
                  break;
                }else{
                  $id = $check['id'];
                  if ($this->func->myQuery("UPDATE conf_bookings SET clash =  1 WHERE id = ?", "i", array($id), "action"))
                    $msg = 'proceed';
                }
              }  
            } 
          }
        }  

      if ($msg == 'edit-booking' || $msg == 'proceed') {
        if ($this->func->myQuery("UPDATE conf_bookings SET description = ?,booking_by = ?,start_date = ?,end_date = ?,start_time = ?,end_time = ? WHERE id = ?", "ssssssi", array($description,$bookedby,$sdate,$edate,$stime,$etime,$bid), "action")) {
          self::resolveClashes();
          $msg = true;
        }else{
          $msg = false;
        }
      }     
        return $msg; 
    }  


// ADD RESERVATION
    public function bookadd($description,$bookedby,$sdate,$edate,$stime,$etime,$dept){
      $priority = self::priorityfetch($dept);
      $msg = 'proceed';
        if ($sdate > $edate){
          $msg = 'wrong-timing';
        }elseif ($stime >= $etime) {
          $msg = 'timing-error';
        }else{
          $checks = $this->func->myQuery("SELECT b.*, d.name FROM conf_bookings b LEFT JOIN departments d ON b.dept = d.dept_id WHERE end_date >= ? ORDER BY start_time ASC","s",array($sdate),"result");
          
          if ($checks->num_rows == 0) {
            $msg = 'new-booking';
          } else {
            foreach ($checks as $check) {
              if ($check['end_time'] > $stime && $check['start_time'] <= $stime) {
                if ($check['priority'] >= $priority){
                  $msg = 'timing-clash';
                  break;
                }else{
                  $id = $check['id'];
                  if ($this->func->myQuery("UPDATE conf_bookings SET clash =  1 WHERE id = ?", "i", array($id), "action"))
                    $msg = 'proceed';
                }
              }  
            } 
          }
        }  

      if ($msg == 'new-booking' || $msg == 'proceed') {
        if ($this->func->myQuery("INSERT INTO conf_bookings (description,booking_by,start_date,end_date,start_time,end_time,dept,priority) VALUES (?,?,?,?,?,?,?,?)", "sssssssi", array($description,$bookedby,$sdate,$edate,$stime,$etime,$_SESSION['aj.gaclintra']['dept'],$priority), "action")) {
          $msg = true;
        }else{
          $msg = false;
        }
      }
        return $msg; 
    } 


// EDIT RECEPIENT
    public function receiveredit($name,$email,$group,$id){
      if ($this->func->myQuery("UPDATE mail_list SET name = ?,email = ?,group_id = ? WHERE id = ?", "ssii", array($name,$email,$group,$id), "action")) {
        return true;
      }else{
        return false;
      }
    }  


// ADD RECEPIENT
    public function receiveradd($name,$email,$group,$id){
      if ($this->func->myQuery("INSERT INTO mail_list (name,email,group_id,created_by) VALUES (?,?,?,?)", "ssii", array($name,$email,$group,$id), "action")) {
        return true;
      }else{
        return false;
      }
    }     


// CREATE MAIL GROUP
    public function createmailgroup($name,$id){
      if ($this->func->myQuery("INSERT INTO mail_group (name,created_by) VALUES (?,?)", "si", array($name,$id), "action")) {
        return true;
      }else{
        return false;
      }
    }        


// DELETE RESERVATION
    public function delreservation($id){
      if ($this->func->myQuery("DELETE FROM conf_bookings WHERE id = ?", "i", array($id), "action")) {
        return true;
      }else{
        return false;
      }
    }  


// DELETE RECEPIENT
    public function delreceiver($id,$user){
      if ($this->func->myQuery("UPDATE mail_list SET created_by = ? WHERE id = ?", "ii", array($user,$id), "action")) {
        return $this->func->myQuery("DELETE FROM mail_list WHERE id = ?", "i", array($id), "action");
      }else{
        return false;
      }
    } 


// DELETE MAIL GROUP
    public function delmgroup($id,$user){
      if ($this->func->myQuery("UPDATE mail_group SET created_by = ? WHERE id = ?", "ii", array($user,$id), "action")) {
        return $this->func->myQuery("DELETE FROM mail_group WHERE id = ?", "i", array($id), "action");
      }else{
        return false;
      }
    }


// SEND MAIL
    public function sendmail($to,$from,$subject,$message){
      return $this->func->sendmail($to,$from,$subject,$message);
    }                                    

  }
?>