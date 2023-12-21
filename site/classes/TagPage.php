<?php

namespace ProcessWire;

class TagPage extends Page
{

    public function array_filter_recursive($input)
    {
        foreach ($input as &$value) {
            if (is_array($value)) {
                $value = $this->array_filter_recursive($value);
            }
        }

        return array_filter($input);
    }

    public function inSearch($param_name){
        $array_values = $this->wire->input->get($param_name);
        if (in_array($this->object->name, $array_values)) {
            return  true;
        } else {
             false;
        }
    }

    public function removeSelfFromURL($param_name)
    {

        $current_url = $this->wire->input->url(true);
        $_url = parse_url($current_url);

        parse_str($_url['query'], $query_str);

        if (array_key_exists($param_name, $query_str)) {
            $query_category = $query_str[$param_name];
            if (in_array($this->name, $query_category)) {
                $key = array_search($this->name, $query_category);
                unset($query_str[$param_name][$key]);
            }
        }

        $query_str = $this->array_filter_recursive($query_str);

        $new_query_string = http_build_query($query_str);

        if ($query_str) {
            $new_url = "{$this->wire->input->url}?{$new_query_string}";
        } else {
            $new_url = $this->wire->input->url;
        }
        return $new_url;
    }
}
