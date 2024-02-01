<?php

namespace ProcessWire;

class ExtremeEventCategoryPage extends Page
{
    protected $fec_fields = [
      'extreme_event_fec_coastal',
      'extreme_event_fec_freshwater',
      'extreme_event_fec_marine',
      'extreme_event_fec_terrestrial'
    ];
    function getItemsFieldgroup(){
        $fec_fields = implode("|", $this->fec_fields);
        return wire('templates')
          ->get('extreme_event')
          ->fieldgroup
          ->find("name!%$=_END, name!=image|$fec_fields, type!=FieldtypeFieldsetOpen");
    }

    function getCsvHeaders()
    {

        $headers = [];
        $_fields = $this->getItemsFieldgroup();
        foreach($_fields as $f){
            //$this->fields->get($f);
                /** @var $f Field */
            $headers[] = $f->getLabel();
            if($f->name == "extreme_event_expected_temporal_scale") {
                $headers[] = "Priority FECs to monitor";
            }
        }
        return $headers;
    }


    function getCsvDataRows()
    {
        $data = [];
        /** @var Fieldgroup $_fields */
        $_fields = $this->getItemsFieldgroup();

        foreach($this->children as $i => $child){
            foreach($_fields  as $f){

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

                if($f->name == "extreme_event_expected_temporal_scale"){
                    $fecs = new WireArray();

                    foreach ($this->fec_fields as $fec_field) {
                        $value = $child->get($fec_field);
                        if($value) {
                            $fecs->import($value);
                        }
                    }
                    $data[$i][] = $fecs->implode(PHP_EOL, "title");
                }
            }
        }

        return $data;
    }
}
