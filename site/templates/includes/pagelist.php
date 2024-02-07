<?php namespace ProcessWire;
/** @var $context PageArray */
/** @var $options array */
?>
<div class='block_list'>
    <div class='row mb-4'>
        <?php foreach ($context as $child): ?>
            <div class='position-relative col-12 col-md-3 mb-3'>
                <a href='<?= $child->url ?>'>
                    <div class='position-relative'>

                        <img class="w-100 h-100 object-fit-cover" src='<?= $child->image->size(240, 260)->url ?>'/>
                        <div class='headline position-absolute z-2 p-1 bg-dark' style='top:0'>
                            <?= $child->title ?>

                        </div>

                    </div>

                </a>

                <?php if ($options['render_checkboxes']): ?>
                    <div class="uk-position-relative w-100">
                        <div class="page-list-select position-absolute">
                            <label>
                                <span class="sr-only">Select for export</span>
                                <input class=""
                                       name="item[<?= $child->name ?>]"
                                       type="checkbox">
                            </label>
                        </div>
                    </div>
                <?php endif ?>

            </div>
        <?php endforeach; ?>
    </div>
</div>
