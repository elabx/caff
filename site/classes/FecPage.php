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
        foreach($this->templates->get('fex_table')->fieldgroup as $f){
            //$this->fields->get($f);
            /** @var $f Field */
            $headers[] = $f->getLabel();
        }
        return $headers;
    }


    function getCsvDataRow()
    {
        $data = [];
        $fg = $this->templates->get('fex_table')->fieldgroup;
        foreach($this->pages->find("template=fec_table, tag_fec=$this ") as $p){
            foreach($fg as $f){
                switch((string)$f->type){
                    case "FildtypePage":
                        $data[] = $f->getUnformated($f)->explode('title');
                        break;
                    default:
                        $data[] = $p->get($f);
                    default:
                }

            }
        }
        return $data;
    }
}
