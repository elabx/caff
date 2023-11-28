<?php
namespace ProcessWire;

//If XML export
if ($input->urlSegment1 == 'xml') {
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="fecs.xml"');
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<fecs>";
    $filteredFields = $templates->get("fec_table")->fieldgroup;
        foreach ($page->children as $fec){
        echo "<fec>";
            foreach ($filteredFields as $filteredField){
            $fieldContent = $fec->$filteredField;
            $fieldContent = str_replace("&", "&#038;", $fieldContent);
            $fieldType = $filteredField->type;
            echo "<{$filteredField}_>";
            if ($fieldType == 'FieldtypePage') {
                  $getPages = $pages->find("id=$fieldContent");
                  $str = "";
                  foreach ($getPages as $id){
                    $str .= "{$id->title},  ";
                  }
                  echo rtrim($str, ",  ");
            }elseif ($fieldType == 'FieldtypeOptions'){
                foreach ($fec->$filteredField as $f) {
                    echo "{$f->title} ";
                }
            }else{
                echo strip_tags($fieldContent);
            }
            echo "</{$filteredField}_>";
         }//foreach fields
       echo "</fec>";
     }//foreach projects
    echo "</fecs>";
    exit();
    }
    
include("includes/header.php"); 
include("includes/submenu.php"); 
$template = $page->template;

