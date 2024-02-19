<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
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
            'message' => ['required', 'string', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'message.required' => 'コメントを入力してください',
            'message.string' => 'コメントは文字列で入力してください',
            'message.max' => 'コメントは1000文字以内で入力してください',
        ];
    }
}
