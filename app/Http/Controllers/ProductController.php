<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;

class ProductController extends Controller
{
public function index(Request $request) {
    $productModel = new Product();

    $products = $productModel->getProductsBySearch(
        $request->keyword,
        $request->company_id,
        $request->min_price,
        $request->max_price,
        $request->min_stock,
        $request->max_stock,
        $request->input('sort', 'id'),
        $request->input('direction','desc')
    );

    $companyModel = new Company();
    $companies = $companyModel->getAllCompanies();

    return view(
        'products.index',
        compact('products', 'companies')
    );
    }
public function create() {
    $companyModel = new Company();
    $companies = $companyModel->getAllCompanies();

    return view('products.create', compact('companies'));
}
public function store(StoreProductRequest $request)
{
    try {
        $product = new Product();
        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;
        $product->company_id = $request->company_id;
        if ($request->hasFile('img_path')) {
            $path = $request->file('img_path')->store('products', 'public');
            $product->img_path = $path;
        }

        $productModel = new Product();
        $productModel->saveProduct($product);

        return redirect()->route('products.create');

    } catch (\Exception $e) {
        return back()
            ->withInput()
            ->withErrors(['error' => '商品の登録に失敗しました。']);
    }
}
public function show($id) {
    $productModel = new Product();
    $product = $productModel->getProductById($id);

    return view('products.show', compact('product'));
}
public function edit($id) {
    $productModel = new Product();
    $product = $productModel->getProductById($id);

    $companyModel = new Company();
    $companies = $companyModel->getAllCompanies();

    return view('products.edit', compact('product', 'companies'));
}
public function update(UpdateProductRequest $request, $id)
{
    try {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        $product->product_name = $request->product_name;
        $product->price = $request->price;
        $product->stock = $request->stock;
        $product->comment = $request->comment;
        $product->company_id = $request->company_id;

        if ($request->hasFile('img_path')) {
            if ($product->img_path) {
                Storage::disk('public')->delete($product->img_path);
            }

            $path = $request->file('img_path')->store('products', 'public');
            $product->img_path = $path;
        }

        $productModel->saveProduct($product);

        return redirect()->route('products.edit', $product->id);

        } catch (\Exception $e) {
    return back()
        ->withInput()
        ->withErrors(['error' => '商品の更新に失敗しました。']);
}
} //
public function destroy($id)
{
    try {
        $productModel = new Product();
        $product = $productModel->getProductById($id);

        if ($product->img_path) {
            Storage::disk('public')->delete($product->img_path);
        }

        $productModel->deleteProduct($product);

        return redirect()->route('products.index');

    } catch (\Exception $e) {
        return redirect()
            ->route('products.index')
            ->withErrors(['error' => '商品の削除に失敗しました。']);
     }
}
}