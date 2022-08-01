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

    public function withoutUser($id) {
        return $this->where('id', '!=', $id);
    }

    public function firstname($firstname) {
        return $this->whereLike('firstname', $firstname);
    }

    public function lastname($lastname) {
        return $this->whereLike('lastname', $lastname);
    }

    public function email($email) {
        $this->where('email', $email);
    }

    public function age($age) {
        $this->whereBetween('age', $age);
    }

    public function readSchoolExp($exp) {
        $this->whereBetween('read_school_exp', $exp);
    }

    public function birthday($birthday) {
        $this->where('birthday', $birthday);
    }

    public function gender($gender) {
        $this->where('gender', $gender);
    }

    public function language($language) {
        $this->whereLike('language', $language);
    }

    public function rating($rating) {
        $this->where('rating', '>', 0)
            ->whereBetween('rating', $rating);
    }
}
