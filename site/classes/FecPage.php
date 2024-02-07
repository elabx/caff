<?php

namespace ProcessWire;

class FecPage extends Page
{
    /*protected $fields_for_csv = [
      'tag_ecosystem',
      'tag_habitat',
      'tag_species',
      'fec_pub',
      'fex_ama',
      'fec_notes'
    ];*/
    function getCsvHeaders()
    {
        $headers = [];
        foreach($this->wire->templates->get('fec_table')->fieldgroup->find("name!=tag_fec") as $f){
            //$this->fields->get($f);
            /** @var $f Field */
            $headers[] = $f->getLabel();
            if($f->name == "fec_priority") {
                $headers[] = "Extreme Events";
            }
        }
        return $headers;
    }


    function getCsvDataRows()
    {
        $data = [];
        $fg = $this->wire->templates->get('fec_table')->fieldgroup->find("name!=tag_fec");
        $fecs = $this->wire->pages->find("template=fec_table, fecs_group=$this->id");
        //bd($fecs);
        foreach($fecs as $p){
            $item_data = [];
            foreach($fg as $f){

                $type = (string)$f->getFieldtype();
                switch($type){
                    case "FieldtypePage":
                        $set_pages = $p->get($f->name);
                        $item_data[] = $set_pages->implode(',','title');
                        break;
                    case "FieldtypeOptions":
                        $value = $p->get($f->name);
                        if($value instanceof WireArray){
                            $item_data[] = $value->implode(PHP_EOL, 'title');
                        }else{
                            $item_data[] = $value->title;
                        }
                        break;
                    default:
                        $item_data[] = $p->get($f->name);
                }

                if($f->name == "fec_priority") {
                    //bd($this->tag_ecosystem);
                    $cat_fields = $this->tag_ecosystem->implode("|", function($field){
                        return "extreme_event_fec_{$field->name}";
                    });
                    $item_data[] = wire('pages')
                      ->find("$cat_fields=$this, template=extreme_event")
                      ->implode(PHP_EOL, "title");
                }
            }
            $data[] = $item_data;
        }
        return $data;
    }
}
