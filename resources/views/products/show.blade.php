<html>
    <body>
    <h1>商品情報詳細画面</h1>

    <p>ID:{{ $product->id }}</p>
    
    @if ($product->img_path)
    <p>
        <img src="{{ asset('storage/' . $product->img_path) }}" width="200">
    </p>
    @endif
    <p>商品名:{{ $product->product_name }}</p>
    <p>メーカー:{{ $product->company->company_name }}</p>
    <p>価格:{{ $product->price }}</p>
    <p>在庫数:{{ $product->stock }}</p>
    <p>コメント:{{ $product->comment }}</p>
    <p><a href="{{ route('products.index')}}">戻る</a></p>
    <p><a href="{{ route('products.edit', $product->id) }}">編集</a></p>
<form action="{{ route('products.destroy', $product->id) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit">削除</button>
</form>
</body>
</html>