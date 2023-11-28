<?php

//Sort by url segment if provided
if($input->urlSegment(1)){
    $sort="sort={$input->urlSegment(1)}";
}else{
    $sort="sort=projects, sort=number";
}

//If search query
$q = $sanitizer->text($input->get('q')); 
if($q) { 
	$input->whitelist('q', $q); 
	$q = $sanitizer->selectorValue($q); 
	$selector = "textarea~=$q, parent=$page"; 
	$results = $pages->find($selector); 
	if($results->count) {
		$content = "<h2>Showing results for $q (<a href='$page->url'>see all</a>)</h2>";
	} else {
		$content = "<h2>Sorry, no results were found.</h2>";
	}

} else {
	// no search terms provided
	$results = $page->children("limit=20, $sort");
	$content = "";
}
include("includes/header.php"); 
include("includes/submenu.php"); 

//PAGE CONTENT
content($page);

//Search
echo "<form class='form-inline mt-5 mb-3' action='{$page->url}' method='get'>
	  <input class='form-control search w-75' type='text' placeholder='Search recommendations' aria-label='Search' name='q' value='{$sanitizer->entities($input->whitelist('q'))}'>
	  <button class='btn btn-primary w-25' type='submit' name='submit'>
	  Search</button>
	</form>";

//include("fields/blocks.php"); 

echo $content;
$pagination = $results->renderPager();
echo $pagination;


//recommendations table
echo "<div class='breakout px-4'>
<table class='table small'>";
echo "<tr>
    <th><a href='{$page->url}projects'>Project</a></th>
    <th><a href='{$page->url}tag_outcome'>Type</a></th>
    <th><a href='{$page->url}text'>#</a></th>
    <th><a href='{$page->url}textarea'>Outcome</a></th>
    <th><a href='{$page->url}fec_pub'>Report</a></th>
    <th><a href='{$page->url}fec_pub.text'>Year</a></th>
    <th><a href='{$page->url}tag_fec'>FEC</a></th>
</tr>";
foreach ($results as $child){
    echo "<tr>";
    echo "<td>";
         foreach ($child->projects as $project){
            echo "<a href='$project->url'>$project->title</a>";
        }
    echo "</td>";
    echo "<td>";
        tags($child->tag_outcome);
    echo "</td>";
    echo "<td>";
        echo "$child->text";
    echo "</td>";
    echo "<td>";
        echo "$child->textarea";
        editLink($child, $user);
    echo "</td>";

    echo "<td>";
        foreach ($child->fec_pub as $pub){
            if($pub->link != ""){
                echo "<a href='$pub->link' class=''>$pub->title</a>";
            }else{
                echo "$pub->title";
                editLink($pub, $user);
            }
            
            $year = $pub->text;
        }
    echo "</td>";
    echo "<td>";
        echo "$year";
        unset($year);
    echo "</td>";

    echo "<td>";
         foreach ($child->tag_fec as $fec){
            echo "<a href='$fec->url'>$fec->title</a>";
        }
    echo "</td>";
    echo "</tr>";
}
echo "</table></div>";
echo $pagination;

include("includes/footer.php"); 
?>
