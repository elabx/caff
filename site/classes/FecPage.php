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
        foreach($this->wire->templates->get('fec_table')->fieldgroup as $f){
            //$this->fields->get($f);
            /** @var $f Field */
            $headers[] = $f->getLabel();
        }
        return $headers;
    }


    function getCsvDataRows()
    {
        $data = [];
        $fg = $this->wire->templates->get('fec_table')->fieldgroup;
        $fecs = $this->wire->pages->find("template=fec_table, tag_fec=$this ");
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
                        $item_data[] = $p->get($f->name)->title;
                        break;
                    default:
                        $item_data[] = $p->get($f->name);
                }
            }
            $data[] = $item_data;
        }
        return $data;
    }
}
