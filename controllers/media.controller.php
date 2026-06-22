<?php  
	/**
	* media controller class
	*/
	class Media
	{
		protected $func;
		public $media = [];

	function __construct()
	{
		$this->func = new myFunc;
	}

	public function index(){
		date_default_timezone_set("Africa/Accra");
		$date = date("Y");

		$result = $this->func->myQuery("SELECT DISTINCT event FROM galleries WHERE gallery_category = ? AND EXTRACT(YEAR FROM dateEdited) =  ? ORDER BY event ASC", "is", array(1,$date), "result");

		if ($result->num_rows > 0)
			foreach ($result as $row)
				$media[] = $row;
		else
			$media = false;

		return $media;
	}

	public function event($event){
		$result = $this->func->myQuery("SELECT * FROM galleries WHERE event =  ? AND gallery_category = ? AND visible = ?", "sii", array($event,1,1), "result");

		if ($result->num_rows > 0)
			foreach ($result as $row)
				$media[] = $row;
		else
			$media = false;

		return $media;
	}		

    public function view($id){
      // get images
      return $this->func->myQuery("SELECT * FROM galleries WHERE id = ?","i",array($id),"fetch");
    } 

	public function addImage($files,$visible,$caption,$event,$category){
		$event = (!empty(trim($event)))? $event : 'OTHERS / RANDOMS';
		$images = $this->func->reArrayFiles($files);
		foreach ($images as $pic) {
			if ($this->func->imgUpload('../media/gallery/',$pic['name'],$pic['tmp_name'],$pic['size']) === true) {
				$check = $this->func->myQuery("INSERT INTO galleries (frame,visible,caption,event,gallery_category) VALUES (?,?,?,?,?)","sissi",array($pic['name'],$visible,$caption,$event,$category),"action");
			}
			if ($check === false)
				return false;
		}
		return true;	
	}  

	public function addVideo($vid,$visible,$caption,$event,$category){
		return $this->func->myQuery("INSERT INTO galleries (frame,visible,caption,event,gallery_category) VALUES (?,?,?,?,?)","sissi",array($vid,$visible,$caption,$event,$category),"action");	
	}

	public function delete($id){
		$result = $this->func->myQuery('SELECT * FROM galleries WHERE id = ?', "i", array($id),"fetch");

		if ($result['gallery_category']==1){
			if (unlink("../media/gallery/".$result['frame']))
				return $this->func->myQuery("DELETE FROM galleries WHERE id = ?", "i", array($id), "action");
		}elseif($result['gallery_category']==2)
			return $this->func->myQuery("DELETE FROM galleries WHERE id = ?", "i", array($id), "action");
		else
			return false;	
	}

	public function editImage($visible,$caption,$event,$id){
		return $this->func->myQuery("UPDATE galleries SET visible = ?,caption = ?,event = ? WHERE id = ?","sssi",array($visible,$caption,$event,$id),"action");	
	}

	public function category($id){
		date_default_timezone_set("Africa/Accra");
		$date = date("Y");

		$result = $this->func->myQuery("SELECT * FROM galleries WHERE EXTRACT(YEAR FROM dateEdited) =  ? AND gallery_category = ?", "si", array($date,$id), "result");

		if ($result->num_rows > 0)
			foreach ($result as $row)
				$media[] = $row;
		else
			$media = false;

		return $media;
	}				        		

}
?>