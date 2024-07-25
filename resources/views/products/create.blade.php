@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">

        @csrf

        <dl class="row mt-3">
            <dt class="col-sm-3">商品名<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="product_name" class="form-control" required>
            </dd>

            <dt class="col-sm-3">メーカー<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <select class="form-select" name="company_id" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}">{{ $company->company_name }}</option>
                    @endforeach
                </select>
            </dd>

            <dt class="col-sm-3">価格<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="price" class="form-control" required>
            </dd>

            <dt class="col-sm-3">在庫数<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="stock" class="form-control" required>
            </dd>

            <dt class="col-sm-3">コメント</dt>
            <dd class="col-sm-9">
                <textarea name="comment" class="form-control" rows="3"></textarea>
            </dd>

            <dt class="col-sm-3">商品画像</dt>
            <dd class="col-sm-9">
                <input type="file" name="img_path" class="form-control">
            </dd>
        </dl>

        <div class="d-flex mt-4">
            <button type="submit" class="btn btn-primary me-2" style="flex: 0 0 100px;">新規登録</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary" style="flex: 0 0 100px;">戻る</a>
        </div>
    </form>
</div>
@endsection
