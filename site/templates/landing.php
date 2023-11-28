<?php
include("includes/header.php"); 
include("includes/submenu.php"); 

//IMAGE
imgSize($page, 1200, 400);
content($page);

include("fields/blocks.php"); 

//Related projects
$projects = $page->references->find("template=project");
echo "<h3>Related projects</h3>";
pageList ($projects);

//Related news
$articles = $page->references->find("template=article, limit=3");
echo "<h3>Related news</h3>";
newsList ($articles);


include("includes/footer.php"); 
?>
