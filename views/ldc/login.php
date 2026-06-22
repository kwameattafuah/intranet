<div class="row">
	<div class="col s12 m6 offset-m3">	
	    <div class="col s12 wow fadeIn">
		    <nav class="z-depth-3 green darken-5">
				<div class="nav-wrapper">
					<p class="white-text flow-text center-align"><b>Learning & Development Centre</b> User Login</p>
				</div>
			</nav>	
		      <ul class="tabs" style="overflow-x: hidden;">
		        <li class="tab col s6"><a href="#user" class="active black-text"><b>USER</b> LOGIN</a></li>
		        <li class="tab col s6"><a href="#admin" class="blue-text"><b>ADMIN</b> LOGIN</a></li>
		      </ul>
	    </div>
	    <div id="user">
			<div class="row">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<form data-dest="<?php echo __url__.'/actions/ldclogin.actions.php' ?>" data-output="#det" class="form" form-type="form">
								<span id="det" class="right yellow-text text-darken-3"><?= (isset($_GET['ldc']) && $_GET['ldc'] === "success")? 'Registration Successful: Please Login!' : '' ?></span>
								<div class="input-field">
									<input type="text" id="email" name="email" required class="center validate">
									<label for="email">E-mail Address or Phone Number</label>
								</div>
								<div class="input-field">
									<input type="password" id="passphrase" name="passphrase" required class="center validate">
									<label for="passphrase">Password</label>
								</div>
								<div class="input-field center-align">
									<input type="hidden" name="login">
									<button type="submit" class="waves-effect waves-light btn blue darken-4" style="width: 50%">LOGIN</button>
								</div>
							</form>
							<p style="padding-top: 5%">You haven't registered yet, <a href="<?= __url__.'/ldc/?ldc=register' ?>">Register Now</a></p>
						</div>						
					</div>
				</div>
			</div>
	    </div>
	    <div id="admin">
			<div class="row">
				<div class="col s12">
					<div class="card">
						<div class="card-content">
							<form data-dest="<?php echo __url__.'/actions/ldclogin.actions.php' ?>" data-output="#det" class="form" data-clear-input="true" form-type="form">
								<span id="det" class="right yellow-text text-darken-3"></span>
								<div class="input-field">
									<input type="text" id="email" name="email" required class="center validate">
									<label for="email">E-mail Address or Phone Number</label>
								</div>
								<div class="input-field">
									<input type="password" id="passphrase" name="passphrase" class="center validate">
									<label for="passphrase">Password</label>
								</div>
								<div class="input-field center-align">
									<input type="hidden" name="auth">
									<button type="submit" class="waves-effect waves-light btn blue darken-4" style="width: 50%">LOGIN</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
	    </div>
	</div>
</div>	