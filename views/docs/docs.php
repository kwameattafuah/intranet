<?php  
	// initialise controller class
	$class = new Docs;

	// get default
	list($cats,$docs) = $class->index();
?>

<main>
	<div style="padding: 1% 5% 0% 5%">
		<div class="card">
		  <div class="row">
		    <form class="form" data-dest="<?php echo __url__.'/actions/docs.actions.php' ?>" data-output="tbody" form-type="dynamic" >
		      <div class="row">
		        <div class="input-field col s12 m6">
		          <i class="material-icons prefix">search</i>
		          <input id="icon_prefix" type="text" name="item" class="validate">
		          <label for="icon_prefix">Type Name here</label>
		        </div>
		        <div class="input-field col s12 m4">
				    <select class="browser-default" name="category" required>
				      <option value="" disabled selected>Choose your Category</option>
				    <?php 
						if ($cats !== false)
							foreach ($cats as $cat) {
					?> 
				      <option value="<?= $cat['id']?>" ><?= $cat['name'] ?></option>
				      <?php } ?>
				    </select>
		        </div>
				<div class="input-field col s12 m2 center">
	      			<input type="hidden" name="docSearch" value="set">
				    <button type="submit" id="submit" class="btn green darken-2 white-text"> SEARCH </button> 
				</div>
		      </div>
		    </form>
		  </div>
		</div>  

		<div class="card">
			<div class="card-content">	
				<table class="responsive-table">		
					<thead>
						<tr class="blue-text text-darken-3">
							<th>Name</th>
							<th>Category</th>
							<th>Date Modified</th>
						</tr>
					</thead>
					<tbody style="overflow-y: auto;">
						<?php include("./doc.php") ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>