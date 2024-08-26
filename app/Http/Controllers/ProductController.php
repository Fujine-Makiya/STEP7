<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ArticleRequest;

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
    public function store(ArticleRequest $request){
        $product = new Product();

        if($request->hasFile('img_pass')){
            $filename = $request->img_path->getClientOriginalName();
            $filePath = $request->img_path->storeAs('products', $filename, 'public');
            $product->img_path = '/storage/' . $filePath;
        }

        $product->fill($request->input());

        DB::beginTransaction();

        try {
            $product->save();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => '予期しないエラーが発生しました。'])->withInput();
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
        $product->fill($request->input());

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
        return back()->withErrors(['error' => '予期しないエラーが発生しました。'])->withInput();
    }

    return redirect()->route('products.index')
        ->with('success', '商品が更新されました');
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
    