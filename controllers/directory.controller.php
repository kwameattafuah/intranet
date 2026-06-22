<?php  
  /**
  * directory controller class
  */
  class Directories
  {

    protected $func;
    public $contacts = [];
    public $depts = [];
    
    function __construct()
    {
      $this->func = new myFunc;
    }

    public function index(){
      // query directory
      $result = $this->func->myQuery("SELECT * FROM departments WHERE 1 = ? ORDER BY name ASC", "i", array(1), "result");

      // put results in array
      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
        $depts = false;

      return [$depts,false];
    }

    public function contacts($id,$null){
      // query directory
      $result = $this->func->myQuery("SELECT * FROM directory WHERE dept = ? AND (name LIKE CONCAT('%', ?, '%') OR location LIKE CONCAT('%', ?, '%') OR extension LIKE CONCAT('%', ?, '%') ) ORDER BY name ASC","ssss",array($id,$null,$null,$null), "result");

      // put results in array
      if($result->num_rows > 0){
        foreach ($result as $row)
          $directories[] = $row;
      }else{
        $result = $this->func->myQuery("SELECT * FROM directory WHERE dept = ? ORDER BY name ASC","s",array($id), "result");
        if($result->num_rows > 0){
          foreach ($result as $row)
            $directories[] = $row;
        }else{  
          $directories = false;
        } 
      } 

      return $directories;
    }    

    public function search($search){
      // query depts
      $result = $this->func->myQuery("SELECT DISTINCT o.* FROM departments o JOIN directory  c ON o.dept_id = c.dept  WHERE o.name LIKE CONCAT('%', ?, '%') OR c.name LIKE CONCAT('%', ?, '%') OR c.location LIKE CONCAT('%', ?, '%') OR c.extension LIKE CONCAT('%', ?, '%') ORDER BY o.name ASC","ssss",array($search,$search,$search,$search),"result");

      // put results in array
      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
        $depts = false;     

      return [$depts,$search];
    }

    public function diradd($name,$location,$number,$extension,$dept){
      return $this->func->myQuery("INSERT INTO directory (name,location,number,extension,dept) VALUES (?,?,?,?,?)","sssss",array($name,$location,$number,$extension,$dept),"action");
    }

    public function editdir($name,$location,$number,$extension,$dept,$id){
      return $this->func->myQuery("UPDATE directory set name =?, location = ?, number = ?, extension = ?, dept = ? WHERE id = ? ","sssssi",array($name,$location,$number,$extension,$dept,$id),"action");    
    }   

    public function dirdelete($id){
      return $this->func->myQuery("DELETE FROM directory WHERE id = ?", "i", array($id), "action"); 
    }

}

?>