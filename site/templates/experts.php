<?php
include("includes/header.php"); 
?>
<script src="<?php echo $config->urls->templates;?>scripts/lists.js"></script> 
 
<?php
//PAGE CONTENT
content($page);

//Get tags for dropdowns


echo "<div id='peopleList'>



    <div class='form-row pb-4' mb-4>
        <div class='col-md-4'>
            <input class='search form-control border-bottom' placeholder='Start typing to filter list'/>
        </div>
        <div class='col-md-2'>
            <div class='sort btn form-control bg-light' data-sort='list-name'>Sort by name</button></div>
        </div>
    </div>";
        //Sort by tags - this could be converted to a function.
        
        $tagparents = $pages->find("id=1032|1034|1036");
        $template = "expert";
        filters($tagparents, $template);

    //     $tags = $pages->find("id=1032|1034|1036");
    //     $template = "expert";
    //     foreach ($tags as $tag){
    //     echo "<div class='col-md'>   
    //         <select class='form-control small' onchange='location = this.value;'>
    //             <option>{$tag->title}</option>";
    //             foreach ($tag->children as $child){
    //                 $count = $child->references("template=$template")->count;
    //                 echo "<option value='./{$child->id}'>{$child->title} ({$count})</option>";
    //                 unset($count);
    //             }
    //         echo "</select>";
    // echo "</div>";
    // }
    // echo "</div>";


    //If tag for country, etc, show tag and limit list
    if ($input->urlSegment1 != ""){
        $filter = $pages->get("id=$input->urlSegment1");
        echo "<div class='p-4'>Showing: $filter->title <a href='$page->url'><i class='bi bi-x-circle'></i></a></div>";
        $experts = $pages->find("template=$template, state|pps|observers=$filter");
    //Else show full list
    }else{
        $experts = $page->children;
    }

people($experts);

echo "</div>";
?>
<script>
var options = {
    valueNames: [ 'list-name', 'list-title', 'list-details' ]
};

var peopleList = new List('peopleList', options);
</script>

<?php
include("includes/footer.php"); 
?>
