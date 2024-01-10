<?php

namespace ProcessWire;

class ExtremeEventCategoryPage extends Page
{
    function getItemsTemplate(){
        return wire('templates')->get('extreme_event');
    }

    function getCsvHeaders()
    {
        $headers = [];
        foreach($this->getItemsTemplate()->fieldgroup->not("name=image") as $f){
            //$this->fields->get($f);
            /** @var $f Field */
            $headers[] = $f->getLabel();
        }
        return $headers;
    }


    function getCsvDataRows()
    {
        $data = [];
        foreach($this->children as $i => $child){
            foreach($this->getItemsTemplate()->fieldgroup->not('name=image') as $f){
                $type = (string)$f->getFieldtype();
                switch($type){
                    case "FieldtypePage":
                        $set_pages = $child->get($f->name);
                        if($set_pages instanceof WireArray){
                            $data[$i][] = $set_pages->implode(',', 'title');
                        }else{
                            $data[$i][]  = $set_pages->title;
                        }
                        break;
                    /* case "FieldtypeOptions":
                         $data[] = $this->get($f->name)->title;
                         break;*/
                    case "FieldtypeRepeater":
                        $value = $child->get($f->name)->implode(PHP_EOL, function ($item) {
                            return $item->textarea;
                        });
                        $data[$i][] = wire('sanitizer')->lines($value);
                        break;
                    default:
                        $data[$i][] = $child->get($f->name);
                }
            }
        }

        return $data;
    }
}
