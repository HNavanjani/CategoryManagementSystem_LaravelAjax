<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidatePhoneNumber implements Rule
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
        $value = str_replace('','',$value);

        if(preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $value)) {
           return true;
          }
          else{
              return false;
          }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Phone number is invalid!';
    }
}
