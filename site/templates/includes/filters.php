<?php namespace ProcessWire;
/////FILTER
$tagparents = $pages->find("id=3417|3423|3460");
$path = "./";
?>


<form id='filter'
      hx-get='.'
      hx-select='#filter'
      hx-push-url='true'
      hx-target='#filter'
      hx-trigger='change from: find #filter-submit'
      class='row mb-4 w-100' hx-swap='outerHTML'>
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
                <button hx-post="." hx-vals='{"csv": "1"}'
                        hx-include="[name^=item]"
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
        </select>
    </div>
</form>
