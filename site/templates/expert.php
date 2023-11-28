<?php
include("includes/header.php"); 


echo "<div class='row mb-4 bg-light p-4'>";

    echo "<div class='col-md-3'>
        <div class='p-2 border'>";
        imgSize($page, 240, 240);
    echo "</div>
    </div>";
    
    echo "<div class='col-md-9'>";
        echo"
        <h1>$page->academic_title $page->title</h1>
        <h2>$page->text</h2>
        $page->text2
        <p>$page->textarea</p>";
        if ($page->email){
            echo "<div><i class='bi bi-envelope'></i> <a href='mailto:$page->email'>$page->email</a></div>";
        }
        if ($page->text3){
            echo "<div><i class='bi bi-phone'></i> $page->text3</div>";
        }
        
        //List tags
        $filters = $page->state;
        $filters->add($page->pps);
        $filters->add($page->observers);
        if($filters->count >= 1){
            echo "<div class='mt-4 small'>
            Tagged: ";
            foreach ($filters as $tag){
                echo "<a href='../$tag->id' class='button'>$tag->title</a>";
            }
            echo "</div>";
        }
    echo "</div>";  
    
echo "</div>";

//List articles this person is tagged with
$articles = $page->references->find("template=article, limit=3");
if($articles->count >= 1){
    echo "<h3>News</h3>";
    newsList ($articles);
}
//List projects this person is tagged with
$projects = $page->projects;
//$projects = $page->references->find("template=project");
if($projects->count >= 1){
    echo "<h3>Projects</h3>";
    pageList($projects);
}




include("includes/footer.php"); 
?>
