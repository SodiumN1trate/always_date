<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Carbon\Carbon;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

class IsAdult implements Rule
{
    public $message;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Carbon::now()->diff(new Carbon($value))->y <= 18 || Carbon::now() < new Carbon($value) ) {
            $this->message = 'Jūs neesat pilngadīgs.';
            return false;
        } else if (Carbon::now()->diff(new Carbon($value))->y >= 100) {
            $this->message = 'Jūs nevarat būt vecāks par 100 gadiem.';
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return $this->message;
    }
}
