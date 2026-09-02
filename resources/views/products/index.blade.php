<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品一覧</title>
</head>
<body>
    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">ログアウト</button>
</form>
<h1>商品一覧画面</h1>
<p><a href="{{ route('products.create') }}">新規登録</a></p>
<form action="{{ route('products.index') }}" method="GET">
    <label>検索キーワード</label>
    <input type="text" name="keyword"
    value="{{ request('keyword')}}">

    <label>メーカー名</label>
<select name="company_id">
        <option value="">すべて</option>
        @foreach ($companies as $company)
        <option value="{{ $company->id }}"
        {{ request('company_id') == $company->id ? 'selected' : '' }}>
        {{ $company->company_name }}
    </option>
@endforeach
</select>

    <button type="submit">検索</button>
</form>
<table>
    <tr>
        <th>ID</th>
        <th>商品画像</th>
        <th>商品名</th>
        <th>価格</th>
        <th>在庫数</th>
        <th>メーカー</th>
        <th>詳細</th>
        <th>削除</th>
</tr>
@foreach ($products as $product)
    <tr>
        <td>{{ $product->id }}</td>
        <td>
            @if ($product->img_path)
                <img src="{{ asset('storage/' . $product->img_path) }}" width="100">
            @endif
        </td>
        <td>{{ $product->product_name }}</td>
        <td>{{ $product->price }}</td>
        <td>{{ $product->stock }}</td>
        <td>{{ $product->company->company_name }}</td>
        <td>
            <a href="{{ route('products.show', $product->id) }}">詳細</a>
        </td>
        <td>
            <form action="{{ route('products.destroy', $product->id) }}" method="POST"
                  onsubmit="return confirm('本当に削除しますか？');">
                  @csrf
                  @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </td>
    </tr>
@endforeach
</table>
{{ $products->appends(request()->query())->links() }}
</body>
</html>