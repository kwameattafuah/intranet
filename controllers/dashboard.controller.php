<?php
  /**
   * dashboard
   */
  class Dashboard
  {
    protected $func;
    public $news = array();
    public $links = array();
    public $images = array();
    public $top = array();
    public $infos = array();

    function __construct()
    {
        $this->func = new myFunc;
    }

    public function index(){
      // get news
      $result = $this->func->myQuery("SELECT * FROM news WHERE topstory = ? AND visible = ? ORDER BY id DESC LIMIT 2","ii",array(0,1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $news[] = $row;
      else
          $news = false;

      // get links
      $result = $this->func->myQuery("SELECT * FROM links WHERE 1 = ?","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $links[] = $row;
      else
        $links = false;

      // get infos
      $result = $this->func->myQuery("SELECT * FROM infos WHERE 1 = ? ORDER BY id DESC","i",array(1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $infos[] = $row;
      else
        $infos = false;

      // get top story
      $top = $this->func->myQuery("SELECT * FROM news WHERE topstory = ?", "i", array(1),"fetch");

      // get top story images
      $result = $this->func->myQuery("SELECT * FROM news_galleries WHERE gallery_category = ? AND news_id = (SELECT id FROM news WHERE topstory = ?)","ii",array(1,1),"result");

      if($result->num_rows > 0)
        foreach ($result as $row)
          $images[] = $row;
      else
        $images = false;


      return [$infos,$top,$images,$links,$news];

    }

    public function infoView($id){
      // get info
      return $this->func->myQuery("SELECT * FROM infos WHERE id = ?","i",array($id),"fetch");
    }

    public function newsView($id){
      // get info
      return $this->func->myQuery("SELECT * FROM news WHERE id = ?","i",array($id),"fetch");
    }   

    public function imageView($id){
      // get news image
      return $this->func->myQuery("SELECT frame FROM news_galleries WHERE news_id = ? ORDER BY RAND() LIMIT 1","i",array($id),"fetch");
    }

    public function viewNewspic($id){
      // get news image
      return $this->func->myQuery("SELECT * FROM news_galleries WHERE id = ?","i",array($id),"fetch");
    }   

    public function viewAllNewspics($id,$nid){
      $lead = self::viewNewspic($id);
      // get news image
      $result = $this->func->myQuery("SELECT * FROM news_galleries WHERE news_id = ? AND id != ?","ii",array($nid,$id),"result");

      if($result->num_rows > 0)
          foreach ($result as $row)
            $news[] = $row;
        else
            $news = false;

        return [$lead,$news];
    }      

    public function fetch_newsitem($id){
      return $this->func->myQuery("SELECT * FROM news WHERE id = ?","i",array($id),"fetch");  
    }

      public function fetch_newspics($id){
        // get news pictures
        $result = $this->func->myQuery("SELECT * FROM news_galleries WHERE news_id = ? AND gallery_category = ?","ii",array($id,1),"result");

        if($result->num_rows > 0)
          foreach ($result as $row)
            $news[] = $row;
        else
            $news = false;

        return $news;
      }

}

?>
