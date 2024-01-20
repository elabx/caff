<?php namespace ProcessWire;

if ($input->urlSegment1 == 'xml') {
    header('Content-type: text/xml');
    header("Content-Disposition: attachment; filename=\"extreme_event_{$page->name}.xml\"");
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<extremeEvents>";
    $filteredFields = $page->children->first->fieldgroup->not('name=extreme_event_impacts|extreme_event_impacts_END|extreme_event_detecting_and_monitoring|extreme_event_detecting_and_monitoring_END|extreme_event_section_description|extreme_event_section_description_END|title|image');
    foreach ($page->children as $child){
        echo "<extremeEvent>";
        echo "<name>{$child->title}</name>";
        foreach ($filteredFields as $filteredField) {
            $fieldContent = $child->get($filteredField->name);
            $fieldContent = str_replace("&", "&#038;", $fieldContent);
            $fieldType = $filteredField->type;
            echo "<{$filteredField->name}_>";
            if ($fieldType == 'FieldtypePage') {
                $getPages = $pages->find("id=$fieldContent");
                $str = "";
                foreach ($getPages as $id) {
                    $str .= "{$id->title},  ";
                }
                echo rtrim($str, ",  ");
            } elseif ($fieldType == 'FieldtypeOptions') {
                foreach ($child->$filteredField as $f) {
                    echo "{$f->title} ";
                }
            } else {
                echo strip_tags($fieldContent);
            }
            echo "</{$filteredField->name}_>";
        }
        echo "</extremeEvent>";//foreach projects
    }

    echo "</extremeEvents>";
    exit();
}


include("includes/header.php");

?>


<div class='my-4 row'>
    <div class='col-md-7'>

        <h1><?= $page->title ?></h1>
        <div class='font-italic mb-4'><?= $page->extreme_event_description ?></div>

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
    <table class='table table-bordered table-striped small'>
        <?php
        $table_sections = [
          'monitoring'  => [
            'title'  => 'Monitoring ecosystem impacts of the extreme event',
            'fields' => [
              'title',
              'extreme_event_suggested_background',
              'extreme_events_suggested_method_monitor',
              'extreme_event_expected_temporal_scale',
              'fecs'
            ]
          ],

          'description' => [
            'title'  => 'Description of extreme event',
            'fields' => [
              /*'title',*/
              'extreme_event_description',
              'tag_temporal_scale',
              'tag_spatial_scale',
              'extreme_event_types_of_impact'
            ]
          ],
          'detecting'   => [
            'title'  => 'Detecting and monitoring the extreme event itself',
            'fields' => [
              'extreme_event_definition',
              'extreme_event_variable',
              'extreme_event_monitor_method',
            ]
          ],
        ]
        ?>
        <tr>
            <?php foreach ($table_sections as $i => $section): ?>
                <td class="table-section-<?=$i?>" colspan="<?= count($section['fields']) ?>" class="">
                        <span class="d-block w-100 font-weight-bold">
                            <?= $section['title'] ?>
                        </span>
                </td>
            <?php endforeach; ?>
            <td>&nbsp</td>
        </tr>

        <tr>
            <?php foreach ($table_sections as $i => $section): ?>
                <?php foreach ($section['fields'] as $field): ?>
                    <?php
                    $fieldLabel = $page->children->first->fieldgroup->getField($field, true)->label;
                    if($field == "fecs"){
                        $fieldLabel = "Priority FECs to monitor";
                    }
                    ?>
                    <td class="table-section-<?=$i?>" style="font-size:12px; font-weight: bold;min-width:200px;">
                            <span>
                                  <?php
                                  if($field == "title"){
                                      echo "";
                                  }else {
                                      echo $fieldLabel;
                                  }
                                  ?>
                            </span>
                    </td>
                <?php endforeach ?>
            <?php endforeach ?>
            <td class="table-section-references" style="font-size:12px; font-weight: bold;min-width:200px;">
                Reference/Links
            </td>

        </tr>

        <?php foreach ($page->children as $child): ?>
            <tr>
                <?php foreach ($table_sections as $i => $section): ?>
                    <?php
                    /*$fields = implode("|", $section['fields']);
                    $fields = $child->fields->find("name=$fields");*/
                    ?>
                    <?php foreach ($section['fields'] as $field): ?>
                        <?php

                        $styles = [];
                        if($field == "title"){
                            $styles[] = "font-weight:bold";
                        }
                        $styles = implode(';', $styles);
                        ?>
                        <?php if($field != "fecs"):?>
                            <td class="table-section-<?=$i?>" style="<?=$styles?>">
                                <?php

                                $value = $child->get($field);
                                switch ($value) {
                                    case $value instanceof Page:
                                        echo $value->title;
                                        break;
                                    default:
                                        echo $value;
                                }
                                ?>
                            </td>
                        <?php else:?>
                            <td class="table-section-<?=$i?>">
                                <?php
                                $fecs = new WireArray();
                                $fec_fields = [
                                  'extreme_event_fec_coastal',
                                  'extreme_event_fec_freshwater',
                                  'extreme_event_fec_marine',
                                  'extreme_event_fec_terrestrial'
                                ];
                                foreach ($fec_fields as $fec_field) {
                                    $fecs->import($child->get($fec_field));
                                }
                                echo $fecs->implode(function ($item) {
                                    return "<li><a href='{$item->url}'>{$item->title}</a></li>";
                                }, ['prepend' => '<ul>', 'append' => '</ul>']);
                                ?>
                            </td>
                        <?php endif ?>

                    <?php endforeach; ?>
                <?php endforeach ?>
                <td>
                    <?php
                    $value = $child->extreme_event_references->implode(function ($item) {
                        return "<li>{$item->textarea}</li>";
                    }, ['prepend' => '<ul>', 'append' => '</ul>']);
                    echo $value;
                    ?>
                </td>
            </tr>
        <?php endforeach ?>

        </thead>


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


<?php echo include("includes/footer.php"); ?>
