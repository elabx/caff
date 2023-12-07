<?php namespace ProcessWire;
/////FILTER
$tagparents = $pages->find($filters_selector);
$path = "./";
?>

<?php  //include("fields/blocks.php"); ?>
<form id='filter'
      hx-get='.'
      hx-select='#filter'
      hx-push-url='true'
      hx-target='#filter'
      hx-trigger='change from:#filter-submit'
      class=' w-100' hx-swap='outerHTML'>
    <div class="row">
        <div class="col-md-8">
            <div class="row">
                <?php foreach ($tagparents as $tag): ?>
                    <div class='col-md-4 mb-2'>
                        <select class='tag-select  form-control small'>
                            <option selected disabled><?= $tag->title ?></option>
                            <?php foreach ($tag->children as $child): ?>
                                <?php
                                $selected = "";
                                $disabled = "";
                                $filters = array_values($input->get->array('tag'));
                                if (in_array($child->name, $filters)) {
                                    $disabled = "disabled";
                                }
                                ?>
                                <option value='<?= $child->name ?>' <?= $disabled ?>>
                                    <?= $child->title ?>
                                </option>
                            <?php endforeach ?>
                        </select>

                    </div>
                <?php endforeach ?>
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-end">
                <div>
                    <button type="submit"
                            name="csv"
                            value="1"
                            class="btn btn-primary">Export CSV</button>
                </div>
            </div>
        </div>
        <div class="col-md-12 w-100">
            <select hidden id="filter-submit" name="tag[]" multiple>";
                <?php foreach ($tagparents as $tag): ?>
                    <?php foreach ($tag->children as $tag): ?>
                        <?php $selected = $tag->inSearch('tag') ? "selected" : "" ?>
                        <option <?= $selected ?> value="<?= $tag->name ?>">
                            <?= $tag->title ?>
                        </option>
                    <?php endforeach ?>
                <?php endforeach;?>
            </select >
        </div>
    </div>


<?php
//If page segments, show tags
if(count($input->get->tag)){

    $tags = array_values($input->get->array('tag', 'pageName'));
    foreach($tags as $tag){
        $tag = $pages->get($tag);
        //$tag_url = "";
        $tag_url = $tag->removeSelfFromURL('tag');
        echo "<a href='$tag_url' class='btn btn-outline-secondary mb-2 mr-2'>{$tag->title} <i class=\"bi bi-x\"></i></a>";
    }

    //Apply filters

    $tags = WireArray::new($tags)->implode("|");

    $tagparents = $pages->find($filters_selector);
    $selector = new Selectors();
    foreach($tagparents as $tag){
        // If tags being filtered, found more than 0
        $existing_filter = $tag->children("name=$tags");
        if($existing_filter->count){
            // Add one selector per filter for AND logic
            foreach($existing_filter as $filter){
                $tag_name = $sanitizer->snakeCase($tag->name);
                $selector->add(new SelectorEqual("tag_{$tag_name}", $filter));
            }
        }
    }
    //var_dump($selector);
    $fecpages = $pages->find($selector);

    pageList($fecpages, ['render_checkboxes' => true]);
    $fecs = "";
}else{
    //FEC list
    $categories = $pages->find($categories_selector);
    foreach ($categories as $cat) {
        $items_per_category = $pages->find("$categories_field=$cat, sort=sort");
        if(!$items_per_category->count) continue;
        echo "<h2 class='border-bottom'>$cat->title</h2>";
        pageList($items_per_category, ['render_checkboxes' => true]);
    }

}
?>

</form>
