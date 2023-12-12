<?php namespace ProcessWire;
include("includes/header.php"); 

//HERO IMAGE
echo "
<div class='block_header p-0 mb-4 z-0'>";
    $img = $page->image->size(1600, 500);
    echo "<img src='$img->url' class='breakout'/>";
    imgDetails($img);
    echo"
      <div class='block_headerText bg-white container' >";
          echo "
          <h1>{$page->title}</h1>
      </div>
</div>
";

include("fields/blocks.php"); 
include("includes/footer.php"); 
?>
