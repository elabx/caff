<?php

namespace ProcessWire;

class ExtremeEventPage extends Page
{

    function getCsvHeaders()
    {
        $headers = [];
        foreach($this->template->fieldgroup->not("name=image") as $f){
            //$this->fields->get($f);
            /** @var $f Field */
            $headers[] = $f->getLabel();
        }
        return $headers;
    }


    function getCsvDataRows()
    {
        $data = [];
        foreach($this->template->fieldgroup->not('name=image') as $f){
            $type = (string)$f->getFieldtype();
            switch($type){
                case "FieldtypePage":
                    $set_pages = $this->get($f->name);
                    if($set_pages instanceof WireArray){
                        $data[] = $set_pages->implode(',', 'title');
                    }else{
                        $data[]  = $set_pages->title;
                    }
                    break;
               /* case "FieldtypeOptions":
                    $data[] = $this->get($f->name)->title;
                    break;*/
                case "FieldtypeRepeater":
                    $value = $this->get($f->name)->implode(PHP_EOL, function ($item) {
                        return $item->textarea;
                    });
                    $data[] = wire('sanitizer')->lines($value);
                    break;
                default:
                    $data[] = $this->get($f->name);
            }
        }
        return $data;
    }
}
