<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');
        $stockMin = $request->get('stock_min');
        $stockMax = $request->get('stock_max');
        $direction  = $request->input('direction', 'desc');
        $column = $request->input('sort', 'id');

        $direction = ($direction === 'desc') ? 'asc' : 'desc';

        $query = Product::join('companies', 'products.company_id', '=', 'companies.id')
                    ->select('products.*', 'companies.company_name') // company_nameを取得
                    ->with('company');
    
        if (!empty($search)) {
            $query->where('product_name', 'like', "%{$search}%");
        }
    
        if (!empty($companyId)) {
            $query->where('company_id', $companyId);
        }

        if (!empty($priceMin)) {
            $query->where('price', '>=', $priceMin);
        }

        if (!empty($priceMax)) {
            $query->where('price', '<=', $priceMax);
        }

        if (!empty($stockMin)) {
            $query->where('stock', '>=', $stockMin);
        }
        if (!empty($stockMax)) {
            $query->where('stock', '<=', $stockMax);
        }

        if ($column === 'company_name') {
            $query->orderBy('companies.company_name', $direction);
        } else {
            $query->orderBy($column, $direction);
        }
    
        $products = $query->paginate(10);
        $companies = Company::all();
       

        return view('products.index', compact('products', 'companies', 'column', 'direction'));
    }

    public function sorted(Request $request){
        $direction  = $request->input('direction', 'desc');
        $column = $request->input('sort', 'id');
        $products = Product::orderBy($direction, $column)->paginate(10);
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

        if($request->hasFile('img_path')){
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
        return view('products.show', compact('product'));
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
    public function update(ArticleRequest $request, Product $product){
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

    return redirect()->route('products.edit', $product->id)
        ->with('success', '商品が更新されました')
        ->withErrors([]);
    }

    public function destroy(Product $product){
    try {
        $product->delete();

        return response()->json([
            'success' => true,
            'message' => '商品が削除されました。'
        ]);
    } catch (\Exception $e) {
        Log::error('削除中にエラー発生', ['error' => $e->getMessage()]);
        return response()->json([
            'success' => false,
            'message' => '削除中にエラーが発生しました。',
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function productSearch(Request $request)
    {Log::info($request);


        $search = $request->input('search', '');
        $companyId = $request->input('company_id');
        $priceMin = $request->input('price_min');
        $priceMax  = $request->input('price_max');
        $stockMin = $request->input('stock_min');
        $stockMax = $request->input('stock_max');
        
        try {
            $query = Product::with('company');
            if (!empty($search)) {
    $query->where(function ($q) use ($search) {
        $q->where('product_name', 'like', '%' . $search . '%')
          ->orWhere('description', 'like', '%' . $search . '%');
    });
}

            if (!empty($companyId)) {
                $query->where('company_id', $companyId);
            }

            if (!empty($priceMax)) {
                $query->where('price', '<=', $priceMax);
            }

            if (!empty($priceMin)) {
                $query->where('price', '>=', $priceMin);
            }

            if (!empty($stockMax)) {
                $query->where('stock', '<=', $stockMax);
            }

            if (!empty($stockMin)) {
                $query->where('stock', '>=', $stockMin);
            }

            $products = $query->paginate(15);
            Log::info($products);

        return response()->json($products);
        } catch (\Exception $e) {
            \Log::error('Search error: ' . $e->getMessage());
            return redirect()->route('products.index')->with('error', '検索中にエラーが発生しました。');
        }
    }
}