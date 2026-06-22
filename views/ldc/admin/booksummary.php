<?php  
  if ($feedbacks !== false)
    foreach ($feedbacks as $feedback) {
      $bold = null;
      $color = null;

      // check if read or unread
      if ($feedback['status'] == 0)
        $bold = "bold";
      else
        $color = "grey-text";

?>
  <div class="summary">
    <a href="" class="summary-link spec-ajax" data-dest="<?php echo __url__.'/actions/ldcbook.actions.php' ?>" data-query="<?= ($feedback['status'] != 0)? 'viewfeed':'readfeed' ?>" id="<?php echo $feedback['id']; ?>" data-output="#content">
      <p>
        <span class="<?php echo $bold; ?> grey-text text-darken-4 truncate name"><?php echo $feedback['subject'] ?></span> 
        <span class="right date <?php echo $color; ?>"><?php echo date("jS M", strtotime($feedback['when_was'])); ?></span>
      </p>
      <p class="truncate grey-text wrap"><?php echo $feedback['details']; ?></p>
    </a>
    <div class="divider"></div>
  </div>
<?php } ?>