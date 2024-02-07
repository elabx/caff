<?php namespace ProcessWire; ?>
<ul class="nav nav-tabs">
    <?php
    $extreme_events = $pages->get('template=extreme_events');
    $fec_monitoring =  $pages->get('template=fec_monitoring');
    ?>

    <li class="nav-item">
        <a class="nav-link <?=$page->if("template=fec_monitoring", "active")?>" href="<?=$fec_monitoring->url?>">
            <?=$fec_monitoring->title?>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link <?=$page->if("template=extreme_events", "active")?>" href="<?=$extreme_events->url?>">
            <?=$extreme_events->title?>
        </a>
    </li>
</ul>
