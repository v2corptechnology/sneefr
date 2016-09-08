<?php
namespace Sneefr\Http\Requests {

    use Sneefr\Models\Ad;

    class CreateAdRequest extends Request
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
                'category_id'  => 'required|min:1',
                'condition_id' => 'required',
                'location'     => 'required',
                'latitude'     => 'required|numeric',
                'longitude'    => 'required|numeric',
                'shop_id'      => 'sometimes|required|numeric|exists:shops,id,deleted_at,NULL',
                'amount'       => [
                    'required',
                    'min:0.1',
                    'regex:#^\d+(.|,)?\d{0,2}$#isU',
                ],
                'images'       => 'required',
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
         * Get the response for a forbidden operation.
         *
         * @return \Illuminate\Http\Response
         */
        public function forbiddenResponse()
        {
            return back()->withInput();
        }

        /**
         * Sanitize input before validation.
         *
         * @return array
         */
        public function sanitize()
        {
            $input = $this->all();

            // chnage amount to cents
            $input['amount'] = $this->get('amount') * 100;

            // Boolean needed
            $input['is_hidden_from_friends'] = $this->has('is_hidden_from_friends');

            // Normalize delivery input
            $input['delivery'] = Ad::normalizeDeliveryOptions($this);

            // Fill in missing attributes for the ad
            $input['user_id'] = auth()->id();
            $input['currency'] =  trans('common.currency');

            // Ad's cover should not be predicable
            shuffle($input['images']);

            // Trim fields
            array_walk_recursive($input, function(&$in) {
                $in = is_string($in) ? trim($in) : $in;
            });

            // Overrides the input
            $this->replace($input);

            return $this->all();
        }

    }

}
