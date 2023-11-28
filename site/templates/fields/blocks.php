<?php
namespace ProcessWire;
$depth = 0; // Logic for rows


foreach($page->blocks as $item) {
    
//*** Template for the Blocks field ***//

    if (($item->colour) && ($item->type != 'image_header')){
        $bgcol = $item->colour->name;
    }else{
      $bgcol = "transparent";
    }

    //ROW
    if (($item->depth > $depth) && ($depth == 0)) {
        //If small follows big, start row
		echo "<section class='block {$item->type} bg-transparent'>
        <div class=''>
        <div class='row justify-content-center mb-4'>";
	}elseif(($item->depth < $depth) && ($item->depth == 0)){
        //If big follows small, end row
		echo "</div><!--end row--></div><!--end container--></section>";
		echo "<section class='block {$item->type} bg bg-{$bgcol}'>
        <div class=''>";
	}elseif( ($item->depth == $depth) && ($item->depth == 0) ){
        //Else no row
        echo "<section class='block {$item->type} bg bg-{$bgcol} mb-4'>
        <div class=''>";
    }


	//COLUMN START
	if($item->depth == 1) {
		echo "<div class='col-md'>";
	}elseif($item->depth == 2) {
		echo "<div class='col-md-3'>";
	}
	
	//ROW BREAK
	if($item->type == 'block_break') {
	  echo "<div class='w-100 mb-n4'></div>";
	}
	
	//ACCORDION
	if($item->type == 'block_accordion') {
        $acc_start = "<a class='btn btn-primary text-left p-2 mt-n3 w-100' data-toggle='collapse' href='#collapse{$item->id}' role='button' aria-expanded='false' aria-controls='collapse{$item->id}'>
                <span></span>{$item->title} 
            </a>
            <div class='collapse py-3' id='collapse{$item->id}'>";
        $acc_end = "</div>";
        echo "{$acc_start}{$item->textarea}{$acc_end}";
	}
    
	//CODE
	if($item->type == 'block_code') {
	    echo $item->code;
	}
	
	//FEATURED
    if($item->type == 'block_featured') {
        //If page on site
        if ($item->page_single!=""){
            $feature = $item->page_single;
            $imgicon = "";
            $imgpg = $feature;
            $pglink = $feature->url;
            $target = "";
        //Otherwise link
        }elseif ($item->link!=""){
            $feature = $page;
            $pglink = $item->link;
            $imgpg = $item;
            $imgicon = "<i class='bi bi-box-arrow-up-right'></i>";
            $target = "target='_blank'";
        }
        if ($item->text2!=""){ $altTitle=$item->text2; }else{ $altTitle=$feature->title; }
        if ($item->text!=""){ $btnText="<div class='btn btn-dark mt-1'>{$item->text}</div>"; }else{ $btnText=""; }
        if ($item->summary!=""){ $sumText="<p>$item->summary</p>"; }else{ $sumText=""; }
       
        echo "<a href='{$pglink}' $target class='block-feature-partial bg-gradient'>
        <div class='featured bg-image' style='background-image: linear-gradient(180deg, rgba(51,48,50,.9) 0%, rgba(51,48,50,.8) 30%, rgba(51,48,50,.1) 45%), url(";
            //echo $feature->images->first->size(480,480)->url;
            imgBG($imgpg, 960, 480);
            echo ")'>";
            echo "<div class='p-4'><h5>{$altTitle} {$imgicon}</h5>
            {$sumText}
            {$btnText}
            </div>
        </div></a>";
    }

    //IMAGE
    if($item->type == 'block_image') {

        //Depth 0
        if($item->depth == 0){
            $breakout = 'breakout';
            $img = $item->image->size(1200, 500);
            if ($item->textarea!=""){ $capStyle="linear-gradient(to top, rgba(34, 34, 34, .4), rgba(34, 34, 34, .8)), "; }else{ $capStyle=""; }
            $caption = "<div class='vCenter container h3 text-white'>{$item->textarea}</div>";
            echo "<section class='breakout parallax bg-image blockImage' style='background-image: {$capStyle} url({$img->url});' >
                   {$caption}
            </section>";
            imgDetails($img);
        
        //Depth 1 or 2 (in column)
        }elseif($item->depth >= 1){
            if($item->depth == 1){
                $img = $item->image->width(1200);
            }elseif ($item->depth == 2){
                $img = $item->image->width(480);
            }
            $caption = "<figcaption>{$item->textarea}</figcaption>";
             echo "<figure class='textblock'>";
            if ($item->link!=""){
              echo "<a href='$item->link'><img src='{$img->webp->url}' alt='{$item->text}' loading='lazy' class='img-fluid w-100' '/></a>";
            }else{
              echo "<img src='{$img->webp->url}' alt='{$item->text}' loading='lazy' class='img-fluid w-100' '/>";
            }

			imgDetails($img);
            echo "</figure>
            $caption";
        
        }
        
        unset($breakout);
        unset($imgbg);
    }
    
    //LIST
	if($item->type == 'block_list') {
	    echo"<section class='mb-5'>";
	    if ($item->text){
	        echo "<h3>$item->text</h3>";
	    }
	    if ($item->number){
	        $limit=$item->number;
	    }else{
	        $limit=6;
	    }
		$context = $item->page_single->children("limit=$limit");
		pageList($context);
		echo "</section>";
    }
    
    //NEWS
	if($item->type == 'block_news') {
	    if($item->limit != ""){
	        $limit = $item->limit;
	    }else{
	        $limit = 3;
	    }
	    if ($item->text){
            $title = "<h3>$item->text</h3>";
        }else{
            $title = "";
        }
        $articles = $page->references->find("template=article, limit=$limit");
        echo $title;
	    if($articles->count >= 1){
	        newsList ($articles);
	    }else{
	        $articles = $pages->find("template=article, limit=3, sort=-date");
	        newsList ($articles);
	    }
	    
	    
	}
	
	//PERSON
	if($item->type == 'block_person') {
	    //$people = $page->references->find("template=expert, sort=title");
	    $people = $item->expert;
	    people($people);
	}

    //PROJECTS
	if($item->type == 'block_projects') {
	    $context = $page->references->find("template=project");
	     if ($item->text){
            $title = "<h3>$item->text</h3>";
        }else{
            $title = "";
        }
        echo $title;
        echo "<div class='block_list'>";
	    if($context->count >= 1){
	        pageList($context);
	    }
	    echo "</div>";
	}
	
	//PUBS - SCRAPE OAR
     if($item->type == 'block_pubs') {
        $oarURL = "https://oaarchive.arctic-council.org/";
        $apiBase = $oarURL."server/api/core/";
        $pwLink = $item->link;
        //var_dump($pwLink);
        $url_components = parse_url($pwLink); //get URL parameters

    
         parse_str($url_components['query'], $params); //make the parameters available
    
         if ($item->number!=""){$limit = $item->number;}else{$limit=6;}
        
        //Get type of link
        $arr=explode("/", $pwLink);
        $linkType = $arr[3];
        $id = $arr[4];
        
          echo "<h3><a href='$item->link'>$item->text</a></h3>";
        
         if($linkType == "items"){
          $apiUrl = $apiBase.$linkType."/".$id;
        }elseif($linkType == "browse"){ //Subjects
          $apiUrl = $oarURL."server/api/discover/browses/subject/items?sort=default,dc.date.issued&size=6&filterValue=".$params['value']."&embed=thumbnail";
        }else{
          $apiUrl = $oarURL."server/api/discover/search/objects?scope=".$id."&sort=dc.date.issued,DESC&embed=thumbnail";
        }
        
        //Only get and update the json for superusers
        if ($user->isSuperuser()){
          $json = file_get_contents($apiUrl);
          $item->setAndSave('code', $json);
        }
        
        // //Decode the json
         $data = json_decode($item->code,true);
         echo "<div class='row'>";
        
          //If a single item
          if($linkType == "items"){ 
            $limit = 1;
            $thumbJson = $apiBase."items/".$data[id]."/thumbnail";
            //var_dump($thumbJson);
            $thumb = file_get_contents($thumbJson); 
            $thumb = json_decode($thumb,true);  
            echo "<a href='{$oarURL}items/{$data[id]}' class='pubItem'>      
              <div class='{$thumbClass}'>
                <img src = '{$apiBase}bitstreams/{$thumb[id]}/content' class='img-fluid' alt='thumb'>        
              </div>
              <div class ='pubName'>
                $data[name]
              </div>
            </a>";
    
        
            
             
          }else{
            $i = 0;
            //If subject, author, etc.
            if($linkType == "browse"){
              $results = $data[_embedded][items];
              foreach ($results as $r){
                pubItem($apiBase, $r);
                if (++$i == $limit) break;
              }
            }else{
              //If collection
              $results = $data[_embedded][searchResult][_embedded][objects];
              foreach ($results as $result){
                $r = $result[_embedded][indexableObject];
                pubItem($apiBase, $r);      
                if (++$i == $limit) break;  
              }
            
            }
          }
        
        echo "</div>";
    
        }
     
     
    //SOCIAL
    if($item->type == 'block_social') {
        if ($item->text!=""){
            echo "<h3>$item->text</h3>";
            
        }
        echo "<div class='social'>";
        foreach($item->social_icons as $social) {
        	$network = $social->social_select->title;
        	$username = $social->text;
        	echo "<li><div class='text-center d-inline-block'>
        		<div class=' mb-3'>
        		<a href='http://$network.com/$username'>
        			<i aria-hidden='true' class='bi bi-{$network} h3' title='{$network}'></i>
        			<span class='mr-4'>{$network}<span>
        		</a>
        	</div>";
        	echo "</div>";
        	}
        echo "</div></li>";
    }
    
    //TEXT
	if($item->type == 'block_text') {
    echo "<div class='bg bg-{$bgcol} py-4'>
        <div class='textblock depth-{$item->depth}'>{$item->textarea}</div>
    </div>";
	}
	
	
	//VIDEO
	if($item->type == 'block_video') {
        $vidURL = $item->link;
        if($item->ratio_select != ""){ $vidRatio = $item->ratio_select->value; }else{ $vidRatio = "16by9"; } //ratio
        if($item->checkbox == 1){ $vidBreakout = "breakout"; }else{ $vidBreakout = ""; } //breakout
        $vidEmbed = $vidURL;
		//Vimeo
		if (strpos($vidURL, 'vimeo') !== false) { 
            //$arr = explode("/", $vidURL); //vas like this 13.10.2023 HH
			$arr = explode("video/", $vidURL);
			//$vidID = $arr[3]; //vas like this 13.10.2023 HH
            $vidID = $arr[1];
            //var_dump($vidID);
			$vidEmbed = "https://player.vimeo.com/video/{$vidID}";
        
            // if (strpos($vidURL, 'vimeo.com') !== false) {
            //     $arr = explode("vimeo.com/", $vidURL);
            //     $vid = $arr[1];
            //     var_dump($vid);
            //     $vidEmbed = "https://player.vimeo.com/video/{$vid}";
            // }
        }
      
        //Vimeo Original
		// elseif (strpos($vidURL, 'vimeo/'.$is_int()) !== false) { 
        //     $arr = explode("/", $vidURL);
        //     $vidID = $arr[3];
        //     $vidEmbed = "https://player.vimeo.com/video/{$vidID}";
        // }
        
		//Youtu.be
		elseif (strpos($vidURL, 'youtu.be') !== false) { 
			//https://youtu.be/ryeAbw_hj5E
			$arr = explode(".be/", $vidURL);
			$vidID = $arr[1];
			$vidEmbed = "https://www.youtube-nocookie.com/embed/{$vidID}";
			$vidThumb = "https://img.youtube.com/vi/{$vidID}/0.jpg";
		}
		//Youtube
		elseif (strpos($vidURL, 'youtube.com/watch?v=') !== false) { 
			//https://youtu.be/ryeAbw_hj5E
			$arr = explode("v=", $vidURL);
			$vidID = $arr[1];
			$vidEmbed = "https://www.youtube-nocookie.com/embed/{$vidID}";
		}
        
		echo "<div class='embed-responsive embed-responsive-{$vidRatio} {$vidBreakout}'><iframe class='embed-responsive-item' src='{$vidEmbed}' loading='lazy' frameborder='0' allowfullscreen></iframe></div>";
    }
    
	//END
	if($item->depth >= 1) {
		echo "</div><!--end col-->"; //If in a row
	}else{
		echo "</div></section><!--end {$item->type}-->"; //If full size
	}


	$depth = $item->depth; //reset depth

}
echo "</div>";

?>

