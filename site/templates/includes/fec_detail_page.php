<?php namespace ProcessWire;

// FEC

?>

<div class='my-4 row'>
    <div class='col-md-7'>

        <h1><?= $page->title ?></h1>
        <div class='font-italic mb-4'><?= $page->summary ?></div>

        <ul class='list-unstyled small'>
            <?php foreach ($page->fields as $field): ?>
                <?php if ($field->type instanceof FieldtypePage): ?>
                    <?php $tags = $page->$field; ?>
                    <?php if ($tags->count != 0): ?>
                        <li class='py-2 border-bottom'><span class='mr-2'><?= $field->label ?>:</span>
                            <?php foreach ($tags as $tag): ?>
                                <a href='../<?= $tag->id ?>/' class='mr-2'>
                                    <?= $tag->title ?>
                                </a>
                            <?php endforeach; ?>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </ul>

    </div>

    <div class='col-md-4 offset-md-1'>
        <?php imgSize($page, 600, 600); ?>
        <div class='credit py-3'><?= $page->image->text ?><br/><?= $page->image->text2 ?></div>
    </div>
</div> <!-- row -->

<?php echo $page->render('blocks'); ?>

<div class='table-responsive breakout px-5'>
    <a href='./xml'>Download this table as XML</a>
    <table class='table table-striped small'>
        <tr>
            <?php
            $tabletemplate = $templates->get("fec_table");
            $tablefields = $tabletemplate->fields->find("name!=title");
            foreach ($tablefields as $field):
                $fieldLabel = $tabletemplate->fieldgroup->find("name!=title")->getField($field, true)->label;
                ?>
                <th><?= $fieldLabel ?></th>
            <?php endforeach; ?>
        </tr>
        <?php foreach ($page->references("template=fec_table") as $item): ?>
            <tr>
                <?php
                if ($user->hasPermission('page-publish')):
                    $edit = "<a href='{$item->editUrl}'>{$item->title}</a>";
                else:
                    $edit = $item->title;
                endif;
                foreach ($tablefields as $tf):
                    $type = $tf->type;
                    echo "<td>";
                    if ($type == "FieldtypeOptions" || $type == "FieldtypePage"):
                        foreach ($item->$tf as $t):
                            echo "{$t->title} ";
                        endforeach;
                    elseif ($type == "FieldtypePageTitle"):
                        echo $edit;
                    else:
                        echo $item->$tf;
                    endif;
                    echo "</td>";
                endforeach;
                ?>
            </tr>
        <?php endforeach; ?>
    </table>
</div>

<div class='py-3'><?= $page->fec_notes ?></div>

<?php
$experts = $page->references("template=expert");
if (!empty($experts)):
    echo "<div class='text-center'><h3>Experts</h3></div>";
    people($experts);
endif;
?>
