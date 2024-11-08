<?php

namespace App\Http\Services\Product;

use App\Models\Product;
use Illuminate\Support\Facades\Session;

class ProductService
{
    const LIMIT = 16;

    public function get($page = null)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->orderByDesc('id')
            ->when($page != null, function ($query) use ($page) {
                $query->offset($page * self::LIMIT);
            })
            ->limit(self::LIMIT)
            ->get();
    }

    public function show($id)
    {
        return Product::where('id', $id)
            ->where('active', 1)
            ->with('menu')
            ->firstOrFail();
    }

    public function more($id)
    {
        return Product::select('id', 'name', 'price', 'price_sale', 'thumb')
            ->where('active', 1)
            ->where('id', '!=', $id)
            ->orderByDesc('id')
            ->limit(8)
            ->get();
    }

    // Thêm sản phẩm vào cơ sở dữ liệu
    public function create($data)
    {
        try {
            Product::create([
                'name' => $data['name'],
                'menu_id' => $data['menu_id'],
                'price' => $data['price'],
                'price_sale' => $data['price_sale'],
                'description' => $data['description'],
                'content' => $data['content'],
                'thumb' => $data['thumb'],
                'active' => $data['active'],
            ]);

            Session::flash('success', 'Thêm sản phẩm thành công');
            return true;
        } catch (\Exception $e) {
            Session::flash('error', 'Thêm sản phẩm thất bại');
            return false;
        }
    }
}
