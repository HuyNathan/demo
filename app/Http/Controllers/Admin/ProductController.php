<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Services\Product\ProductService;
use App\Http\Services\Menu\MenuService; // Dịch vụ để lấy danh sách menus
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $productService;
    protected $menuService;

    public function __construct(ProductService $productService, MenuService $menuService)
    {
        $this->productService = $productService;
        $this->menuService = $menuService;
    }

    // Hiển thị form thêm sản phẩm
    public function create()
    {
        // Lấy danh sách menus từ MenuService
        $menus = $this->menuService->getAll();

        return view('admin.product.add', [
            'title' => 'Thêm Sản Phẩm Mới',
            'menus' => $menus, // Truyền biến menus vào view
        ]);
    }

    // Lưu sản phẩm vào database
    public function store(Request $request)
    {
        // Validate dữ liệu
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'menu_id' => 'required|integer',
            'price' => 'required|numeric',
            'description' => 'required',
            'content' => 'required',
            'thumb' => 'required|string', // Yêu cầu hình ảnh sản phẩm
        ]);

        // Gọi service để thêm sản phẩm
        $result = $this->productService->create($request->all());

        // Kiểm tra kết quả
        if ($result) {
            return redirect()->route('admin.products.index')->with('success', 'Sản phẩm đã được thêm thành công.');
        } else {
            return redirect()->back()->with('error', 'Thêm sản phẩm thất bại. Vui lòng thử lại.');
        }
    }

    // Hiển thị sản phẩm chi tiết (ví dụ khi chỉnh sửa)
    public function index($id = '', $slug = '')
    {
        $product = $this->productService->show($id);
        $productsMore = $this->productService->more($id);

        return view('admin.products.list', [
            'title' => $product->name,
            'product' => $product,
            'products' => $productsMore
        ]);
    }
}
