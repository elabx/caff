<?php namespace ProcessWire;

$wire->addHookMethod("Page::inSearch", function ($event) {
    $param_name = $event->arguments(0);
    $array_values = $event->input->get($param_name);
    if (in_array($event->object->name, $array_values)) {
        $event->return = true;
    } else {
        $event->return = false;
    }
});
