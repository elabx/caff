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
            $tablefields = $tabletemplate->fields->find("name!=title")->explode(function($item){
                $new_data = new WireData();
                $new_data->set('name', $item->name);
                $new_data->set('fieldLabel', $item->getLabel());
                return $new_data;
            });
            $tablefields = WireArray::new($tablefields);

            $extreme_events_fake = new WireData();
            $extreme_events_fake->set('name', 'extreme_events');
            $extreme_events_fake->set('fieldLabel', 'Extreme Events');

            $tablefields->insertAfter($extreme_events_fake, $tablefields->findOne('name=fec_priority'));

            foreach ($tablefields as $field):?>
                <th><?= $field->fieldLabel ?></th>
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
                    if($tf->name == "extreme_events"){

                        $cat_fields = $page->tag_ecosystem->implode("|", function($field){
                            return "extreme_event_fec_{$field->name}";
                        });
                        $ev = $pages->find("$cat_fields=$page, template=extreme_event")->implode(function ($item) {
                            return "<li><a href='{$item->title}'>{$item->title}</a></li>";
                        }, ['prepend' => '<ul>', 'append' => '</ul>']);
                        echo "<td>{$ev}</td>";
                        continue;
                    }
                    $tf = $fields->get($tf->name);
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
