<div class="row login">
	<div class="col s12 m6 offset-m3">
		<nav class="z-depth-3">
			<div class="nav-wrapper">
				<p class="card-title green darken-5 white-text flow-text center-align">Staff Login</p>
			</div>
		</nav>	

		<div class="card z-depth-3 white">
			<div class="card-content">
				<form data-dest="<?php echo __url__.'/actions/login.actions.php/' ?>" data-output=".modal-content" class="form" form-type="form">
					<div class="input-field">
						<input type="text" id="username" name="username" required class="validate">
						<label for="username">Username or Email</label>
					</div>
					<div class="input-field">
						<input type="password" id="passphrase" name="passphrase" required class="validate">
						<label for="passphrase">Password</label>
					</div>
					<div class="input-field center-align">
						<input type="hidden" name="login" value="login">
						<input type="submit" value="LOGIN" class="blue darken-4  white-text btn large">
					</div>
				</form>
			</div>	
		</div>
	</div>
</div>
