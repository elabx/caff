<?php
include("includes/header.php"); 

$tags = "<div class='small'>";
foreach ($page->topic as $tag){
    $tags .= "<a href='$tag->url' class='mr-3'>$tag->title</a>";
}
foreach ($page->approach as $tag){
    $tags .= "<a href='$tag->url' class='mr-3'>$tag->title</a>";
}
$tags .= "</div>";

//PAGE HEADER

imgSize($page, 1200, 400);

echo "<div class='my-5 pt-3'>
        <div class='row align-items-end'>
        <div class='col-md-8 text-md-right'>";
            echo "<h1 class='pr-5 pl-2 border-right'>{$page->title}</a></h1>
        </div>
        <div class='col-md-4 pl-4'>";
            echo $tags;
            echo "<h6>$page->date</h6>";
        echo "</div>
    </div>
</div>";


// echo"
// <section class='breakout bg-dark'>
//     <div class='row'>
//         <div class='col-md-6'>";
//             imgSize($page, 1200, 500);
//         echo "</div>
//         <div class='col-md-6 p-4'>
//             <h1 class='mt-4'>$page->title</h1>
//             <div class='mb-4'>$page->date</div>
//             $tags";
//         echo"</div>
//     </div>
// </section>
// ";
// imgDetails($page->image);

//ARTICLE

echo "<div class='row'>
 <div class='col-md-8 mb-4'>
    <div class='lead p-summary mb-4'>{$page->summary}</div>
    {$page->textarea}
</div>";
            
// echo "<div class='lead py-4'>$page->summary</div>";
// echo"
// <div class='row mb-4'>
//     <div class='col-md-8'>
//         $page->textarea
//     </div>";
    
    //SIDEBAR
    echo "<div class='col-md-4'>";
        //Get media contact from Settings page
        $people = $pages->get(1039)->expert;
        people($people);
    echo"</div>
</div>
</div>
";

 include("fields/blocks.php"); //Sidebar

//Related projects
$projects = $page->projects;
if ($projects->count()){
    echo "<h3>Projects</h3>";
    pageList ($projects);
}

//Related experts
$experts = $page->expert;
if ($experts->count()){
    echo "<h3>Experts</h3>";
    people ($experts);
}

//Related reading
$more = $page->pagelist;
if ($more->count()){
    echo "<h3>Further reading</h3>";
    pageList ($more);
}

include("includes/footer.php"); 
?>
