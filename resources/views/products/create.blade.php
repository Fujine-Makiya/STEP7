@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">商品新規登録</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as  $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data">

        @csrf

        <dl class="row mt-3">
            <dt class="col-sm-3">商品名<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="product_name" class="form-control" value="{{ old('product_name') }}" required>
                @if ($errors->has('product_name'))
                    <p>{{ $errors->first('product_name') }}</p>
                @endif
            </dd>

            <dt class="col-sm-3">メーカー<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <select class="form-select" name="company_id" required>
                    @foreach($companies as $company)
                        <option value="{{ $company->id }}" {{ old('company_id') == $company->id ? 'selected' : '' }}>
                            {{ $company->company_name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('company_id'))
                    <p>{{ $errors->first('company_id') }}</p>
                @endif
            </dd>

            <dt class="col-sm-3">価格<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="price" class="form-control" value="{{ old('price') }}" required>
                @if ($errors->has('price'))
                    <p>{{ $errors->first('price') }}</p>
                @endif
            </dd>

            <dt class="col-sm-3">在庫数<span class="text-danger">*</span></dt>
            <dd class="col-sm-9">
                <input type="text" name="stock" class="form-control" value="{{ old('stock') }}" required>
                @if ($errors->has('stock'))
                    <p>{{ $errors->first('stock') }}</p>
                @endif
            </dd>

            <dt class="col-sm-3">コメント</dt>
            <dd class="col-sm-9">
                <textarea name="comment" class="form-control" rows="3">{{ old('comment') }}</textarea>
                @if ($errors->has('comment'))
                    <p>{{ $errors->first('comment') }}</p>
                @endif
            </dd>

            <dt class="col-sm-3">商品画像</dt>
            <dd class="col-sm-9">
                <input type="file" name="img_path" class="form-control">
                @if ($errors->has('img_path'))
                    <p>{{ $errors->first('img_path') }}</p>
                @endif
            </dd>
        </dl>

        <div class="d-flex mt-4">
            <button type="submit" class="btn btn-primary me-2" style="flex: 0 0 100px;">新規登録</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary" style="flex: 0 0 100px;">戻る</a>
        </div>
    </form>
</div>
@endsection