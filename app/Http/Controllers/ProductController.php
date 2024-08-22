<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller {
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request){
        $search = $request->get('search');
        $companyId = $request->get('company_id');

        $query = Product::query();
    
        if (!empty($search)) {
            $query->where('product_name', 'like', "%{$search}%");
        }
    
        if (!empty($companyId)) {
            $query->where('company_id', $companyId);
        }
    
        $products = $query->paginate(10);
        $companies = Company::all();

        return view('products.index', compact('products', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(){
        $companies = Company::all();

        return view('products.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        $request->validate([
            'product_name' => 'required', 
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable', 
            'img_path' => 'nullable|image|max:2048',
    ], [
        'product_name.required' => '商品名は必須項目です。',
        'company_id.required' => 'メーカーを選択してください。',
        'price.required' => '価格を入力してください。',
        'price.integer' => '価格は数値で入力してください。',
        'stock.required' => '在庫数を入力してください。',
        'stock.integer' => '在庫数は数値で入力してください。',
    ]);

        $product = new Product([
            'product_name' => $request->get('product_name'),
            'company_id' => $request->get('company_id'),
            'price' => $request->get('price'),
            'stock' => $request->get('stock'),
            'comment' => $request->get('comment'),
        ]);

        if($request->hasFile('img_path')){ 
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        DB::beginTransaction();

        try {
            $product->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }

        return redirect('products');
    }

    public function show(Product $product){
        return view('products.show', ['product' => $product]);
    }

    public function edit(Product $product){
        $companies = Company::all();

        return view('products.edit', compact('product', 'companies'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product){
        $request->validate([
            'product_name' => 'required',
            'company_id' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'comment' => 'nullable',
            'img_path' => 'nullable|image|max:2048',
    ], [
        'product_name.required' => '商品名は必須項目です。',
        'company_id.required' => 'メーカーを選択してください。',
        'price.required' => '価格を入力してください。',
        'price.numeric' => '価格は数値で入力してください。',
        'stock.required' => '在庫数を入力してください。',
        'stock.numeric' => '在庫数は数値で入力してください。',
    ]);

    DB::beginTransaction();

    try {    
        $product->product_name = $request->product_name;
        $product->company_id = $request->company_id;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;

        if($request->hasFile('img_path')){ 
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->save();

        DB::commit();

        return redirect()->route('products.index')
            ->with('success', 'Product updated successfully');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withErrors(['error' => 'An unexpected error occurred.'])->withInput();
        }
    }

    public function destroy(Product $product){
        DB::beginTransaction();

        try {
            $product->delete();

            DB::commit();

            return redirect('/products')
            ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->withErrors(['error' => 'An unexpected error occurred.']);
        }
    }
}
    