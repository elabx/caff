<?php
namespace ProcessWire;
use League\Csv\Writer;

//If XML export
if ($input->urlSegment1 == 'xml') {
header('Content-type: text/xml');
header('Content-Disposition: attachment; filename="fecs.xml"');
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<fecs>";
    $filteredFields = $templates->get("fec_table")->fieldgroup;
        foreach ($pages->find("template=fec_table, tag_fec=$page") as $fec){
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

if($input->get->bool('csv')){
    $selected = $input->get->item;
    foreach($selected as $i => $value){
        $selected[$i] = $sanitizer->pageName($value);
    }
    $selected = array_keys($selected);
    /*bd($selected);
    bd($input->post->getArray());*/
    if(count($selected)){
        $selected = implode("|", $selected);
        $items = $pages->find("name=$selected");

        $csv = Writer::createFromFileObject(new \SplTempFileObject());

        /** @var FecPage $first_page */
        $first_page = $items->first();
        //bd($first_page->getCsvHeaders());
        $headers = $first_page->getCsvHeaders();
        $csv->insertOne($headers);
        /** @var FecPage $item */
        foreach($items as $item){
            //bd($item->getCsvDataRow());
            $item_data = $item->getCsvDataRows();
            foreach($item_data as $data) {
                $csv->insertOne($data);
            }
        }
        $csv->output("fecs.csv");
        die;
    }
}


include("includes/header.php");
?>


<?php

if($page->name == "monitoring") {
    echo wireRenderFile('includes/monitoring_menu');
}

$template = $page->template;

//IF tag or main page
//bd($page);
if ($page->template->name == "fec_monitoring"){ //If main or tag page
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

    echo wireRenderFile('includes/filters.php', [
      'filters_selector' => "name=ecosystem|species|habitat",
      'items_selector' => "template=fec",
      'categories_selector' => "parent.path=/tags/ecosystem/",
      'categories_field' => "tag_ecosystem",
    ]);


}else{
    echo wireRenderFile('includes/fec_detail_page');
}


include("includes/footer.php");
?>
