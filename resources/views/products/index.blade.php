<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>商品一覧</title>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">ログアウト</button>
</form>
<h1>商品一覧画面</h1>
<p><a href="{{ route('products.create') }}">新規登録</a></p>
<form id="search-form" action="{{ route('products.index') }}" method="GET">
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
<label>価格</label>
<input type="number" name="min_price" value="{{ request('min_price') }}">
～
<input type="number" name="max_price" value="{{ request('max_price') }}">

<label>在庫数</label>
<input type="number" name="min_stock" value="{{ request('min_stock') }}">
～
<input type="number" name="max_stock" value="{{ request('max_stock') }}">
</select>

    <button type="submit">検索</button>
</form>
<table id="product-table">
    <tr>
        <th>
    <a href="{{ route('products.index', array_merge(request()->query(), [
        'sort' => 'id',
        'direction' => request('sort') === 'id' && request('direction') === 'asc' ? 'desc' : 'asc'
    ])) }}">
        ID
    </a>
</th>
        <th>商品画像</th>
        <th>
    <a href="{{ route('products.index', array_merge(request()->query(), [
        'sort' => 'product_name',
        'direction' => request('sort') === 'product_name' && request('direction') === 'asc' ? 'desc' : 'asc'
    ])) }}">
        商品名
    </a>
</th>
        <th>
    <a href="{{ route('products.index', array_merge(request()->query(), [
        'sort' => 'price',
        'direction' => request('sort') === 'price' && request('direction') === 'asc' ? 'desc' : 'asc'
    ])) }}">
        価格
    </a>
</th>
        <th>
    <a href="{{ route('products.index', array_merge(request()->query(), [
        'sort' => 'stock',
        'direction' => request('sort') === 'stock' && request('direction') === 'asc' ? 'desc' : 'asc'
    ])) }}">
        在庫数
    </a>
</th>
        <th>メーカー</th>
        <th>詳細</th>
        <th>削除</th>
</tr>
@foreach ($products as $product)
    <tr id="product-{{ $product->id }}">
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
           <form class="delete-form"
      action="{{ route('products.destroy', $product->id) }}"
      method="POST"
      data-id="{{ $product->id }}">
                  @csrf
                  @method('DELETE')
                <button type="submit">削除</button>
            </form>
        </td>
    </tr>
@endforeach
</table>
{{ $products->appends(request()->query())->links() }}
<script>
$(function () {

    $('#search-form').on('submit', function (e) {
    });
    $(document).on('submit', '.delete-form', function (e) {
        e.preventDefault();

        if (!confirm('本当に削除しますか？')) {
            return;
        }

        const form = $(this);
        const productId = form.data('id');

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),

            success: function () {
                $('#product-' + productId).remove();
            },

            error: function (xhr) {
                console.log('削除に失敗しました');
                console.log(xhr);
                alert('商品の削除に失敗しました。');
            }
        });
    });

});
</script>
</body>
</html>