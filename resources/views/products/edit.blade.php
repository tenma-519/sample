<html>
    <body>

    <h1>商品情報編集画面</h1>
 @if ($errors->any())
    <ul>
        @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
</ul>
@endif
    <p>ID:{{ $product->id }}</p>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <label>商品名＊</label>
        <input type="text" name="product_name" value="{{ old('product_name', $product->product_name) }}">
        <label>価格＊</label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}">
        <label>在庫数＊</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}">
        <label>コメント</label>
        <input type="text" name="comment" value="{{ old('comment', $product->comment) }}">
        <label>メーカー名＊</label>
     <select name="company_id">
        @foreach ($companies as $company)
        <option value="{{ $company ->id }}"
        {{ old('company_id', $product->company_id) == $company->id ? 'selected' : '' }}
        </option>
        @endforeach
    </select>
    <label>商品画像</label>
    <input type="file" name="img_path">
    <button type="submit">更新</button>
    <a href="{{ route('products.show', $product->id) }}">戻る</a>
    </p>
</form>
</body>
</html>