<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailUpdateRequest extends FormRequest
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
            'postal' => ['nullable', 'string', 'regex:/^\d{7}$/'],
            'address' => ['nullable', 'string', 'max:161'],
            'building' => ['nullable', 'string', 'max:161'],
            'introduction' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'postal.string' => '郵便番号は文字列で入力してください',
            'postal.regex' => '郵便番号は7桁の数字である必要があります',

            'address.string' => '住所は文字列で入力してください',
            'address.max' => '住所は161文字以内で入力してください',

            'building.string' => '建物名は文字列で入力してください',
            'building.max' => '建物名は161文字以内で入力してください',

            'introduction.string' => '自己紹介は文字列で入力してください',
            'introduction.max' => '自己紹介は1000文字以内で入力してください',
        ];
    }
}
