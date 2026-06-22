<?php
	// include define
	include("../../../layout/definition.php");

	$aj = new myFunc;

	$to = 'davidyaws14@gmail.com';
	$from = 'AEJAY';
	$subject = 'TRAINING DISAPPROVAL'; 
	$msg = '<html><body><div style="width: 50%; margin: 0px auto;">
		<div style="border-bottom: #003b77 solid 2px; text-align: center;">
			<h3 style="color: red">LDC REGISTRATION RESPONSE</h3>
		</div><div>	
			<p>Dear Aejay,<br><br> we are sorry to inform you that your application for <b> TITLE</b> has been declined.<br><br>Thank you for your cooperation!</p>	
		</div></div></body></html>';				
			//--- mail sending ---//
			if ( $aj->sendmail($to,$from,$subject,$msg) )	
				echo '<p class="green-text center flow-text">Mail sent successfully<br></p>'.$msg;
			else
				echo "ERROR IN MAIL SENDING!";
?>