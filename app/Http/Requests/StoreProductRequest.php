<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'product_name' => 'required',
            'company_id'   => 'required',
            'price'        => 'required|integer|min:0',
            'stock'        => 'required|integer|min:0',
            'img_path'     => 'nullable|image',
        ];
    }

    public function messages()
    {
        return [
            'product_name.required' => '商品名を入力してください。',
            'company_id.required'   => 'メーカー名を選択してください。',
            'price.required'        => '価格を入力してください。',
            'price.integer'         => '価格は整数で入力してください。',
            'price.min'             => '価格は0以上で入力してください。',
            'stock.required'        => '在庫数を入力してください。',
            'stock.integer'         => '在庫数は整数で入力してください。',
            'stock.min'             => '在庫数は0以上で入力してください。',
            'img_path.image'        => '商品画像には画像ファイルを選択してください。',
        ];
    }
}