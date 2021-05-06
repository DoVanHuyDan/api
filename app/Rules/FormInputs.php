<?php

namespace App\Rules;

use App\Models\FormInput;
use Illuminate\Contracts\Validation\Rule;

class FormInputs implements Rule
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
            'required' => 'The input must have order, label, type',
            'invalid_order' => 'The order must be a integer',
            'invalid_type' => 'The type must be one of '.implode(', ', FormInput::inputTypesSupported()),
            'invalid_value_list' => 'The value_list is required and must be a array',
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
            if (!isset($input['order']) || !isset($input['label']) || !isset($input['type'])) {
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
            if (!in_array($input['type'], FormInput::inputTypesSupported())) {
                $this->messageCode = 'invalid_type';
                return false;
            }
            if (in_array($input['type'], [FormInput::INPUT_TYPE_RADIO, FormInput::INPUT_TYPE_CHECKBOX, FormInput::INPUT_TYPE_SELECT_ONE, FormInput::INPUT_TYPE_MULTIPLE_SELECT])) {
                if (!isset($input['value_list']) || !is_array($input['value_list']) || count($input['value_list']) === 0) {
                    $this->messageCode = 'invalid_value_list';
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
