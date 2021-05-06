<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ApprovalRequests implements Rule
{
    protected $messageCode = null;

    protected $messages = null;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->messages = [
            'invalid_array' => 'The inputs must be a array',
            'required' => 'The input must have user_id, approval_id and order',
            'invalid_order' => 'The order must be a integer',
        ];
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
        if (!is_array($value)) {
            $this->messageCode = 'invalid_array';
            return false;
        }
        foreach ($value as $input) {
            if (!isset($input['user_id']) || !isset($input['order'])) {
                $this->messageCode = 'required';
                return false;
            }
            if (!is_numeric($input['order'])) {
                $this->messageCode = 'invalid_order';
                return false;
            } else {
                if (intval($input['order']) <= 0) {
                    $this->messageCode = 'invalid_order';
                    return false;
                }
            }
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
        if ($this->messageCode) {
            return $this->messages[$this->messageCode];
        }
        return 'The :attribute invalid.';
    }
}
