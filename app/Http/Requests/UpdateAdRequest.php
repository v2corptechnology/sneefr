<?php
namespace Sneefr\Http\Requests {

    class UpdateAdRequest extends Request
    {

        /**
         * Get the validation rules that apply to the request.
         *
         * @return array
         */
        public function rules()
        {
            return [
                'title'        => 'required|between:3,255',
                'condition_id' => 'required',
                'location'     => 'required',
                'lat'          => 'required|regex:/^[+-]?\d+\.\d+$/',
                'long'         => 'required|regex:/^[+-]?\d+\.\d+$/',
                'shop_id'      => 'integer',
                'tags'        => 'required|array',
                'amount'       => [
                    'required',
                    'min:0.1',
                    'regex:#^\d+(.|,)?\d{0,2}$#isU',
                ],
                'images'          => 'required|array|min:1'
            ];
        }

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
         * Sanitize input before validation.
         *
         * @return array
         */
        public function sanitize()
        {
            $input = $this->all();

            // Rename fields while the ad's columns are not updated
            $input['lat'] = $this->get('latitude');
            $input['long'] = $this->get('longitude');

            // Trim some fields
            $input['title'] = trim($this->get('title'));
            $input['amount'] = trim($this->get('amount'));
            $input['description'] = trim($this->get('description'));
            $input['location'] = trim($this->get('location'));

            if (isset($input['shop_slug'])) {
                if ($input['shop_slug'] == auth()->user()->shop->slug) {
                    unset($input['shop_slug']);

                    $input['shop_id'] = auth()->user()->shop->id;
                }
            }

            $this->replace($input);

            return $this->all();
        }

    }

}
