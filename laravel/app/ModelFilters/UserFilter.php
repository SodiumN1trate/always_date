<?php

namespace App\ModelFilters;

use EloquentFilter\ModelFilter;

class UserFilter extends ModelFilter
{
    /**
    * Related Models that have ModelFilters as well as the method on the ModelFilter
    * As [relationMethod => [input_key1, input_key2]].
    *
    * @var array
    */
    public $relations = [];

    public function sort($order) {
        $this->orderBy($order[0], $order[1]);
    }

    public function name($name) {
        return $this->whereLike('name', $name);
    }

    public function email($email) {
        return $this->where('email', $email);
    }

    public function age($age) {
        return $this->whereBetween('age', $age);
    }

    public function birthday($birthday) {
        return $this->where('birthday', $birthday);
    }

    public function gender($gender) {
        return $this->where('gender', $gender);
    }

    public function language($language) {
        return $this->whereLike('language', $language);
    }

    public function rating($rating) {
        return $this->whereBetween('rating', $rating);
    }
}
