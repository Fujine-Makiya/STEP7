@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品情報編集画面</h1>

    <form method="POST" action="{{ route('products.update', $product) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <dl class="row mt-3">
            <dt class="col-sm-3">ID.</dt>
            <dd class="col-sm-9">{{ $product->id }}</dd>

            <dt class="col-sm-3">商品名<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $product->product_name }}" required>
            </dd>

            <dt class="col-sm-3">メーカー名<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <select class="form-select" id="company_id" name="company_id">
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ $product->company_id == $company->id ? 'selected' : '' }}>{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </dd>

            <dt class="col-sm-3">価格<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="number" class="form-control" id="price" name="price" value="{{ $product->price }}" required>
            </dd>

            <dt class="col-sm-3">在庫数<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->stock }}" required>
            </dd>

            <dt class="col-sm-3">コメント</dt>
            <dd class="col-sm-9">
                <textarea id="comment" name="comment" class="form-control" rows="3">{{ $product->comment }}</textarea>
            </dd>

            <dt class="col-sm-3">商品画像:</dt>
            <dd class="col-sm-9">
                <input id="img_path" type="file" name="img_path" class="form-control">
                @if($product->img_path)
                    <img src="{{ asset($product->img_path) }}" alt="商品画像" class="mt-2" width="150">
                @endif
            </dd>
        </dl>

        <div class="d-flex mt-3">
            <button type="submit" class="btn btn-primary me-2" style="flex: 0 0 100px;">更新</button>
            <a href="{{ route('products.show', $product) }}" class="btn btn-secondary" style="flex: 0 0 100px;">戻る</a>
        </div>
    </form>
</div>
@endsection
