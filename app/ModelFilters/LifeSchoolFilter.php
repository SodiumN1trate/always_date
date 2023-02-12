<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class LifeSchoolFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function number($number) {
        return $this->where('number', $number);
    }

    public function gender($gender) {
        return $this->where('gender', $gender);
    }

    public function title($name) {
        return $this->whereLike('title', $name);
    }
}
