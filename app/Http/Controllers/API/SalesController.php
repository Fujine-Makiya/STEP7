<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function purchase(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {
            $product = Product::lockForUpdate()->find($request->product_id);

            if ($product->stock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => '在庫が不足しています。'
                ], 400);
            }

            $product->stock -= $request->quantity;
            $product->save();

            Sale::create([
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'sold_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => '購入が完了しました。'
            ]);
        });
    }
}