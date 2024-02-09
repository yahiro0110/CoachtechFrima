<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreItemRequest extends FormRequest
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
            'files' => ['required', 'array'],
            'files.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5000'],
            'name' => ['required', 'string', 'max:40'],
            'category_id' => ['required', 'integer'],
            'brand' => ['nullable', 'string', 'max:20'],
            'condition_id' => ['required', 'integer'],
            'description' => ['nullable', 'string', 'max:1000'],
            'price' => ['required', 'integer', 'min:100', 'max:9999999'],
        ];
    }

    public function messages()
    {
        return [
            'files.required' => '少なくとも画像ファイルを1つ選択してください',
            'files.*.image' => '全てのファイルは画像である必要があります',
            'files.*.mimes' => '画像はjpeg, png, jpg形式である必要があります',
            'files.*.max' => '画像のサイズは5MB以下にしてください',

            'name.required' => '商品名は必須です',
            'name.string' => '商品名は文字列で入力してください',
            'name.max' => '商品名は40文字以内で入力してください',

            'category_id.required' => 'カテゴリーは必須です',
            'category_id.integer' => 'カテゴリーは整数で入力してください',

            'brand.string' => 'ブランドは文字列で入力してください',
            'brand.max' => 'ブランドは20文字以内で入力してください',

            'condition_id.required' => '商品の状態は必須です',
            'condition_id.integer' => '商品の状態は整数で入力してください',

            'description.string' => '商品の説明は文字列で入力してください',
            'description.max' => '商品の説明は1000文字以内で入力してください',

            'price.required' => '価格は必須です',
            'price.integer' => '価格は整数で入力してください',
            'price.min' => '価格は¥100以上で入力してください',
            'price.max' => '価格は¥9,999,999以下で入力してください',
        ];
    }
}