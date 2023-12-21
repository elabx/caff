<?php namespace ProcessWire;
include("includes/header.php"); 
include("includes/submenu.php"); 

//IMAGE
imgSize($page, 1200, 400);

//PAGE CONTENT
content($page);



include("fields/blocks.php"); 


//List of child pages
$children = $page->children;
if ($children->count >= 1){
    echo "<h3>In this section / Topics</h3>";
    pageList($children);
}



//Related news
$articles = $page->references->find("template=article, limit=3");
if ($articles->count >= 1){
    echo "<h3>Related news</h3>";
    newsList ($articles);
}



//Experts
$people = $page->references->find("template=expert");
if ($people->count >= 1){
    //var_dump($page);
    echo "<h3>Experts</h3>";
    people($people);
}

//If there's a page on AMAROK / the AC site
if($page->link != ""){
    //If user is logged in, get latest stats from AMAROK on AC site.
    // if ($user->isLoggedin()){
    //     $html = file_get_html($page->link);
    //     $array = array("amarok_wgs", "amarok_leads", "amarok_observers", "amarok_dates");
    //     foreach ($array as $arr){
    //         $$arr = $html->getElementById("#$arr");
    //     }
    //     $stats = "";
    //     foreach ($array as $a){
    //         $stats .= strip_tags(${$a}, '<h6><div><br>');
    //     }
    //     $page->setAndSave('code', $stats);
    // }
    
    //If there are GBF targets
    if($page->tag_gbf != ""){
        $gbf = "<div class='col'><h6>GBF Targets</h6>";
        foreach ($page->tag_gbf as $g){  
            $gbf_desc = $sanitizer->textarea($g->textarea);
            $gbf .= "<div type='button' data-toggle='tooltip' data-placement='left' title='$gbf_desc'>$g->title  </div>";
        }
         $gbf .= "</div>";
    }else{
        $gbf = "";
    }
    
    echo "
    <section class='bg-light breakout mb-n4 p-5'>
        <div class='row'>
            $page->code
            $gbf
        </div>
    </section>";
}


include("includes/footer.php"); 
?>
