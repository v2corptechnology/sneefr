<?php

namespace Sneefr\Http\Requests;

use Sneefr\Http\Requests\Request;

class PhoneRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'phone' => 'required|phone:AUTO',
            'task'  => 'required',
            'code_confirm' => 'numeric'
        ];
    }
}
