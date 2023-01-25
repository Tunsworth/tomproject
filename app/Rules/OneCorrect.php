<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class OneCorrect implements Rule
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
        $check = $collection->filter( function ($item) {
            if(array_key_exists('correct_answer', $item)){
                if($item['correct_answer'] ==  true){
                    return true;
                }  
            }
        });
        if ($check->count() > 0){
            return true;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'At least one answer must be marked as true';
    }
}
