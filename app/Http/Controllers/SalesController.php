<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|integer',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($request->product_id);

            if ($product->stock <= 0) {
                DB::rollBack();

                return response()->json([
                    'message' => '在庫がありません。'
                ], 400);
            }

            $sale = new Sale();
            $sale->product_id = $product->id;
            $sale->save();

            $product->stock = $product->stock - 1;
            $product->save();

            DB::commit();

            return response()->json([
                'message' => '購入しました。',
                'stock' => $product->stock
            ], 200);

} catch (\Exception $e) {
    DB::rollBack();

    return response()->json([
        'message' => '購入処理に失敗しました。'
    ], 500);
}
    }
}