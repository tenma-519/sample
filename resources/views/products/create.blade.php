<html>
<body>

<h1>商品新規登録画面</h1>
@if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
@endif
<form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <label>商品名＊</label>
    <input type="text" name="product_name" value="{{old('product_name')}}">

    <label>価格＊</label>
    <input type="number" name="price" value="{{ old('price') }}">

    <label>在庫数＊</label>
    <input type="number" name="stock" value="{{ old('stock')}}">

    <label>コメント</label>
    <input type="text" name="comment" value="{{ old('comment')}}">

    <label>メーカー名＊</label>
<select name="company_id">
    @foreach ($companies as $company)
        <option value="{{ $company->id }}"
            {{ old('company_id') == $company->id ? 'selected' : '' }}>
            {{ $company->company_name }}
        </option>
    @endforeach
</select>
<label>商品画像</label>
<input type="file" name="img_path">
<button type="submit">登録</button>
<a href="{{ route('products.index') }}">戻る</a>
</form>

</body>
</html>