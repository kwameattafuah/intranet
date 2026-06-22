<?php  

  class Ldc
  {
    protected $func;
    public $books = [];
    public $ubooks = [];
    public $rooms = [];
    public $attendees = [];
    public $feeds = [];
    public $recents = [];
    public $depts = [];
    public $history = [];

    function __construct()
    {
      $this->func = new myFunc;
    }


// ROOM STATS FOR DASHBOARD
    public function index(){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT b.*, r.room_name, d.name as dept, s.name as approval FROM ldc_bookings b JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.status >= ? ORDER BY b.id ASC","i",array(0),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $books[] = $row;
      else
          $books = false;

      $result = $this->func->myQuery("SELECT b.*, r.room_name, d.name as dept, s.name as approval FROM ldc_bookings b LEFT JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.status = ? ORDER BY b.id ASC","i",array(-1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $ubooks[] = $row;
      else
          $ubooks = false;  

      $result = $this->func->myQuery("SELECT f.*, d.name as dept FROM ldc_roomfeed f JOIN ldc_room r ON f.room = r.id JOIN departments d ON f.dept = d.dept_id WHERE 1 = ? ORDER BY f.status ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $feeds[] = $row;
      else
          $feeds = false;

      $result = $this->func->myQuery("SELECT * FROM ldc_bookings b JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.status = 1 AND (start_dt <= ? AND end_dt >= ?)","ss",array($dated,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false;                

      return [$books,$ubooks,$feeds,$recents];
    }


// FETCH ROOMS
    public function rooms($val){
      if(is_null($val)) {      
        $result = $this->func->myQuery("SELECT * FROM ldc_room WHERE id != ? ORDER BY room_name ASC","i",array(0),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $rooms[] = $row;
        else
            $rooms = false;        
      } else {
        $result = $this->func->myQuery("SELECT * FROM ldc_room WHERE id = ? ORDER BY room_name ASC","i",array($val),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $rooms[] = $row;
        else
            $rooms = false;        
      }
      return $rooms;
    }          


// FETCH FEEDBACKS
    public function feedbacks($val){
      if(is_null($val)) {
        $result = $this->func->myQuery("SELECT f.*, d.name as dept FROM ldc_roomfeed f JOIN ldc_room r ON f.room = r.id JOIN departments d ON f.dept = d.dept_id WHERE 1 = ? ORDER BY when_was DESC", "i", array(1), "result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $feedbacks[] = $row;
        else
            $feedbacks = false;        
      }else{
        $result = $this->func->myQuery("SELECT f.*, d.name as dept FROM ldc_roomfeed f JOIN ldc_room r ON f.room = r.id JOIN departments d ON f.dept = d.dept_id WHERE f.status = ?", "i", array($val), "result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $feedbacks[] = $row;
        else
            $feedbacks = false;        
      }
        return $feedbacks;
    }


// CREATE FEEDBACK
    public function feed($room,$details,$whom){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d H:i:s");      
      return $this->func->myQuery("INSERT INTO ldc_feedback (room,details,by_whom,when_was) VALUES (?,?,?,?)","isss",array($room,$details,$whom,$dated),"action");
    }


// READ FEEDBACK
    public function readfeed($id,$user){
      if ($this->func->myQuery("UPDATE ldc_roomfeed SET status = ?, read_by = ? WHERE id = ?", "isi", array(1,$user,$id), "action"))
        return $this->func->myQuery("SELECT f.*, d.name as dept, r.room_name FROM ldc_roomfeed f JOIN ldc_room r ON f.room = r.id JOIN departments d ON f.dept = d.dept_id WHERE f.id = ?", "i", array($id), "fetch");
    }


// VIEW FEEDBACK
    public function viewfeed($id){
        return $this->func->myQuery("SELECT f.*, d.name as dept, r.room_name FROM ldc_roomfeed f JOIN ldc_room r ON f.room = r.id JOIN departments d ON f.dept = d.dept_id WHERE f.id = ?", "i", array($id), "fetch");
    }


// AUTHORISE ROOM REQUEST
    public function authorise($user,$room,$person){
      return $this->func->myQuery("UPDATE ldc_history SET authorised = ? WHERE room_id = ? AND individual = ?", "sis", array($user,$room,$person), "action");
    }


// VIEW ROOM DETAILS
    public function viewroom($id){
        return $this->func->myQuery("SELECT * FROM ldc_rooms WHERE id = ?", "i", array($id), "fetch");
    }


// UPDATE ROOM DETAILS
    public function roomupdate($title,$venue,$occupancy,$id){
      return $this->func->myQuery("UPDATE ldc_room SET room_name = ?, location = ?, occupancy = ? WHERE id = ?", "ssii", array($title,$venue,$occupancy,$id), "action");
    } 


// ADD A ROOM
    public function roomadd($title,$venue,$occupancy){
      return $this->func->myQuery("INSERT INTO ldc_room (room_name,location,occupancy) VALUES (?,?,?)", "ssi", array($title,$venue,$occupancy), "action");
    } 


// DELETE ROOM
    public function roomdel($id,$user){
      if ($this->func->myQuery("UPDATE ldc_room SET modifier_id = ? WHERE id = ?", "ii", array($user,$id), "action"))
        return $this->func->myQuery("DELETE FROM ldc_room WHERE id = ?", "i", array($id), "action");
    }


// DELETE FEEDBACK
    public function delfeed($id){
      return $this->func->myQuery("DELETE FROM ldc_feedback WHERE id = ?", "i", array($id), "action");
    } 


// FLAG BOOKING
    public function flags($val){
      if(is_null($val)) {      
        $result = $this->func->myQuery("SELECT * FROM ldc_bookings WHERE flag is not null OR status = ? ORDER BY id ASC","i",array(-1),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $recents[] = $row;
        else
            $recents = false;  

        return $recents;          
      } else {
        return $this->func->myQuery("SELECT b.*, r.room_name, d.name as dept, s.name as approval FROM ldc_bookings b LEFT JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.id = ?","i",array($val),"fetch");        
      }

    }    


// FETCH DEPARTMENTS
    public function deptfetch(){
      $result = $this->func->myQuery("SELECT * FROM departments WHERE 0 = ?", "i", array(0), "result");

    if($result->num_rows > 0)
      foreach ($result as $row)
        $depts[] = $row;
    else
      $depts = false;        

    return $depts;    
    }


// SAVE LDC BOOKING
    public function booksave($purpose,$booked_by,$dept,$room,$occupancy,$start_date,$end_date){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d H:i:s");
        // LDC RESERVATION CODE
        $code = 'ldc-';
        for ($i=0; $i<5; $i++){
          $code .= mt_rand(0,9);
        }  
      return [ $code, $this->func->myQuery("INSERT INTO ldc_bookings (purpose,booked_by,dept,room,occupancy,start_dt,end_dt,book_date,code) VALUES (?,?,?,?,?,?,?,?,?)", "sssiissss", array($purpose,$booked_by,$dept,$room,$occupancy,$start_date,$end_date,$dated,$code), "action") ];
    } 


// ANSWER A BOOKING
    public function bookanswer($answer,$comments,$id,$user){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d H:i:s");
      return $this->func->myQuery("UPDATE ldc_bookings SET status = ?, comments = ?, approval = ?, approval_date = ? WHERE id = ?", "ssssi", array($answer,$comments,$user,$dated,$id), "action");
    }    


// SEARCH FOR A BOOKING
    public function booksearch($search){
      // query bookings
      $result = $this->func->myQuery("SELECT b.*, r.room_name, d.name as dept, s.name as approval FROM ldc_bookings b LEFT JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.purpose LIKE CONCAT('%', ?, '%') OR b.code LIKE CONCAT('%', ?, '%')","ss",array($search,$search),"result");

      // put results in array
      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
        $recents = false;     

      return $recents;
    } 


// REQUISITION SEARCH
    public function requisitionsearch($search){
        return $this->func->myQuery("SELECT b.*, r.room_name, d.name as dept, s.name as approval FROM ldc_bookings b LEFT JOIN ldc_staff s ON b.approval_id = s.id JOIN ldc_room r ON b.room = r.id JOIN departments d ON b.dept = d.dept_id WHERE b.code = ?","s",array($search),"fetch");
    }                 

  }
?>