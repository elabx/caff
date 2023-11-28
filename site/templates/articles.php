<?php
include("includes/header.php"); 
content ($page);
 
include("fields/blocks.php"); 

$context = $pages->find("template=article, sort=-date, limit=9");
$pagination = $context->renderPager();
echo $pagination;
newsList ($context);
echo $pagination;

//include("fields/blocks_bottom.php"); 
echo'
<section class="block block_break bg bg-transparent">
        <div class=""><div class="w-100 mb-n4"></div></div></section>';
		
echo'<h2>CAFF social media</h2>';

echo'
<section class="block block_break bg bg-transparent">
        <div class=""><div class="w-100 mb-n4"></div></div></section>';

echo'
<script src="https://apps.elfsight.com/p/platform.js" defer></script>
<div class="elfsight-app-12b3e11b-42b6-4629-9775-6d87d3c4414c"></div>
';

 echo
 '<h2>CAFF on FLICKR</h2>';
 echo'
<section class="block block_break bg bg-transparent">
        <div class=""><div class="w-100 mb-n4"></div></div></section>';
 
 echo
 /*
 '<a data-flickr-embed="true" data-header="true" data-footer="true" href="https://www.flickr.com/photos/93387980@N07" title=""><img src="https://live.staticflickr.com/65535/51957161392_1c7575d3e5.jpg" width="600" height="655" alt=""></a><script async src="//embedr.flickr.com/assets/client-code.js" charset="utf-8"></script>';
 */
 
 '<a data-flickr-embed="true" data-header="true" data-footer="true" data-context="true" href="https://www.flickr.com/photos/caff_arctic_biodiversity/49784149503/in/album-72157713926053451/" title="AMBI at CMS COP13, Gandhinagar, India, February 2020"><img src="https://live.staticflickr.com/65535/49784149503_16b0a2218a_b.jpg" width="1024" height="683" alt="AMBI at CMS COP13, Gandhinagar, India, February 2020"/></a><script async src="//embedr.flickr.com/assets/client-code.js" charset="utf-8"></script>';


 include("includes/footer.php");
 
?>
