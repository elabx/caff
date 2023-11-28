<?php include('includes/header.php'); ?>

<section class="mt-5 pt-5">

	<h1 class=''><?php echo $page->title; ?></h1>
	<?php 
    	include("fields/blocks.php"); 
    	include("includes/searchform.php"); 
	?>

	<script>
	  (function() {
		var cx = 'b671ec5b1631f1c95';
		var gcse = document.createElement('script');
		gcse.type = 'text/javascript';
		gcse.async = true;
		gcse.src = 'https://cse.google.com/cse.js?cx=' + cx;
		var s = document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(gcse, s);
	  })();
	</script>
	<gcse:searchresults-only></gcse:searchresults-only>
	</section>

<?php include('includes/footer.php'); ?>