<?php 
//search form used on search page
echo "<form class='form-inline mt-5 mb-3' action='{$pages->get('template=search')->url}' method='get'>
	  <input class='form-control search w-75' type='text' placeholder='Search' aria-label='Search' name='q' value='{$sanitizer->entities($input->whitelist('q'))}'>
	  <button class='btn btn-primary w-25' type='submit' name='submit'>
	  Search</button>
	</form>
";?>
