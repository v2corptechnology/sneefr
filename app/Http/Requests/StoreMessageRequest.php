<?php

namespace Sneefr\Http\Requests;

class StoreMessageRequest extends Request
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
            'body'                 => 'required|string',
            'recipient_identifier' => 'required|alpha_dash',
            'ad_id'                => 'sometimes|required|integer',
            'discussion_id'        => 'sometimes|required|integer',
            'recipient_is_shop'    => 'sometimes|boolean',
        ];
    }
}
