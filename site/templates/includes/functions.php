<?php

//Edit
function editLink($context, $user){
    if($user->hasPermission('page-edit', $context)) {
        echo " <a href='/pwcaff/admin/page/edit/?id={$context->id}' class='text-edit small'>[Edit]</a>";
    }
}

//Breadcrumbs
function crumbs($context){
	echo "<nav class='' aria-label='breadcrumb'><ol class='breadcrumb bg-transparent p-0 small'>";
	foreach($context->parents as $ancestor) {
        if ($ancestor->id == 1){
          echo "";
        }elseif($ancestor->parent->id == 1){
            echo "";
        }else{
          echo "<li class='breadcrumb-item'><a href='$ancestor->url'>{$ancestor->title}</a></li>";
        }
	}
	echo "</ol></nav>";
}

//Content
function content($page){
    echo "
    <h1 class='my-4'>{$page->title}</h1>
    <div class='textblock pl-0'>
        <p class='lead mb-4'>{$page->summary}</p>
        $page->textarea
    </div>
    ";
}

//Events
function showEvents($events){
    //[to add: if page segment = past, show events before today]
    //$today = time();
    //$events = $context->find("sort=-date, date>=$today");
    echo "
    <ul class='list p-0'>";
    foreach ($events as $event) {
      //date
      $start = date("d M", strtotime($event->date));
      $end = date("d M", strtotime($event->date2));
      $year = date("Y", strtotime($event->date));
      $week = date("W", strtotime($event->date));

      //one day events
      if ( $start == $end) {$end = "";}
      echo "
      <li>
      <div class='row py-4 eventInfo'>
       <div class='col-md-2 border-right text-muted'>
        <div class='h3'>
          {$start}<br/>
          {$end}
        </div>
         Week {$week}<br/>
         {$year}
         </div>
          <div class='col-md-6 pl-md-4'>
            <h4 class='eventTitle h5'>{$event->title}</h4>";
            if ($event->textarea != "") {echo $event->textarea;}
        echo "</div>

      <div class='col-md-4 eventDetails small'>
        <p><i class='bi bi-geo-alt mr-3'></i>{$event->text}</p>
        ";
        if ($event->link != "") {
          echo "<p><i class='bi bi-globe2 mr-3'></i><a href='{$event->link}'target='_blank'>Website</a></p>";
        }
      echo "</div>
      </div>
      </li>";
      }
      echo "</ul>";
}

//Filters
function filters($tagparents, $template){

    echo "<div class='row mb-4'>";
    foreach ($tagparents as $tag){
    echo "<div class='col-md'>   
        <select class='form-control small' onchange='location = this.value;'>
            <option>{$tag->title}</option>";
            foreach ($tag->children as $child){
                $count = $child->references("template=$template")->count;
                echo "<option value='./{$child->id}'>{$child->title} ({$count})</option>";
                unset($count);
            }
        echo "</select>";
        echo "</div>"; //end col
    }
    echo "</div>"; //end row
}

//Image figure and attribution
function imgDetails($img){
  if ($img->text2 != ""){
    $imgCredit = "{$img->text} © {$img->text2}";
  }else{
    $imgCredit = "{$img->text}";
  }
  unset($imgCredit);
}

//Image resize and crop
//imgSize($page->image, 1000, 400);
function imgSize($imgpg, $width, $height){
  echo "
  <picture>";
  $lazy="loading='lazy'";
	if ($imgpg->images != ""){ //If target pg has images
		$img = $imgpg->images->first;
	}elseif ($imgpg->image != ""){ //If target pg has image
		$img = $imgpg->image;
	}else{ // if no image found, placeholder image
		return;
	}
	if ($img != ""){
		$img = $img->size($width, $height);
        //$img_xs = $img->width(480);
        //global $imgDesc;
        $imgDesc = addslashes($img->text);
        if ($img->text != ""){
          $imgCredit = addslashes($img->text2);
          $imgCredit = "© $imgCredit";
        }else{
          $imgCredit = "";
        }

        echo "
        <source srcset='{$img->webp->url}' type='image/webp'>
        <source srcset='{$img->url}' type='image/jpeg'>";
        echo "<img
            src='$img->url'
            class='img-fluid'
            alt='$imgDesc'
            title='$imgDesc $imgCredit'
            $lazy
        />";
	}
      echo "</picture>
  ";
}

