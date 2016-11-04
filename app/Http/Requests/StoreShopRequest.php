<?php

namespace Sneefr\Http\Requests;

class StoreShopRequest extends Request
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
            'name'        => 'sometimes|required|between:3,100',
            'slug'        => 'required_if:terms,1|alpha_dash|between:3,120|unique:shops|not_in:demo,admin,stats,blog,shop,shops,forum,help,faq',
            'description' => 'required',
            'location'    => 'required',
            'latitude'    => 'required|numeric',
            'longitude'   => 'required|numeric',
            'logo'        => 'required_if:terms,1|image',
            'cover'       => 'required_if:terms,1|image',
            'terms'       => 'sometimes|required',
            'tags'        => 'required|array',
        ];
    }

    /**
     * Sanitize input before validation.
     *
     * @return array
     */
    public function sanitize()
    {
        $this->merge([
            'latitude'  => (float) $this->input('latitude'),
            'longitude' => (float) $this->input('longitude'),
        ]);

        return $this->all();
    }
}
