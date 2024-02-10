<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItemImagesRequest extends FormRequest
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
            'files' => ['nullable', 'array'],
            'files.*' => ['image', 'mimes:jpeg,png,jpg', 'max:5000'],
        ];
    }

    public function messages()
    {
        return [
            'files.*.image' => '全てのファイルは画像である必要があります',
            'files.*.mimes' => '画像はjpeg, png, jpg形式である必要があります',
            'files.*.max' => '画像のサイズは5MB以下にしてください',
        ];
    }

    /**
     * バリデータインスタンスにカスタムバリデーションを追加する
     *
     * @param  \Illuminate\Validation\Validator  $validator
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            // itemImagesの配列が存在し、かつその長さが0であることを確認
            if ($this->has('itemImages') && count($this->input('itemImages')) === 0) {
                // filesが空、または存在しないことを確認
                $files = $this->file('files'); // アップロードされたファイルを取得
                if (empty($files) || count($files) === 0) {
                    // filesが空または存在しない場合、バリデーションエラーを追加
                    $validator->errors()->add('itemImages', '画像ファイルは少なくとも1つ残しておく必要があります');
                }
            }
        });
    }
}