//Image background resize and crop
//imgBG($page->image, 1000, 400);
function imgBG($imgpg, $width, $height){
	if ($imgpg->images != ""){ //If target pg has images
		$img = $imgpg->images->first;
	}elseif ($imgpg->image != ""){ //If target pg has image
		$img = $imgpg->image;
	}
	if ($img != ""){
		$img = $img->size($width, $height);
        echo $img->url;
	}
}
//List
function pageList($context){
 echo "<div class='block_list'>
    <div class='row mb-4'>";
	foreach ($context as $child){
	    echo "<div class='col mb-3'>
	        <div class='position-relative'>
    	        <a href='$child->url'>
    		        <img src='{$child->image->size(240, 260)->url}'/>";
    		        echo "<div class='position-absolute z-2 p-1 bg-dark' style='top:0'>$child->title</div>";
        	    echo "</a>
    	    </div>
	    </div>";
	}
	echo "</div>";
echo "</div>";
}

//Newslist
function newsList ($context){
	//$page = wire('page');

    if ($context != ""){
        
foreach($context as $result) {
    echo "<div class='row mb-4'>";
    	echo "<div class='col-md-4'>
    	    <a href='{$result->url}' class='point-tr'>";				
    		imgSize($result, 480, 350);
    	echo "</a></div>
    	<div class ='col-md-8'><h4><a href='{$result->url}'>{$result->title}</a></h4>";
        	truncate($result->summary, 170);
        	echo "<div class='small text-muted mt-2'>{$result->date}</div>";
    	echo "</div>";
	echo "</div>";
}
			
	}
}

//People
function people($people){
	//var_dump($people);
    echo "<ul class='d-flex flex-wrap people list'>";
    foreach ($people as $person){
        // $tags = $person->references();
        // foreach ($tags as $tag){
        //     $taglist .= "<span>$tag->title</span>";
        // }
        $image = $person->images->first->size(125, 125)->url;
        if ($person->state != ""){
            $state = $person->state->first->title;
        }else{$state="";}
        if ($person->observers != ""){
            $observer = $person->observers->first->title;
        }else{$observer="";}

        echo "
        <li class='mb-5 flex-xs'>
            <div class='p-2 m-2 border h-100 text-center'>
                <div class=' mt-n3 mb-2'><a href='$person->url'><img src='$image' alt='$person->title' class='rounded-circle' /></a></div>
                <small class='list-details'>
                    <h5 class='list-name'><a href='$person->url'>$person->title</a></h5>
                    <div class='list-title'><em>$person->text</em></div>      
                    <div>{$state} $observer</div>
					<!-- CAFF people want less information here!
						<div><a href='tel:{$person->text3}'>{$person->text3}</a></div>
						<div><a href='mailto:{$person->email}'>Email</a></div>
						<p>$person->textarea</p>
					 -->
                </small>
            </div>
        </li>";
        //editLink($person, $user);
        unset($state);
        unset($observer);
    }
    echo "</ul>";
    
}

//Pub item
function pubItem($apiBase, $data){
    $oarURL = "https://oaarchive.arctic-council.org/";
	  $thumbJson = $apiBase."items/".$data[id]."/thumbnail";
    $thumb = file_get_contents($thumbJson); 
    $thumb = json_decode($thumb,true);  
    echo "<div class='col-6 col-md-2'>
      <a href='{$oarURL}items/{$data[id]}' class='d-block'>      
          <img src = '{$apiBase}bitstreams/{$thumb[id]}/content' class='border w-100' alt='thumb'>        
      </a>
      <small>$data[name]</small>
    </div>";
}

//Tags to linked titles
function tags($tags){
    foreach ($tags as $tag){
        echo "$tag->title";
    }
}

//Truncate text
function truncate($text, $length){
	if (mb_strlen($text) > $length) {
		$summary = mb_substr($text, 0, $length) . "...";
	} else {
		$summary = $text;
	}
	echo $summary;
}

?>

