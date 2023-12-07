<?php
//Initialize
namespace ProcessWire;
$files->include($config->paths->templates."includes/functions.php");
include ("scripts/simple_html_dom.php");


//Useful variables
$settings = $pages->get(1039);
$aclogo = $settings->images->getTag('ac');
$cafflogo = $settings->images->getTag('caff');
#$domain = "http://217.171.210.247/pwcaff/";
$domain = "https://new.caff.is";
$menu = $pages->find("parent=1");
$sitename = "CAFF";
$httptemplates = $config->urls->httpTemplates;
$twitterHandle = "CAFFSecretariat";

//Get page summary, strip quotes
if ( $page->summary != "" ){
  $summary = $page->summary;
}else{
  $summary = "CAFF - {$page->title}";
}
$summary = str_replace('"', "", $summary);
//Get alt title
if ( $page->title_alt != "" ){$titleFull = $page->title_alt;}else{$titleFull = $page->title;}
?>

<!doctype html>
<html lang="en">

  <head>
    <meta charset="utf-8">
    <meta name="description" content="<?php echo $summary;?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="shortcut icon" href="<?php echo $config->urls->assets.'files/1039/favicon.ico'; ?>">

  	<meta property="og:type" content="article" />
  	<meta property="og:title" content="<?php echo $titleFull;?>" />
  	<meta property="og:description" content="<?php echo $summary;?>" />
  	<meta property="og:image" content="<?php echo $domain.$page->images->first->url;?>" />
  	<meta property="og:url" content="<?php echo $page->httpUrl;?>" />
  	<meta property="og:site_name" content="<?php echo $sitename;?>" />

  	<meta name="twitter:title" content="<?php echo $titleFull;?>">
  	<meta name="twitter:description" content="<?php echo $summary;?>">
  	<meta name="twitter:image" content="<?php echo $domain.$page->images->first->url;?>">
  	<meta name="twitter:site" content="@<?php echo $twitterHandle;?>">
  	<meta name="twitter:creator" content="@<?php echo $twitterHandle;?>">

    <link rel="canonical" href="<?php echo $domain.$page->path; ?>" />
    <!-- <link rel="shortcut icon" href=""> -->
   <!-- <link rel="preconnect" href="https://fonts.googleapis.com">-->
   <!-- <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>-->
   <!-- <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400&family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.3/font/bootstrap-icons.css">
  	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
    <script src="https://unpkg.com/htmx.org@1.9.9/dist/htmx.js"></script>
      <link rel="stylesheet" href="<?php echo $httptemplates; ?>styles/main.css">

        <!-- Google tag (gtag.js) Google Analytics  -->
		<script async src="https://www.googletagmanager.com/gtag/js?id=G-S8X2L1B2QX"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag(){dataLayer.push(arguments);}
          gtag('js', new Date());
        
          gtag('config', 'G-S8X2L1B2QX');
          gtag('config', 'UA-49873190-1');
        </script>
        

    <title><?php echo "$page->title | $sitename";?></title>
  </head>

<a href="#" id="toTopBtn" class="cd-top text-replace js-cd-top cd-top--is-visible cd-top--fade-out" data-abc="true"></a>

  <body id='<?php echo $page->template->name;?>'>
      
    <header>

      <!--Working group header-->
      <div class="text-uppercase small bg-dark">
          <div class="container py-3">
              <a href='https://arctic-council.org'>
                  <img src='<?php echo $aclogo->url;?>' height='25px' class='pr-3'>
                  <?php echo "Arctic Council Working Group";?>
              </a>
          </div>
      </div>

      <!--Main navigation-->
      <div class="z-100 py-4 mb-4">
        <nav class='navbar navbar-expand-lg container'>
            <a class="navbar-brand mr-0" href="<?php echo $domain;?>"><img src="<?php echo $cafflogo->url;?>" height='50px'></a>
              <button class='navbar-toggler' type='button' data-toggle='collapse' data-target='#navbarSupportedContent' aria-controls='navbarSupportedContent' aria-expanded="false" aria-label="Toggle navigation">
                <i class='i bi-list'></i>
              </button>

            <div class='collapse navbar-collapse' id='navbarSupportedContent'>
                <ul class='navbar-nav mr-auto px-2'>
                    
                <?php
                    $menu = $pages->get('id=2314');
                    include('includes/menu.php');
                ?>
                <?php
                // foreach ($menu as $item){
                //   if ( ($item->hasChildren() ) && ($item->name != "news") ) { //if dropdown
                //     echo "
                //     <li class='nav-item dropdown position-static'>
              		// 		<a class='nav-link dropdown-toggle text-uppercase' href='#' id='$item->name' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>$item->title</a>
              		// 		<div class='dropdown-menu w-100 mt-n4' aria-labelledby='$item->name'>
                //   				<div class='flex'>";
                  				
                //   				    echo "<div>
                //   				        <a class='dropdown-item p-0' href='$item->url'>$item->title</a>
              		// 		        </div>";
                //                     foreach ($item->children as $child){
                //                       echo "<div><a class='dropdown-item' href='$child->url'>$child->title</a>";
                //                       if ($child->template == "landing"){
                //                           foreach ($child->children->find("limit=10") as $grandchild){
                //                               echo "<a class='dropdown-item small display-block' href='$grandchild->url'>$grandchild->title</a>";
                //                           }  
                //                       }
                //                       echo "</div>";
                //                     }
                //                 echo "
                //                 </div>
                //             </div>";
                //   }else{ //no dropdown
                //     echo "<li class='nav-item text-uppercase'><a class='nav-link' href='$item->url'>$item->title</a></li>";
                //   }
                // }
                // echo "</ul>";
                
                // //Edit links for superusers
                // if($user->hasPermission('page-publish')) {
                // 	echo "<span class='nav-item'><a class='nav-link text-edit' href='{$config->urls->admin}page/edit/?id={$page->id}'>Edit</a></span>";
                // 	echo "<span class='nav-item'><a class='nav-link text-edit' href='{$config->urls->admin}page'>All</a></span>";
                // }else{
                // }


                // //Search
                // echo "<span class='nav-item dropdown position-static'>
                // 			<a class='nav-link dropdown-toggle' href='#' id='search' role='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                // 				Search
                // 			</a>

                // 			<div class='dropdown-menu w-100' aria-labelledby='search'>";
                // 				include("searchform.php");
                // 			echo "</div>
                // 	</span>
                // 	";
                  ?>
            </div>
        </nav>
      </div>

    </header>

    <main class="mt-n5 container">
    <?php crumbs($page);?>
