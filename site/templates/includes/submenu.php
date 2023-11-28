<?php
$sibs = $page->siblings;
if($sibs->count >= 2 && $page->numParents() >= 3){

  echo "<div class='submenu bg-light' id='submenu'>";
  echo "<h5><a href='{$page->parent->url}'>{$page->parent->title}</a></h5>";
    foreach ($sibs as $sib){
        if($sib == $page){
          $current = 'current';
        }else{
          $current='';
        }
        echo "<div class='py-1'><a href='{$sib->url}' class='$current'>{$sib->title}</a></div>";
    }
  echo "</div>";
}
?>