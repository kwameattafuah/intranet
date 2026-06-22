<?php  

  class Sec
  {
    protected $func;
    public $docs = [];
    public $info = [];
    public $stats = [];

    function __construct()
    {
      $this->func = new myFunc;
    }


// INDEX - FETCH ALL DOCUMENTS BUT INTRA DEPARTMENT
    public function index($dept){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d LEFT JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE disdate >= ? AND d.recdept = ? AND category != ? ORDER BY disdate DESC","ssi",array($dated,$dept,1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $docs[] = $row;
      else
          $docs = false;               

      return $docs;
    }


// FETCH ALL INTER DEPARTMENT DISPATCH DOCUMENTS
    public function internalindex($dept){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE disdate >= ? AND d.sendept = ? AND d.category = ? ORDER BY disdate DESC","ssi",array($dated,$dept,2),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $docs[] = $row;
      else
          $docs = false;               

      return $docs;
    }


// FETCH ALL INTRA DEPARTMENT DISPATCH DOCUMENTS
    public function intranalindex($dept){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE disdate >= ? AND d.sendept = ? AND d.recdept = ? AND d.category = ? ORDER BY disdate DESC","sssi",array($dated,$dept,$dept,1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $docs[] = $row;
      else
          $docs = false;               

      return $docs;
    }    


// SEARCH FOR DOCUMENTS BY SUBJECT, CODE, CATEGORY
    public function itemcrit($item,$crit){
      $dept = $_SESSION['aj.gaclintra']['dept'];
      if ($crit == 9){
        $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d LEFT JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE ( sendept = ? OR recdept = ? ) AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') ) ORDER BY disdate DESC","ssss",array($dept,$dept,$item,$item),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false; 
      }else {
          $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d LEFT JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE ( sendept = ? OR recdept = ? ) AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') ) AND category = ? ORDER BY disdate DESC","ssssi",array($dept,$dept,$item,$item,$crit),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false;     
      }
      return $docs;
    }


// SEARCH FOR DOCUMENTS BY SUBJECT, CODE, CATEGORY, SENDING DEPARTMENT
    public function itemcritsource($item,$crit,$source){
      $dept = $_SESSION['aj.gaclintra']['dept'];
      if (!empty($source)){
        $result = $this->func->myQuery("SELECT d.*, d.source as sender, r.name as recepient FROM dispatch d JOIN departments r ON d.recdept = r.dept_id WHERE recdept = ? AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') OR source LIKE CONCAT('%', ?, '%') ) AND category = ? ORDER BY disdate DESC","ssssi",array($dept,$item,$item,$source,$crit),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false; 
      }else {
          $result = $this->func->myQuery("SELECT d.*, d.source as sender, r.name as recepient FROM dispatch d JOIN departments r ON d.recdept = r.dept_id WHERE recdept = ? AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') ) AND category = ? ORDER BY disdate DESC","sssi",array($dept,$item,$item,$crit),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false;     
      }
      return $docs;
    }


// SEARCH FOR DOCUMENTS BY SUBJECT, CODE, CATEGORY, RECEIVING DEPARTMENT
    public function itemcritdept($item,$crit,$department){
      $dept = $_SESSION['aj.gaclintra']['dept'];
      if ($department != 'all' && $crit == 2){
        $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE ( (sendept = '$dept' AND recdept = ?) OR (sendept = ? AND recdept = '$dept') ) AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') ) ORDER BY disdate DESC","ssss",array($department,$department,$item,$item),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false; 
      }else {
          $result = $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id WHERE (sendept = ? OR recdept = ?) AND ( subject LIKE CONCAT('%', ?, '%') OR code LIKE CONCAT('%', ?, '%') ) AND category = ? ORDER BY disdate DESC","ssssi",array($dept,$dept,$item,$item,$crit),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $docs[] = $row;
        else
            $docs = false;     
      }
      return $docs;
    }            


// FETCHING DETAILS OF SINGLE DOCUMENT
    public function fetch($val){
      return $this->func->myQuery("SELECT d.*, s.name as sender, r.name as recepient FROM dispatch d LEFT JOIN departments s ON d.sendept = s.dept_id JOIN departments r ON d.recdept = r.dept_id  WHERE d.id = ? ","i",array($val),"fetch");
    }


// GENERATION OF DEPARTMENTS
    public function generate(){
      $result = $this->func->myQuery("SELECT * FROM departments WHERE 1 = ? ORDER BY name ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
          $depts = false;        

      return $depts;
    } 


// CONFIRMATION OF DOCUMENT RECEIPT
    public function receive($id,$user){
      return $this->func->myQuery("UPDATE dispatch SET receiver = ?, state = ?, status = ? WHERE id = ?", "siii", array($user,1,1,$id), "action");
    }  


// INITIAL RECEIPT OF DOCUMENT
    public function initreceive($code,$user){
      return $this->func->myQuery("UPDATE dispatch SET receiver = ? WHERE code = ?", "ss", array($user,$code), "action");
    }      


// DISPATCH OF DOCUMENTS
    public function send($id,$whom,$dept,$remarks){
      date_default_timezone_set("Africa/Accra");
        $disdate = date("Y-m-d H:i:s");
      if ($_SESSION['aj.gaclintra']['dept'] == $dept) {
        $cat = 1;
      } else {
        $cat = 2;
      }
      $info = self::fetch($id);
      if ($this->func->myQuery("UPDATE dispatch SET status = ? WHERE id = ?", "ii", array(2,$id), "action")) {
        return $this->func->myQuery("INSERT INTO dispatch (code,source,sdate,disdate,to_whom,subject,sendept,recdept,remarks,category,status) VALUES (?,?,?,?,?,?,?,?,?,?,?)", "sssssssssii", array($info['code'],$info['source'],$info['sdate'],$disdate,$whom,$info['subject'],$_SESSION['aj.gaclintra']['dept'],$dept,$remarks,$cat,2), "action");
      }
    }         


// INTRA OR INTERNAL DOCUMENT DISPATCH
    public function dispatch($source,$sdate,$initial,$whom,$subject,$receiver,$dept,$remarks,$cat){
      date_default_timezone_set("Africa/Accra");
        $disdate = date("Y-m-d H:i:s");

        $code = $this->func->myQuery("SELECT code FROM dispatch WHERE code LIKE CONCAT('%', ?, '%') ORDER BY code DESC LIMIT 1", "s", array($initial), "fetch");
       
        $from = $initial.' - ';
        $calc = str_replace($from, '', $code['code']);
        $calc = (!empty($calc))? substr($calc, 7) : 0 ;
        $calc = substr(($calc + 7001), 1);
        $code = $initial.' - '.date('ym').' - '.$calc;

      if ($this->func->myQuery("INSERT INTO dispatch (code,source,sdate,disdate,to_whom,subject,sendept,recdept,remarks,category,status) VALUES (?,?,?,?,?,?,?,?,?,?,?)", "sssssssssii", array($code,$source,$sdate,$disdate,$whom,$subject,$initial,$dept,$remarks,$cat,2), "action")) {
        if (!empty($receiver))
          $response = self::initreceive($code,$receiver);
        else
          $response = true;
        return ($response !== false)? $code : false;
      }else{
        return false;
      }
    }


// RECEIVING EXTERNAL DOCUMENTS
    public function extreceive($source,$sdate,$whom,$subject,$receiver,$dept,$remarks,$cat){
      date_default_timezone_set("Africa/Accra");
        $disdate = date("Y-m-d H:i:s");

        $code = $this->func->myQuery("SELECT code FROM dispatch WHERE code LIKE CONCAT('%', ?, '%') ORDER BY code DESC LIMIT 1", "s", array($dept), "fetch");
       
        $from = $dept.' - ';
        $calc = str_replace($from, '', $code['code']);
        $calc = (!empty($calc))? substr($calc, 7) : 0 ;
        $calc = substr(($calc + 7001), 1);
        $code = $dept.' - '.date('ym').' - '.$calc;

      if ($this->func->myQuery("INSERT INTO dispatch (code,source,sdate,disdate,to_whom,subject,sendept,recdept,remarks,category,state) VALUES (?,?,?,?,?,?,?,?,?,?,?)", "ssssssssssi", array($code,$source,$sdate,$disdate,$whom,$subject,null,$dept,$remarks,$cat,1), "action")) {
        $response = self::initreceive($code,$receiver);
        return ($response !== false)? $code : false;
      }else{
        return false;
      }
    }   


// EDIT OF EXTERNAL DOCUMENT
    public function extedit($source,$sdate,$whom,$subject,$receiver,$remarks,$id){
      if ($this->func->myQuery("UPDATE dispatch SET source = ?,sdate = ?,to_whom = ?,subject = ?,receiver = ?,remarks = ? WHERE id = ?", "ssssssi", array($source,$sdate,$whom,$subject,$receiver,$remarks,$id), "action")) {
        return true;
      }else{
        return false;
      }
    } 


// EDIT OF INTERNAL DOCUMENT
    public function intedit($source,$sdate,$whom,$subject,$receiver,$remarks,$state,$id){
      if ($this->func->myQuery("UPDATE dispatch SET source = ?,sdate = ?,to_whom = ?,subject = ?,receiver = ?,remarks = ?,state = ? WHERE id = ?", "ssssssii", array($source,$sdate,$whom,$subject,$receiver,$remarks,$state,$id), "action")) {
        return true;
      }else{
        return false;
      }
    }    


// DELETION OF DOCUMENT
    public function deldocument($id){
      if ($this->func->myQuery("DELETE FROM dispatch WHERE id = ?", "i", array($id), "action")) {
        return true;
      }else{
        return false;
      }
    }           


// FETCH DOCUMENT STATISTICS
    public function statistics($year,$dept){
      $result = $this->func->myQuery("SELECT COUNT(*) AS yeartotal FROM dispatch WHERE (sendept = ? OR recdept = ?) AND EXTRACT(YEAR FROM disdate) = ?", "sss", array($dept,$dept,$year),"fetch");
      $stats[0] = $result['yeartotal'];

      $result = $this->func->myQuery("SELECT COUNT(*) AS receivetotal FROM dispatch WHERE category != 1 AND recdept = ? AND EXTRACT(YEAR FROM disdate) = ?", "ss", array($dept,$year),"fetch");
      $stats[1] = $result['receivetotal'];

      $result = $this->func->myQuery("SELECT COUNT(*) AS dispatchtotal FROM dispatch WHERE sendept = ? AND EXTRACT(YEAR FROM disdate) = ?", "ss", array($dept,$year),"fetch");
      $stats[2] = $result['dispatchtotal'];

      $result = $this->func->myQuery("SELECT COUNT(*) AS externaltotal FROM dispatch WHERE category = '3' AND recdept = ? AND EXTRACT(YEAR FROM disdate) = ?", "ss", array($dept,$year),"fetch");
      $stats[3] = $result['externaltotal'];

      $result = $this->func->myQuery("SELECT COUNT(*) AS internaltotal FROM dispatch WHERE category = '1' AND sendept = ? AND recdept = ? AND EXTRACT(YEAR FROM disdate) = ?", "sss", array($dept,$dept,$year),"fetch");
      $stats[4] = $result['internaltotal'];

      return $stats;
    }                

  }
?>