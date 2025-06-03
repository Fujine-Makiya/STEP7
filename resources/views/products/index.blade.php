@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品一覧画面</h1>

    <div class="search mt-5">
        <form id="search-form" method="GET" class="row g-3" data-search-url="{{ route('products.search') }}">
            <div class="col-sm-12 col-md-3">
                <input type="text" name="search" class="form-control" placeholder="検索キーワード" value="{{ request('search') }}">
            </div>            

            <div class="col-sm-12 col-md-3">
                <select class="form-select" id="company_id" name="company_id">
                    <option value="">メーカー名</option>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-sm-12 col-md-2">
                <input type="number" name="price_min" class="form-control" placeholder="価格（下限）" value="{{ request('price_min') }}">
            </div>
            <div class="col-sm-12 col-md-2">
                <input type="number" name="price_max" class="form-control" placeholder="価格(上限)" value="{{ request('price_max') }}">
            </div>

            <div class="col-sm-6 col-md-3">
                <input type="number" name="stock_min" class="form-control" placeholder="在庫数（下限）" value="{{ request('stock_min') }}">
            </div>
            <div class="col-sm-6 col-md-3">
                <input type="number" name="stock_max" class="form-control" placeholder="在庫数（上限）" value="{{ request('stock_max') }}">
            </div>
            
            <div class="col-sm-12 col-md-1">
                <button type="button" class="btn btn-outline-secondary" id="search-button">検索</button>
            </div>
        </form>
    </div>

    <div class="products mt-5">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th><a class="sort-link" href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'id', 'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">ID</a></th>
                    <th>商品画像</th>
                    <th><a class="sort-link" href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'product_name', 'direction' => request('sort') === 'product_name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">商品名</a></th>
                    <th><a class="sort-link" href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'price', 'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">価格</a></th>
                    <th><a class="sort-link" href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'stock', 'direction' => request('sort') === 'stock' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">在庫数</a></th>
                    <th><a class="sort-link" href="{{ route('products.index', array_merge(request()->query(), ['sort' => 'company_name', 'direction' => request('sort') === 'company_name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}">メーカー名</a></th>
                    <th><a href="{{ route('products.create') }}" class="btn btn-primary mb-3">商品新規登録</a></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr id="product-{{ $product->id }}">
                        <td>{{ $product->id }}</td>
                        <td><img src="{{ asset($product->img_path) }}" alt="商品画像" width="100"></td>
                        <td>{{ $product->product_name }}</td>
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>{{ $product->company->company_name }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-info btn-sm mx-1">詳細</a>
                            <form method="POST"
                                  action="{{ route('products.destroy', $product->id) }}" 
                                  class="delete-form"
                                  data-product-id="{{ $product->id }}"
                                  data-product-name="{{ $product->product_name }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm mx-1 delete-button">削除</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{ $products->links() }}
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/product-search.js') }}"></script>
@endpush