<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'item_id' => ['required', 'integer'],
            'purchaser_id' => ['required', 'integer'],
            'ship_address' => ['required', 'string'],
            'payment_id' => ['required', 'integer'],
            'email' => ['required', 'email'],
        ];
    }

    public function messages()
    {
        return [
            'item_id.required' => '商品IDは必須です',
            'item_id.integer' => '商品IDは整数で入力してください',

            'purchaser_id.required' => '購入者IDは必須です',
            'purchaser_id.integer' => '購入者IDは整数で入力してください',

            'ship_address.required' => '配送先住所が登録されていません',
            'ship_address.string' => '配送先住所は文字列で入力してください',

            'payment_id.required' => '決済IDは必須です',
            'payment_id.integer' => '決済IDは整数で入力してください',

            'email.required' => 'メールアドレスは必須です',
            'email.email' => 'メールアドレスの形式で入力してください',
        ];
    }
}
