<?php namespace ProcessWire;

if ($input->urlSegment1 == 'xml') {
    header('Content-type: text/xml');
    header("Content-Disposition: attachment; filename=\"extreme_event_{$page->name}.xml\"");
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<extremeEvents>";
    $filteredFields = $page->children->first->fieldgroup->not('name=extreme_event_impacts|extreme_event_impacts_END|extreme_event_detecting_and_monitoring|extreme_event_detecting_and_monitoring_END|extreme_event_section_description|extreme_event_section_description_END|title|image');
    foreach ($page->children as $child) {
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
    <?php
    $table_sections = [
      'description' => [
        'title'  => 'Description of extreme event',
        'fields' => [

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
      'monitoring'  => [
        'title'  => 'Monitoring ecosystem impacts of the extreme event',
        'fields' => [
          'extreme_event_suggested_background',
          'extreme_events_suggested_method_monitor',
          'extreme_event_expected_temporal_scale'
        ]
      ]
    ]
    ?>
    <?php foreach ($page->children as $child): ?>
        <table class='main-table table'>
            <thead>
            <tr>
                <th class="text-left"
                    colspan="<?= count($section['fields']) ?>"><?= $child->title ?></th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($table_sections as $section): ?>
                <tr>
                    <td class="p-0">
                        <table class="small my-0 table table-bordered">

                            <thead>

                            <tr>
                                <th class=" font-bold text-left"
                                    colspan="<?= count($section['fields']) ?>"><?= $section['title'] ?></th>
                            </tr>
                            <tr>
                                <?php foreach ($section['fields'] as $field): ?>
                                    <?php $fieldLabel = $page->children->first->fieldgroup->getField($field,
                                      true)->label; ?>
                                    <td class="">
                                    <span style="">
                                          <?php echo $fieldLabel ?>
                                    </span>
                                    </td>
                                <?php endforeach ?>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>

                                <?php
                                $fields = implode("|", $section['fields']);
                                $fields = $child->fields->find("name=$fields");
                                ?>
                                <?php foreach ($fields as $field): ?>
                                    <td>
                                        <?php
                                        $value = $child->get($field->name);
                                        switch ($value) {
                                            case $value instanceof Page:
                                                echo $value->title;
                                                break;
                                            default:
                                                echo $value;
                                        }
                                        ?>
                                    </td>
                                <?php endforeach; ?>

                            </tr>
                            </tbody>
                        </table>
                    </td>


                    <!-- <td>
                         Reference/Links
                     </td>-->

                </tr>
            <?php endforeach ?>
            <tr>
                <td class="p-0">
                    <table class="table table-bordered small">
                        <thead>
                        <tr>
                            <th class="reference-links">
                                Reference / links
                            </th>
                            <th class="fec-links">
                                Priority FECs to Monitor
                            </th>
                        </tr>
                        </thead>
                        <tr>
                            <td>
                                <?php
                                $value = $child->extreme_event_references->implode(function ($item) {
                                    return "<li>{$item->textarea}</li>";
                                }, ['prepend' => '<ul>', 'append' => '</ul>']);
                                echo $value ?: "No links available";
                                ?>
                            </td>
                            <td>
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
                        </tr>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    <?php endforeach ?>
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
