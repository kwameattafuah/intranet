<?php  

  class Ldc
  {
    protected $func;
    public $peeps = [];
    public $upeeps = [];
    public $courses = [];
    public $attendees = [];
    public $feeds = [];
    public $recents = [];
    public $depts = [];
    public $history = [];

    function __construct()
    {
      $this->func = new myFunc;
    }


// LDC DASHBOARD DISPLAY
    public function index(){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT * FROM ldc_history h JOIN ldc_register r ON r.id = h.individual_id JOIN ldc_courses c ON h.course_id = c.id WHERE h.authorised_id is not null AND c.active = '1' AND h.status != ? ORDER BY name ASC","i",array(0),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $peeps[] = $row;
      else
          $peeps = false;

      $result = $this->func->myQuery("SELECT * FROM ldc_history h JOIN ldc_register r ON r.id = h.individual_id JOIN ldc_courses c ON h.course_id = c.id WHERE h.authorised_id is null AND c.active = '1' AND 1 = ? ORDER BY r.name ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $upeeps[] = $row;
      else
          $upeeps = false;

      $result = $this->func->myQuery("SELECT * FROM ldc_courses c JOIN ldc_venue v ON c.venue = v.id WHERE c.active = ? ORDER BY c.title ASC","s",array('1'),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;  

      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE registered > 0 AND (start_date <= ? AND end_date >= ?) ORDER BY title ASC","ss",array($dated,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false;                

      return [$peeps,$upeeps,$courses,$recents];
    }


// AEJAY RANDOMISATION
    public function aj_rands($c, $l, $u = FALSE) { 
      if (!$u) for ($s = '', $i = 0, $z = strlen($c)-1; $i < $l; $x = rand(0,$z), $s .= $c{$x}, $i++); 
      else for ($i = 0, $z = strlen($c)-1, $s = $c{rand(0,$z)}, $i = 1; $i != $l; $x = rand(0,$z), $s .= $c{$x}, $s = ($s{$i} == $s{$i-1} ? substr($s,0,-1) : $s), $i=strlen($s)); 
      return $s; 
    }  


// APPRAISAL KEY CREATION
    public function rands($chars){
      $chars .= '0147852369LDCAEJAYGACL';
      do {
        $key = self::aj_rands($chars,14);
        $check = $this->func->myQuery("SELECT appraisal_key FROM ldc_courses WHERE appraisal_key = ?", "s", array($key),"fetch");
        $check = $check['appraisal_key'];
      }while(!empty($check));
      return $key;
    }  


// FETCH COURSES AND DEPARTMENTS FOR REGISTRATION
    public function course_reg(){
      date_default_timezone_set("Africa/Accra");
      $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE active = ? AND (reg_start <= ? AND reg_end >= ?) ORDER BY title ASC","sss",array(1,$dated,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;

      $result = $this->func->myQuery("SELECT * FROM departments WHERE 1 = ?", "i", array(1), "result");

      // put results in array
      if($result->num_rows > 0)
        foreach ($result as $row)
          $depts[] = $row;
      else
        $depts = false;

      return [$courses,$depts];  
    } 


// CHANGE PASSWORD
    public function pass($old,$new,$id){
      $result = $this->func->myQuery("SELECT * FROM ldc_register WHERE id = ?", "i", array($id),"fetch");

      if (!password_verify($old,$result['password']))
        return "password";

      $new = password_hash($new, PASSWORD_DEFAULT);

      return $this->func->myQuery("UPDATE ldc_register SET password = ? WHERE id = ?","si",array($new,$id),"action");
    }     


// COURSE DETAILS FOR RECENTS
    public function titles($dated){
      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE registered > 0 AND (start_date <= ? AND end_date >= ?) ORDER BY title ASC","ss",array($dated,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }


// FETCH RESET PERSONS
    public function rupsearch($id){
      $result = $this->func->myQuery("SELECT * FROM ldc_register WHERE name LIKE CONCAT('%', ?, '%') OR staff_id = ? ORDER BY name ASC","ss",array($id,$id),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }


// FETCH ALL COURSES
    public function all_courses(){
      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE 1 = ? ORDER BY title ASC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }


// FETCH COURSES OF USER
    public function fetch_activecourses($id){
		date_default_timezone_set("Africa/Accra");
		$dated = date("Y-m-d");
	  
      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE active = ? AND (reg_start <= ? AND reg_end >= ?) AND id NOT IN (SELECT course_id FROM ldc_history WHERE individual_id = ?) ORDER BY title ASC","sssi",array(1,$dated,$dated,$id),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }	


// FETCH ACTIVE COURSES	
    public function activecourses($val){
      if ($val == 9)
        $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE 9 = ? ORDER BY title ASC","i",array(9),"result");
      else
        $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE evaluate = ? ORDER BY title ASC","i",array($val),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;   

      return $courses;  
    }  


// FETCH EVALUATED COURSE
    public function evaluatedcourses($val){
      if (is_null($val))
        $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE id IN (SELECT course_id FROM ldc_evaluations_scoresheet WHERE 9 = ?) ORDER BY title ASC","i",array(9),"result");
      else
        $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE id = ? ORDER BY title ASC","i",array($val),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;   

      return $courses;  
    } 


// FETCH SCORE FOR SECTIONS
    public function fetch_score_sections($val){
        $result = $this->func->myQuery("SELECT DISTINCT e.section_id, s.caption, c.title FROM ldc_evaluations_scoresheet e JOIN ldc_evaluations_sections s ON e.section_id = s.id JOIN ldc_courses c ON c.id = e.course_id WHERE e.course_id = ?","i",array($val),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;   

      return $courses;  
    } 


// FETCH SCORE FOR QUESTIONS
    public function fetch_question_scores($course,$section,$question){
        $result = $this->func->myQuery("SELECT * FROM ldc_evaluations_scoresheet s JOIN ldc_evaluations_questions q ON s.question_id = q.id WHERE s.course_id = ? AND s.section_id = ? AND s.question_id =?","isi",array($course,$section,$question),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false;   

      return $recents;  
    }    


// ADD DOCUMENTS
    public function adddoc($files,$course,$modifier){
      $docs = $this->func->reArrayFiles($files);
      $path = '../docs/study_materials/'.$course;
      if (!file_exists($path))
        mkdir($path,0777,true);

      foreach ($docs as $doc) {
        if ($this->func->docUpload($path.'/',$doc['name'],$doc['type'],$doc['tmp_name'],$doc['size']) === true) {
          $check = $this->func->myQuery("INSERT INTO ldc_materials (source,name,course_id,modifier_id) VALUES (?,?,?,?)","ssii",array($doc['name'],(substr($doc['name'], 0, strpos($doc['name'], '.pdf'))),$course,$modifier),"action");
        }
        if ($check === false)
          return false;
      }
      return true;      
    }      


// APPRAISL OF PARTICIPANT
    public function appraised_participants($id){
      $result = $this->func->myQuery("SELECT h.individual_id, h.course_id, r.name, d.name as dept FROM ldc_history h JOIN ldc_register r ON h.individual_id = r.id JOIN departments d ON d.dept_id = r.dept_id WHERE h.course_id = ? AND h.appraised = ? ORDER BY r.name ASC","ii",array($id,1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }    


// EVALUATION SECTIONS
    public function evaluations_sections(){
      $result = $this->func->myQuery("SELECT * FROM ldc_evaluations_sections WHERE id != ?","s",array('comments'),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }   


// EVALUATION QUESTIONS
    public function evaluations_questions($id){
      $result = $this->func->myQuery("SELECT * FROM ldc_evaluations_questions WHERE section_id = ? ORDER BY id ASC","s",array($id),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    } 


// EVALUATION COMMENTS
    public function evaluations_comments(){
      $result = $this->func->myQuery("SELECT * FROM ldc_evaluations_questions WHERE section_id = ? ORDER BY id ASC","s",array('comments'),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }           


// APPRAISAL SECTIONS
    public function appraisal_sections(){
      $result = $this->func->myQuery("SELECT * FROM ldc_appraisal_sections WHERE id != ? ORDER By id DESC","s",array('remarks'),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }  


// FETCH APPRAISED
    public function fetch_appraisees($course,$dept){
      $result = $this->func->myQuery("SELECT h.individual_id, r.name FROM ldc_history h JOIN ldc_register r ON h.individual_id = r.id AND r.dept_id = ? WHERE h.appraised = ? AND h.status = ? AND h.course_id = (SELECT id FROM ldc_courses WHERE appraisal_key = ?) ORDER By r.name ASC","siis",array($dept,1,1,$course),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;
    } 


// FETCH APPRAISALS	
    public function fetch_eval_appraisees($course,$dept){
      $result = $this->func->myQuery("SELECT h.individual_id, r.name FROM ldc_history h JOIN ldc_register r ON h.individual_id = r.id AND r.dept_id = ? WHERE h.appraised = ? AND h.status = ? AND h.course_id = (SELECT id FROM ldc_courses WHERE appraisal_key = ?) ORDER By r.name ASC","siis",array($dept,0,1,$course),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;
    }	


// CREATE EVALUATION
    public function create_evaluation($course,$section,$question){
      return $this->func->myQuery("INSERT INTO ldc_evaluations_scoresheet(course_id,section_id,question_id) VALUES(?,?,?)","isi",array($course,$section,$question),"action");
    }


// EVALUATE COURSE
    public function evaluatecourse($id){
      $result = $this->func->myQuery('SELECT evaluate FROM ldc_courses WHERE id = ?', "i", array($id),"fetch");
      $display = $result['evaluate'];

      if ($display == 0) { // create scoresheet
        $result = $this->func->myQuery('SELECT course_id FROM ldc_evaluations_scoresheet WHERE course_id = ?', "i", array($id),"fetch");
        
        if(!isset($result['course_id'])){ // creation - work here
          $sections = self::evaluations_sections();
          print_r($sections);          
          foreach ($sections as $section) { 
            $thesection = $section['id'];
            if ($thesection != 'comments')
              $questions = self::evaluations_questions($thesection);

            foreach ($questions as $question) {
              $thequestion = $question['id'];
              $done = self::create_evaluation($id,$thesection,$thequestion);
            }
          } 
          return $this->func->myQuery("UPDATE ldc_courses SET evaluate = ? WHERE id = ?","si",array('1',$id),"action");
        }else{
          return $this->func->myQuery("UPDATE ldc_courses SET evaluate = ? WHERE id = ?","si",array('1',$id),"action");
        }
      } elseif ($display == 1) {
        return $this->func->myQuery("UPDATE ldc_courses SET evaluate = ? WHERE id = ?","si",array('0',$id),"action");
      }
    }


// FETCH TO BE APPRAISED
    public function fetch_appraisee_name($id){
      return $this->func->myQuery("SELECT name,email FROM ldc_register WHERE email = ?","s",array($id),"fetch");      
    }


// SCORE COURSE
    public function scorecourse($course,$section,$question,$place){
      return $this->func->myQuery("UPDATE ldc_evaluations_scoresheet SET $place = ($place + 1) WHERE course_id = ? AND section_id = ? AND question_id = ?","ssi",array($course,$section,$question),"action"); 
    }


// REMARK COURSE
    public function remarkcourse($course,$section,$question,$place,$user){
      $check = $this->func->myQuery("INSERT INTO ldc_evaluations_remarks(course_id,section_id,question_id,remarks) VALUES(?,?,?,?) ","isss",array($course,$section,$question,$place),"action"); 

      if ($check !== false)
        return $this->func->myQuery("UPDATE ldc_history SET evaluated = ? WHERE individual_id = ? AND course_id = ?","isi",array(1,$user,$course),"action");
    }  


// SCORE APPRAISAL
    public function scoreappraisee($key,$section,$question,$place,$person,$super){
      $course = $this->func->myQuery("SELECT id FROM ldc_courses WHERE appraisal_key = ?","s",array($key),"fetch");
      $course = $course['id'];

      return $this->func->myQuery("INSERT INTO ldc_appraisal_scoresheet(assessor,participant,course_id,section_id,question_id,grade) VALUES(?,?,?,?,?,?)","ssisis",array($super,$person,$course,$section,$question,$place),"action");  
    } 


// REMARK IN APPRAISAL
    public function remarkappraisee($key,$section,$question,$place,$person,$super){
      $course = $this->func->myQuery("SELECT id FROM ldc_courses WHERE appraisal_key = ?","s",array($key),"fetch");
      $course = $course['id'];

      $check = $this->func->myQuery("INSERT INTO ldc_appraisal_remarks(assessor,participant,course_id,section_id,question_id,remarks) VALUES(?,?,?,?,?,?) ","ssisis",array($super,$person,$course,$section,$question,$place),"action"); 

      if ($check !== false)
        return $this->func->myQuery("UPDATE ldc_history SET appraised = ? WHERE individual = ? AND course_id = ?","isi",array(1,$person,$course),"action"); 
    }          


// APPRAISAL QUESTIONS
    public function appraisal_questions($id){
      $result = $this->func->myQuery("SELECT * FROM ldc_appraisal_questions WHERE section_id = ? ORDER BY id ASC","s",array($id),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    } 


// PARTICIPANT QUSETIONS
    public function participant_questions($course,$id,$user){
      $result = $this->func->myQuery("SELECT a.grade, q.reads_as FROM ldc_appraisal_scoresheet a JOIN ldc_appraisal_questions q ON q.id = a.question_id WHERE a.section_id = ? AND a.course_id = ? AND a.participant = ?","sss",array($id,$course,$user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }     


// APPRAISAL COMMENTS
    public function appraisal_comments(){
      $result = $this->func->myQuery("SELECT * FROM ldc_appraisal_questions WHERE section_id = ? ORDER BY id ASC","s",array('remarks'),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }


// PARTICIPANT COMMENTS
    public function participant_comments($course,$user){
      $result = $this->func->myQuery("SELECT a.remarks, q.reads_as FROM ldc_appraisal_remarks a JOIN ldc_appraisal_questions q ON q.id = a.question_id WHERE a.section_id = ? AND a.course_id = ? AND a.participant = ?","sss",array('remarks',$course,$user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }    


// APPRAISAL VALIDATE
    public function appraisalvalidate($key,$dept){
      $result = $this->func->myQuery("SELECT r.name, r.id FROM ldc_history h JOIN ldc_register r ON h.individual_id = r.id WHERE h.appraised = ? AND r.dept_id = ? AND h.course_id = (SELECT id FROM ldc_courses WHERE appraisal_key = ?)","iss",array(0,$dept,$key),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;
    }


// FETCH MATERIALS
    public function materials($id){
      $result = $this->func->myQuery("SELECT * FROM ldc_materials WHERE course_id = ? ORDER BY id DESC","i",array($id),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }


// SEEK EVALUATIONS
    public function evaluations($user){
      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE id IN (SELECT course_id FROM ldc_history WHERE evaluated = 0 AND individual_id = ?) ORDER BY title ASC","s",array($user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $courses[] = $row;
      else
          $courses = false;

      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE id IN (SELECT course_id FROM ldc_history WHERE appraised = 1 AND individual_id = ?) ORDER BY title ASC","s",array($user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
        $recents = false;

      return [$courses,$recents];  
    } 


// CURRENT USER COURSE
    public function current_courses($user){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d");

      $result = $this->func->myQuery("SELECT * FROM ldc_courses WHERE active = ? AND (start_date <= ? OR end_date >= ?) AND id IN (SELECT course_id FROM ldc_history WHERE individual_id = '$user') ORDER BY title ASC","sss",array(1,$dated,$dated),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $recents[] = $row;
      else
          $recents = false; 

      return $recents;  
    }    


// VIEW ATTENDEES
    public function attendees($val){
      date_default_timezone_set("Africa/Accra");
      $dated = date("Y");

      if(is_null($val)) {
        $result = $this->func->myQuery("SELECT  h.id, r.name as person, d.name as dept, c.title, s.name as granted FROM ldc_history h LEFT JOIN ldc_courses c ON h.course_id = c.id LEFT JOIN ldc_register r JOIN departments d ON r.dept_id = d.dept_id ON h.individual_id = r.id LEFT JOIN ldc_staff s ON h.authorised_id = s.id WHERE EXTRACT(YEAR FROM h.date_reg) = ? ORDER BY person ASC","s",array($dated),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $attendees[] = $row;
        else
            $attendees = false;        
      } else {
        $result = $this->func->myQuery("SELECT  h.id,r.name as person, d.name as dept, c.title, s.name as granted FROM ldc_history h LEFT JOIN ldc_courses c ON h.course_id = c.id LEFT JOIN ldc_register r JOIN departments d ON r.dept_id = d.dept_id ON h.individual_id = r.id LEFT JOIN ldc_staff s ON h.authorised_id = s.id WHERE EXTRACT(YEAR FROM h.date_reg) = ? AND c.id = ? ORDER BY person ASC","si",array($dated,$val),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $attendees[] = $row;
        else
            $attendees = false;        
      }
       return $attendees;      
    } 


// FILTER ATTENDEES
    public function attendees_filter($aval,$bval){
      date_default_timezone_set("Africa/Accra");
      $dated = date("Y");

      $result = $this->func->myQuery("SELECT  h.id,r.name as person, d.name as dept, c.title, s.name as granted FROM ldc_history h LEFT JOIN ldc_courses c ON h.course_id = c.id LEFT JOIN ldc_register r JOIN departments d ON r.dept_id = d.dept_id ON h.individual_id = r.id LEFT JOIN ldc_staff s ON h.authorised_id = s.id WHERE EXTRACT(YEAR FROM h.date_reg) = ? AND c.id = ? AND h.status = ? ORDER BY person ASC","sii",array($dated,$aval,$bval),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $attendees[] = $row;
      else
          $attendees = false;        

       return $attendees;      
    }


// USER COURSE HISTORY
    public function history($user){
      $result = $this->func->myQuery("SELECT c.*, h.authorised_id, r.name FROM ldc_history h JOIN ldc_register r ON r.id = h.individual_id JOIN ldc_courses c ON h.course_id = c.id WHERE h.individual_id = ? ORDER BY name ASC","s",array($user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $history[] = $row;
      else
          $history = false;

      return $history;
    }      


// COURSE ATTENDANCE
    public function attended($course){
      if(is_null($course)){
        $result = $this->func->myQuery("SELECT a.*, r.name, d.name as dept FROM attendance a LEFT JOIN ldc_register r JOIN departments d ON r.dept_id = d.dept_id ON r.id = a.pid WHERE 1 = ? ORDER BY name ASC","i",array(1),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $attendees[] = $row;
        else
            $attendees = false;        
      }else{
        $result = $this->func->myQuery("SELECT a.*, r.name, d.name as dept FROM attendance a LEFT JOIN ldc_register r JOIN departments d ON r.dept_id = d.dept_id ON r.id = a.pid WHERE a.course = ? ORDER BY name ASC","i",array($course),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $attendees[] = $row;
        else
            $attendees = false;
      }  

      return $attendees;
    }  


// COURSE DETAILS
    public function courses($val){
      if(is_null($val)) {      
        $result = $this->func->myQuery("SELECT c.*, v.room_name FROM ldc_courses c JOIN ldc_venue v ON c.venue = v.id  WHERE 1 = ? ORDER BY c.title ASC","i",array(1),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $courses[] = $row;
        else
            $courses = false;        

        return $courses;
      } else {
        $result = $this->func->myQuery("SELECT c.*, v.room_name FROM ldc_courses c JOIN ldc_venue v ON c.venue = v.id  WHERE DATE_FORMAT(start_date, '%Y-%m-%d') <= ? ORDER BY c.title ASC","s",array($val),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $courses[] = $row;
        else
            $courses = false;        

        return $courses;
      }
    }  


// COURSE HISTORY
    public function course_history($user){
      $result = $this->func->myQuery("SELECT c.* FROM ldc_history h JOIN ldc_courses c ON h.course_id = c.id WHERE h.individual_id = ? ORDER BY h.id DESC","s",array($user),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $histories[] = $row;
      else
          $histories = false;        

      return $histories;
    }        


// SEND FEEDBACK
    public function feed($course,$details,$whom){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d H:i:s");      
      $check = $this->func->myQuery("INSERT INTO ldc_feedback (course,details,by_whom,when_was) VALUES (?,?,?,?)","isss",array($course,$details,$whom,$dated),"action");
      if ($check !== false) {
        return $this->func->myQuery("UPDATE ldc_history SET remarked = (remarked + 1) WHERE individual = ? AND course_id = ?","si",array($whom,$course),"action");
      }
    }


// FETCH FEEDBACK
    public function fetch_feed($course,$whom){
      $result = $this->func->myQuery("SELECT * FROM ldc_feedback WHERE course = ? AND by_whom = ? ORDER BY when_was DESC", "is", array($course,$whom), "result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $feedbacks[] = $row;
        else
            $feedbacks = false;

      $recents = $this->func->myQuery("SELECT remarked FROM ldc_history WHERE course_id = ? AND individual = ?", "is", array($course,$whom), "fetch");    

      return [$feedbacks, $recents];    
    }


// GET FEEDBACKS
    public function feedbacks($val){
      if(is_null($val)) {
        $result = $this->func->myQuery("SELECT *,f.id as fid FROM ldc_feedback f JOIN ldc_courses c ON f.course = c.id JOIN ldc_register r ON r.id = f.by_whom WHERE 1 = ? ORDER BY when_was DESC", "i", array(1), "result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $feedbacks[] = $row;
        else
            $feedbacks = false;        
      }else{
        $result = $this->func->myQuery("SELECT *,f.id as fid FROM ldc_feedback f JOIN ldc_courses c ON f.course = c.id JOIN ldc_register r ON r.id = f.by_whom WHERE f.status = ?", "i", array($val), "result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $feedbacks[] = $row;
        else
            $feedbacks = false;        
      }
        return $feedbacks;
    }


// READ FEEDBACK
    public function readfeed($id,$user){
      if ($this->func->myQuery("UPDATE ldc_feedback SET status = ?, read_by = ? WHERE id = ?", "isi", array(1,$user,$id), "action"))
        return $this->func->myQuery("SELECT * FROM ldc_feedback f JOIN ldc_courses c ON f.course = c.id JOIN ldc_register r ON r.id = f.by_whom WHERE f.id = ?", "i", array($id), "fetch");
    }


// VIEW FEEDBACK
    public function viewfeed($id){
        return $this->func->myQuery("SELECT * FROM ldc_feedback f JOIN ldc_courses c ON f.course = c.id JOIN ldc_register r ON r.id = f.by_whom WHERE f.id = ?", "i", array($id), "fetch");
    }


// VIEW ATTENDEE
    public function viewattendee($id){
      return $this->func->myQuery("SELECT h.*, r.*, c.id as courseid, c.title, s.name as author FROM ldc_history h JOIN ldc_register r ON r.id = h.individual_id JOIN ldc_courses c ON h.course_id = c.id LEFT JOIN ldc_staff s ON s.id = h.authorised_id WHERE h.id = ?", "i", array($id), "fetch");      
    }


// COURSE - ATTENDEE VIEW
    public function attendeeview($eml,$course){
      return $this->func->myQuery("SELECT *, c.id as courseid FROM ldc_history h JOIN ldc_register r ON r.id = h.individual_id JOIN ldc_courses c ON h.course_id = c.id WHERE h.individual_id = ? AND h.course_id = ?", "ii", array($eml,$course), "fetch");      
    }    


// AUTHORISE ATTENDEE
    public function authorise($user,$person,$course,$answer,$message){
      return $this->func->myQuery("UPDATE ldc_history SET authorised_id = ?, status = ?, comment = ? WHERE course_id = ? AND individual_id = ?", "iisii", array($user,$answer,$message,$course,$person), "action");
    }


// VIEW COURSE
    public function viewcourse($id){
        return $this->func->myQuery("SELECT * FROM ldc_courses c JOIN ldc_venue v ON c.venue = v.id WHERE c.id = ?", "i", array($id), "fetch");
    }


// UPDATE COURSE
    public function courseupdate($title,$instructor,$venue,$sdate,$edate,$sreg,$ereg,$occupancy,$status,$id){
      return $this->func->myQuery("UPDATE ldc_courses SET title = ?, instructor = ?, venue = ?, start_date = ?, end_date = ?, reg_start = ?, reg_end = ?, capacity = ?, active = ? WHERE id = ?", "ssissssisi", array($title,$instructor,$venue,$sdate,$edate,$sreg,$ereg,$occupancy,$status,$id), "action");
    } 


// ADD COURSE
    public function courseadd($title,$instructor,$venue,$sdate,$edate,$sreg,$ereg,$occupancy,$status,$key,$id){
      return $this->func->myQuery("INSERT INTO ldc_courses (title,instructor,venue,start_date,end_date,reg_start,reg_end,capacity,active,appraisal_key,created_by) VALUES (?,?,?,?,?,?,?,?,?,?,?)", "sssssssissi", array($title,$instructor,$venue,$sdate,$edate,$sreg,$ereg,$occupancy,$status,$key,$id), "action");
    } 


// TAKE COURSE
    public function takecourse($course,$individual){
      date_default_timezone_set("Africa/Accra");
        $dated = date("Y-m-d H:i:s");
      if ($this->func->myQuery("INSERT INTO ldc_history (course_id,individual_id,date_reg) VALUES (?,?,?)", "iis", array($course,$individual,$dated), "action")!==false)
        return $this->func->myQuery("UPDATE ldc_courses SET registered = registered + 1 where id = ?", "i", array($course), "action");
    } 


// DELETE COURSE
    public function coursedel($id,$user){
      if ($this->func->myQuery("UPDATE ldc_courses SET created_by = ? WHERE id = ?", "ii", array($user,$id), "action"))
        return $this->func->myQuery("DELETE FROM ldc_courses WHERE id = ?", "i", array($id), "action");
    }


// DELETE MATERIAL 
    public function delmaterial($id,$user){     
      if ($this->func->myQuery("UPDATE ldc_materials SET modifier_id = ? WHERE id = ?", "ii", array($user,$id), "action")) {
        $result = $this->func->myQuery('SELECT source, course_id FROM ldc_materials WHERE id = ?', "i", array($id),"fetch");
        if (unlink("../docs/study_materials/".$result['course_id']."/".$result['source']))
          return $this->func->myQuery("DELETE FROM ldc_materials WHERE id = ?", "i", array($id), "action");
      }
    } 


// DELETE FEEDBACK
    public function delfeed($id){
      return $this->func->myQuery("DELETE FROM ldc_feedback WHERE id = ?", "i", array($id), "action");
    }  


// REGISTER ATTENDANCE
    public function reggen($course,$password,$user){
      $credentials = $this->func->myQuery("SELECT * FROM ldc_staff WHERE email = ?","s",array($user),"fetch");
      if (password_verify($password,$credentials['password'])){
        if ($this->func->myQuery("INSERT INTO attendance (pid,course) SELECT individual, course_id FROM ldc_history WHERE course_id = ?","i",array($course),"action")!== false)
          return true;
        else
          return false;
      }else{
        return false;
      }
    }


// LDC STAFF PASSWORD VERIFY
    public function pass_verify($password,$user){
      $credentials = $this->func->myQuery("SELECT * FROM ldc_staff WHERE id = ?","i",array($user),"fetch");
      if (password_verify($password,$credentials['password'])){
        return true;
      } else {
        return false;
      }  
    } 


// USER PASSWORD VERIFY
    public function user_pass_verify($password,$user){
      $credentials = $this->func->myQuery("SELECT * FROM ldc_register WHERE id = ?","i",array($user),"fetch");
      if (password_verify($password,$credentials['password'])){
        return true;
      } else {
        return false;
      }  
    }    


// PARTICIPANT ACCOUNT DETAILS
    public function participant_account($user){
      return $this->func->myQuery("SELECT * FROM ldc_register WHERE id = ?","i",array($user),"fetch"); 
    } 


// SET ATTENDANCE REGISTRY
    public function registry($user,$password,$course){
      $credentials = $this->func->myQuery("SELECT * FROM ldc_register WHERE email = ? OR phone = ?","ss",array($user,$user),"fetch");
      if (password_verify($password,$credentials['password'])){
        return $this->func->myQuery("UPDATE attendance SET status = ? WHERE course = ? AND pid = ?", "iis", array(1,$course,$user), "action");
      } 
    }                  


// GET COURSE ATTENDANCE
    public function courseattendants($course){
      $result = $this->func->myQuery("SELECT * FROM attendance WHERE course = ? AND status = 0","i",array($course),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $attendees[] = $row;
      else
          $attendees = false;        

      return $attendees;      
    }


// GET LDC STAFF
    public function users(){
      // get users
      $result = $this->func->myQuery("SELECT * FROM ldc_staff WHERE position != ?", "s", array("super"), "result");

      if ($result->num_rows > 0)
        foreach ($result as $row)
          $users[] = $row;
      else
        $users = false; 

      return $users;
    }


// RESET LDC STAFF
    public function resetuser($fullname,$position,$email,$phone,$passphrase,$id){
      $password = password_hash($passphrase,PASSWORD_DEFAULT);
      return $this->func->myQuery("UPDATE ldc_staff set name =?, position = ?, email = ?, phone = ?, password = ? WHERE email = ? ","ssssss",array($fullname,$position,$email,$phone,$passphrase,$id),"action");    
    }


// ADD LDC STAFF
    public function adduser($fullname,$position,$email,$phone,$passphrase,$id){
      $password = password_hash($passphrase,PASSWORD_DEFAULT);
      return $this->func->myQuery("INSERT INTO ldc_staff (name,position,email,phone,password,created_by) VALUES (?,?,?,?,?,?)","sssssi",array($fullname,$position,$email,$phone,$password,$id),"action");    
    }


// EDIT LDC STAFF
    public function edituser($fullname,$position,$email,$phone,$id){
      return $this->func->myQuery("UPDATE ldc_staff set name =?, position = ?, email = ?, phone = ? WHERE id = ? ","ssssi",array($fullname,$position,$email,$phone,$id),"action");    
    }


// EDIT ATTENDEE
    public function editparticipant($fullname,$position,$staffid,$phone,$id){
      return $this->func->myQuery("UPDATE ldc_register set name =?, position = ?, staff_id = ?, phone = ? WHERE id = ? ","ssssi",array($fullname,$position,$staffid,$phone,$id),"action");    
    }    


// DELETE LDC STAFF ACCOUNT
    public function userdelete($id){
      if ($this->func->myQuery("UPDATE ldc_staff SET created_by = ? WHERE id = ?", "ii", array($user,$id), "action"))
        return $this->func->myQuery("DELETE FROM ldc_staff WHERE id = ?", "i", array($id), "action");
    }    


// VIEW ROOMS
    public function rooms($val){
      if(is_null($val)) {      
        $result = $this->func->myQuery("SELECT * FROM ldc_venue WHERE id != ? ORDER BY room_name ASC","i",array(0),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $rooms[] = $row;
        else
            $rooms = false;        
      } else {
        $result = $this->func->myQuery("SELECT * FROM ldc_venue WHERE id = ? ORDER BY room_name ASC","i",array($val),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $rooms[] = $row;
        else
            $rooms = false;        
      }
      return $rooms;
    }  


// UPDATE ROOM
    public function roomupdate($title,$venue,$occupancy,$id){
      return $this->func->myQuery("UPDATE ldc_venue SET room_name = ?, location = ?, occupancy = ? WHERE id = ?", "ssii", array($title,$venue,$occupancy,$id), "action");
    } 


// ADD ROOM
    public function roomadd($title,$venue,$occupancy){
      return $this->func->myQuery("INSERT INTO ldc_venue (room_name,location,occupancy) VALUES (?,?,?)", "ssi", array($title,$venue,$occupancy), "action");
    } 


// DELETE ROOM
    public function roomdel($id,$user){
      if ($this->func->myQuery("UPDATE ldc_venue SET modifier_id = ? WHERE id = ?", "ii", array($user,$id), "action"))
        return $this->func->myQuery("DELETE FROM ldc_venue WHERE id = ?", "i", array($id), "action");
    }


// SEND MAIL
    public function sendmail($to,$from,$subject,$message){
      return $this->func->sendmail($to,$from,$subject,$message);
    }


// RUP RESET
    public function rupreset($new,$id){
      $new = password_hash($new, PASSWORD_DEFAULT);
      return $this->func->myQuery("UPDATE ldc_register SET password = ? WHERE id = ?","si",array($new,$id),"action");
    } 

  }
?>