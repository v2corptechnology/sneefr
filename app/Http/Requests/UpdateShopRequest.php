<?php namespace Sneefr\Http\Requests;

class UpdateShopRequest extends Request
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
            'name'        => 'required|between:3,100',
            'description' => 'required',
            'location'    => 'required',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'logo'        => 'image',
            'cover'       => 'image',
            'category'    => 'required|exists:categories,id',
        ];
    }

    /**
     * Sanitize input before validation.
     *
     * @return array
     */
    public function sanitize()
    {
        $this->offsetUnset('_token');
        $this->offsetUnset('_method');
        $input = $this->all();

        $input['latitude'] = (float) $input['latitude'];
        $input['longitude'] = (float) $input['longitude'];

        $this->replace($input);

        return $this->all();
    }
}
