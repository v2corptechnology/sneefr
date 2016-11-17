<?php

namespace Sneefr\Http\Requests;

use Sneefr\Models\Ad;

class StoreItemRequest extends Request
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
            'title'     => 'required|between:3,255',
            'location'  => 'required',
            'latitude'  => 'required|regex:/^[+-]?\d+\.\d+$/',
            'longitude' => 'required|regex:/^[+-]?\d+\.\d+$/',

            'shop_id'  => 'required|integer|exists:shops,id,deleted_at,NULL',
            'quantity' => 'required|numeric|between:1,100',
            'tags'     => 'required|array|min:1|max:2',
            'amount'   => [
                'required',
                'min:1',
                'regex:#^\d+(.|,)?\d{0,2}$#isU',
            ],
            'images'   => 'required|array|min:1',
        ];
    }

    /**
     * Sanitize input before validation.
     *
     * @return array
     */
    public function sanitize()
    {
        $input = $this->all();

        // Change amount to cents
        $input['amount'] = $this->get('amount') * 100;
        if (isset($input['data']['tag_price'])) {

            // Change amount to cents
            $tagprice = $input['data']['tag_price'] * 100;

            if (!$tagprice) {
                unset($input['data']['tag_price']);
            } else {
                $input['data']['tag_price'] = $tagprice;
            }
        }

        // Normalize delivery input
        $input['delivery'] = Ad::normalizeDeliveryOptions($this);

        // Fill in missing attributes for the ad
        $input['user_id'] = auth()->id();
        $input['currency'] = trans('common.currency');
        $input['remaining_quantity'] = $this->get('quantity');

        // Ad's cover should not be predicable
        if (isset($input['images'])) {
            shuffle($input['images']);
        }

        // Trim fields
        array_walk_recursive($input, function (&$in) {
            $in = is_string($in) ? trim($in) : $in;
        });

        // Overrides the input
        $this->replace($input);

        return $this->all();
    }
}
