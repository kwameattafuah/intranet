<?php  
	$class = new Account;

	$account = $class->index($_SESSION['aj.gaclintra']['id']);
?>

<main>
	<div class="row">
		<div class="col s12 m6 offset-m3 parent">
			<p class="center-align flow-text">Account Settings</p>
			<form class="form" data-dest="<?= __url__.'/actions/account.actions.php' ?>" data-output=".modal-content" form-type="form">
				<div class="input-field">
					<input type="text" id="name" name="name" required="true" value="<?= $account['full_name'] ?>">
					<label for="name">Name</label>
				</div>			
				<div class="input-field">
					<input type="email" id="email" name="email" required="true" value="<?= $account['email'] ?>">
					<label for="email">Email</label>
				</div>
				<div class="input-field">
					<input type="text" name="username" id="username" required="true" value="<?= $account['username'] ?>">
					<label for="username">Username</label>
				</div>
				<div class="input-field">
					<input type="password" name="password" id="password" required="true">
					<label for="">Password</label>
				</div>
				<div class="input-field">
					<p><a href="" class="spec-ajax" data-extend-view=".pass_form" data-parent=".parent" data-output=".modal-content" data-toggle="modal" return="true">Change Password?</a></p>
				</div>
				<div class="input-field center-align">
					<input type="hidden" name="update" value="set">
					<input type="submit" class="btn green darken-2" value="UPDATE">
				</div>
			</form>
			<div class="pass_form hide">
				<div class="row">
					<div class="col s12">
						<form class="form" data-dest="<?= __url__.'/actions/account.actions.php' ?>" data-output=".modal-content" form-type="form">
							<div class="input-field">
								<input type="password" id="password" name="password" required="true">
								<label for="password">Current Password</label>
							</div>
							<div class="input-field">
								<input type="password" id="npass" name="npass" required="true">
								<label for="password">New Password</label>
							</div>
							<div class="input-field">
								<input type="password" id="rpass" name="rpass" required="true">
								<label for="password">Retype Password</label>
							</div>
							<div class="input-field center-align">
								<input type="hidden" name="pass" value="set">
								<input type="submit" value="CHANGE" class="btn green darken-2">
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</main>