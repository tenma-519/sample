<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Company;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
public function index(Request $request) {
    $productModel = new Product();

    $products = $productModel->getProductsBySearch(
        $request->keyword,
        $request->company_id
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
public function store(Request $request) {
    $request->validate([
        'product_name' => 'required',
        'company_id' => 'required',
        'price' => 'required|integer|min:0',
        'stock' => 'required|integer|min:0',
        'img_path' => 'nullable|image',
    ]);

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
public function update(Request $request, $id) {
    $request->validate([
        'product_name' => 'required',
        'company_id' => 'required',
        'price' => 'required|integer|min:0',
        'stock' => 'required|integer|min:0',
        'img_path' => 'nullable|image',
    ]);

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
}

public function destroy($id) {
    $productModel = new Product();
    $product = $productModel->getProductById($id);

    if ($product->img_path) {
        Storage::disk('public')->delete($product->img_path);
    }

    $productModel->deleteProduct($product);

    return redirect()->route('products.index');
}
}