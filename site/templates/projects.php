<?php
include("includes/header.php"); 
content($page);

    //If tag, etc, show tag and limit list
    if ($input->urlSegment1 != ""){
        $filter = $pages->get("id=$input->urlSegment1");
        echo "<div class='p-4'>Showing: $filter->title <a href='$page->url'><i class='bi bi-x-circle'></i></a></div>";
        $projects = $filter->references("template=project"); //Updated this so any dropdown will work
    //Else show full list
    }else{
        $projects = $page->children;
    }

//Dropdowns for sort
$tagparents = $pages->find("id=1023|1025|3388");
$template = "project";
filters($tagparents, $template); 

echo "<ul class='row list projectList'>";
foreach ($projects as $project){
    if ($project->children->count>= 1){
        $count = $project->children->count();
        $count = "(+{$count})";
    }else{
        $count = "";
    }
    echo "<li class='col-md-4 mb-4'>
        <div class='border'>
            <a href='$project->url'>";
            imgSize($project, 480, 240);
            echo "
            <div class='p-3'>
                <h3>$project->title $count</h3></a>
                $project->summary
            </div>
        </div>
    </li>";
}
echo "</ul>";

include("includes/footer.php"); 
?>
