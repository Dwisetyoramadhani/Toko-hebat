<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(
        ProductService $productService
    ) {
        $this->productService = $productService;
    }

    public function index()
    {
        return response()->json(
            $this->productService->getAll()
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->productService->getById($id)
        );
    }

    public function store(ProductRequest $request)
    {
        $data = $request->validated();

        $product = $this->productService->create($data);

        return response()->json([
            'message' => 'Product created',
            'data' => $product
        ]);
    }

    public function update(ProductRequest $request, $id)
    {
        $data = $request->validated();

        $product = $this->productService->update($data, $id);

        return response()->json([
            'message' => 'Product updated',
            'data' => $product
        ]);
    }

    public function destroy($id)
    {
        $this->productService->delete($id);

        return response()->json([
            'message' => 'Product deleted'
        ]);
    }
}
