<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,', 'max:5000'],
            'name' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'max:254', Rule::unique(User::class)->ignore($this->user()->id), 'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zAZ]{2,4}$/'],
        ];
    }

    public function messages()
    {
        return [
            'file.image' => 'ファイルは画像である必要があります',
            'file.mimes' => '画像は jpeg, png, jpg 形式である必要があります',
            'file.max' => '画像のサイズは5MB以下にしてください',

            'name.required' => '名前は必須です',
            'name.string' => '名前は文字列で入力してください',
            'name.max' => '名前は20文字以内で入力してください',

            'email.required' => 'メールアドレスは必須です',
            'email.email' => '有効なメールアドレスを入力してください',
            'email.regex' => 'メールアドレスの形式が正しくありません',
            'email.max' => 'メールアドレスは254文字以内で入力してください',
        ];
    }
}
