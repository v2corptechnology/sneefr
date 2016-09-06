<?php namespace Sneefr\Http\Requests;

class CreatePlaceRequest extends Request
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
            'place_id'   => 'required|alpha_dash',
            'place_name' => 'required',
            'location'   => 'required',
        ];
    }
}
