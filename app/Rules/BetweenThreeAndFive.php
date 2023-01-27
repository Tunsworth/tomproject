<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class BetweenThreeAndFive implements Rule
{
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
        $collection = collect($value);
        if($collection->count() >=3 && $collection->count() <=5 ){
            return true;
        };
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return '3 to 5 answers must be provided';
    }
}
