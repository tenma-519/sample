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

    public function getProductsBySearch($keyword, $companyId,$minPrice,$maxPrice,$minStock,$maxStock,$sort,$direction) {
        $allowedSorts = ['id','product_name','price','stock','company_id'];

        if (!in_array($sort,$allowedSorts)){
            $sort ='id';
        }    
        $direction =$direction === 'asc' ?
        'asc' : 'desc';
        
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
        ->when($minPrice !== null, function ($query) use ($minPrice) {
            return $query->where('price', '>=', $minPrice);
        })
        ->when($maxPrice !== null, function ($query) use ($maxPrice) {
            return $query->where('price', '<=', $maxPrice);
        })
        ->when($minStock !== null, function ($query) use ($minStock) {
            return $query->where('stock', '>=', $minStock);
        })
        ->when($maxStock !== null, function ($query) use ($maxStock) {
            return $query->where('stock', '<=', $maxStock);
        })
        ->orderBy($sort,$direction)
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