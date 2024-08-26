<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
    public function rules() {
        return [
            'product_name' => 'required', 
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable', 
            'img_path' => 'nullable|image|max:2048',
        ];
    }

    /**
     * Get custom attribute names for validation.
     *
     * @return array
     */
    public function attributes() {
        return [
            'product_name' => '商品名',
            'company_id' => 'メーカー',
            'price' => '価格',
            'stock' => '在庫数',
            'comment' => 'コメント',
            'img_path' => '商品画像',
        ];
    }

    /**
     * Get custom error messages for validation.
     *
     * @return array
     */
    public function messages() {
        return [
            'product_name.required' => ':attributeは必須項目です。',
            'company_id.required' => ':attributeを選択してください。',
            'price.required' => ':attributeを入力してください。',
            'price.numeric' => ':attributeは数値で入力してください。',
            'stock.required' => ':attributeを入力してください。',
            'stock.numeric' => ':attributeは数値で入力してください。',
        ];
    }
}