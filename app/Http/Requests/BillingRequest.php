<?php

namespace Sneefr\Http\Requests;

class BillingRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'stripeToken' => 'required_if:secure,true|alpha_dash',
            'plan'        => 'sometimes|required|string',
        ];
    }
}
