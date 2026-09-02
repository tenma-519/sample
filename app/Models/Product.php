<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function sales() {
        return $this->hasMany(Sale::class);
    }

    public function getProductsBySearch($keyword, $companyId) {
        return Product::with('company')
            ->when($keyword, function ($query, $keyword) {
                return $query->where(
                    'product_name',
                    'like',
                    '%' . $keyword . '%'
                );
            })
            ->when($companyId, function ($query, $companyId) {
                return $query->where('company_id', $companyId);
            })
            ->paginate(10);
    }

    public function getProductById($id) {
        return $this->with('company')->findOrFail($id);
    }

    public function saveProduct($product) {
        return $product->save();
    }

    public function deleteProduct($product){
        return $product->delete();
    }
}