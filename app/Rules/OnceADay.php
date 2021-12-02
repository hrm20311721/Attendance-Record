<?php

namespace App\Rules;

use App\Models\Kid;
use Illuminate\Contracts\Validation\Rule;
use App\Models\Record;

class OnceADay implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Int $kid_id)
    {
        $this->kid_id = $kid_id;
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
        $today = date('Y-m-d');
        $recordTodayCount = Record::whereDate($attribute, '=', $today)->where('kid_id',$this->kid_id)->count();
        return $recordTodayCount == 0;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'すでに記録されています。';
    }
}
