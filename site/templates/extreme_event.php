<?php namespace ProcessWire;


if ($input->urlSegment1 == 'xml') {
    header('Content-type: text/xml');
    header("Content-Disposition: attachment; filename=\"extreme_event_{$page->name}.xml\"");
    echo "<?xml version='1.0' encoding='UTF-8'?>";
    echo "<extremeEvents>";
    $filteredFields = $page->fieldgroup->not('name=title|image');
    echo "<extremeEvent>";
    echo "<name>{$page->title}</name>";
    foreach ($filteredFields as $filteredField) {
        $fieldContent = $page->get($filteredField->name);
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
            foreach ($fec->$filteredField as $f) {
                echo "{$f->title} ";
            }
        } else {
            echo strip_tags($fieldContent);
        }
        echo "</{$filteredField}_>";
    }
    echo "</extremeEvent>";//foreach projects
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
    <table class='table table-striped small'>
        <?php
        $table_sections = [
          'description' => [
            'title'  => 'Description of extreme event',
            'fields' => [
              'title',
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
          ]/*,
          'links'       => [
            'title'  => 'Reference/links',
            'fields' => 'extreme_event_references'
          ]*/
        ]
        ?>
        <thead>
        <?php foreach ($table_sections as $section): ?>
            <td>
                <span class="text-center d-block w-100 font-weight-bold">
                    <?= $section['title'] ?>
                </span>
                <table>
                    <thead>
                    <?php foreach ($section['fields'] as $field): ?>
                        <?php $fieldLabel = $page->fieldgroup->getField($field, true)->label; ?>
                        <td class=" px-0">
                            <span style="min-height:80px; display:block; min-width:300px;">
                                  <?= $fieldLabel ?>
                            </span>

                        </td>
                    <?php endforeach ?>
                    </thead>
                    <tbody>
                    <?php
                    $fields = implode("|", $section['fields']);
                    $fields = $page->fields->find("name=$fields");
                    ?>
                    <?php foreach ($fields as $field): ?>
                        <td>
                            <?php
                            $value = $page->get($field->name);
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
                    </tbody>
                </table>
            </td>
        <?php endforeach; ?>
        <td>
            <span class="text-center font-weight-bold">References/links</span>
            <table>
                <thead>
                    <td class="px-0">
                        <span style="min-height:80px; display:block; min-width:300px;">&nbsp</span>
                    </td>
                </thead>

                <tbody>
                    <td>
                        <?php
                        $value = $page->extreme_event_references->implode(function ($item) {
                            return "<li>{$item->textarea}</li>";
                        }, ['prepend' => '<ul>', 'append' => '</ul>']);
                        echo $value;
                        ?>
                    </td>
                </tbody>
            </table>
        </td>

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