//IF tag or main page
if ($page->id == "2073"){ //If main or tag page
    $thisurl = $input->httpUrl();
    $segments = $input->urlSegments();
    //Turn segments into tags
    $refs = new PageArray();
    foreach ($segments as $s){
        $s = $pages->get($s);
        $refs->add($s);
    }
    
    content($page);
    include("fields/blocks.php"); 
     //echo "[To add: search]";

/////FILTER
    $tagparents = $pages->find("id=3417|3423|3460");
    $path = "./";
    
    echo "<div class='row mb-4'>";
    //if match in segments and $tagparents->children
    
    foreach ($tagparents as $tag){
    echo "<div class='col-md'>   
        <select class='form-control small' onchange='location = this.value;'>
            <option selected disabled>{$tag->title}</option>";
            foreach ($tag->children as $child){
                // //$count = $child->references("template=$template")->count;
                $selected = "";
                if($refs->has($child)){
                   $selected = "selected";
                }
                echo "<option value='{$path}/{$child->id}' {$selected}>{$child->title}</option>";
            }
        echo "</select>";
        echo "</div>"; //end col
        
    }
    echo "</div>"; //end row  

   

    //If page segments, show tags
    if(!empty($segments)){

    //TAG-SPECIFIC CONTENT
        
        foreach ($refs as $ref){
            echo "$ref->textarea";
            
            //publications
            if($ref->parent->id == "3467"){ 
                echo "<hr/>
                <div class='row py-4'>";
                    echo "<div class='col-md-2'><a href='$ref->link'><img src='{$ref->image->height(200)->url}' alt='publication'></a></div>";
                    echo "<div class='col-md-2'><a href='$ref->link' class=''>Download the $ref->title</a>";
                        //Edit link
                        if($user->hasPermission('page-publish')) {
                          echo " [<a href='$ref->editUrl'>Edit</a>]";
                        }
                     echo "</div>";
                    echo "<div class='col-md-8'>{$ref->textarea}</div>";
                echo "</div>
                <hr/>"; //end row

            }
            
            //show tags with remove link
            $url = str_replace($ref->id.'/', '', $thisurl);
            echo "<a href='$url' class='btn btn-outline-secondary mb-2 mr-2'>$ref->title X</a>";
               
        }

        //Apply filters
        $a1 = $refs->explode('id');
        $fecpages = $pages->find("parent=2073");
        $fecs = new PageArray();
        foreach ($fecpages as $f){
            $tags = $f->referencing; //all tags of this fec.
            $a2 = $tags->explode('id');
            if (count(array_intersect($a1, $a2)) == count($a1)) {
              $fecs->add($f);
            }
        }
        
        // foreach ($template->fields as $field) {
        //     if ($field->type instanceof FieldtypePage) {
        //         $results = $pages->find("$field=$refs, parent=2073");
        //             $fecs->add($results); 
        //     }
        // }

        pageList($fecs);
        $fecs = "";
    }else{
        //FEC list
        $ecosystems = $pages->find("parent=3417");
        foreach ($ecosystems as $eco){
            echo "<h2 class='border-bottom'>$eco->title</h2>";
            $ecofecs = $pages->find("tag_ecosystem=$eco, sort=sort");
            pageList($ecofecs);
        }
    }


    
}else{ 
    //FEC page
    

    
    echo "<div class='row'>";
        echo "<div class='col-md-7'>";


            echo "<h1>$page->title</h1>
            <div class='font-italic mb-4'>$page->summary</div>";
           
            
                //////// TAGS
                echo "<ul class='list-unstyled small'>";
                foreach ($page->fields as $field) {
                    if ($field->type instanceof FieldtypePage){
                    $tags = $page->$field;
                        if ($tags->count != 0){
                            echo "<li class='py-2 border-bottom'><span class='mr-2'>{$field->label}:</span> ";
                            foreach ($tags as $tag){
                                 echo "<a href='../{$tag->id}/' class='mr-2'>$tag->title</a>";
                            }
                            echo "</li>";
                        }
                        
                    }
                }
                echo "</ul>";
                
        echo "</div>";
        
        
         
        //Image
        echo "<div class='col-md-4 offset-md-1'>";
            imgSize($page, 600, 600);
            echo "<div class='credit py-3'>{$page->image->text}<br/>{$page->image->text2}</div>";
        echo "</div>"; 
    echo "</div>"; //row
    
     include("fields/blocks.php"); 
    
//////// FEC TABLE
    echo "<div class='table-responsive breakout px-5'>
    <a href='./xml'>Download this table as XML</a>
    <table class='table table-striped small'>";
    //Get table fields
    $tabletemplate = $templates->get("fec_table");
    $tablefields = $tabletemplate->fields;
    
    echo "<tr>";
    foreach ($tablefields as $field){
        $fieldLabel = $tabletemplate->fieldgroup->getField($field, true)->label; //Template-specific label
        echo "<th>$fieldLabel</th>";
    }
    echo "</tr>";
    
    foreach ($page->references("template=fec_table") as $item){
        //Edit link
        if($user->hasPermission('page-publish')) {
          $edit = "<a href='$item->editUrl'>{$item->title}</a>";
        }else{
          $edit = $item->title;
        }
        //Parameters
        echo "<tr>";

        foreach ($tablefields as $tf){
            $type = $tf->type;
            echo "<td>";
            //Select fields
            if ($type == "FieldtypeOptions") { 
               foreach ($item->$tf as $t){
                   echo "{$t->title} "; 
               }
            } 
            elseif ($type == "FieldtypePage") { 
               foreach ($item->$tf as $t){
                   echo "{$t->title} "; 
               }
            } 
            elseif ($type == "FieldtypePageTitle"){
                echo $edit;
            }
            else {
                echo $item->$tf; 
            }         
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</div>";
    
     echo"<div class='py-3'>$page->fec_notes</div>";
    
    //List experts
     $experts = $page->references("template=expert");
     if (!empty($experts)){
          echo "<div class='text-center'><h3>Experts</h3></div>";
          people($experts);
     }
}


 



include("includes/footer.php"); 

// $csv = "{$config->urls->files}{$page->id}/fec.csv";
// if($input->urlSegment('download')) {
//     header('Content-type: application/ms-excel');
//     header('Content-Disposition: attachment; filename=fec.csv');
//     include ("$config->paths->templates}scripts/simple_html_dom.php");
    // $html = file_get_contents("$page->httpUrl");
    //$fp = fopen("$csv", "w");
    // foreach($html->find('tr') as $element) {
    //     $td = array();
    //     foreach($element->find('td') as $row) {
    //         $td[] = $row->plaintext;
    //     }
    //     fputcsv($fp, $td);
    // }
    // fclose($fp);
    // $html->clear();
    // unset($html);
// }
?>
