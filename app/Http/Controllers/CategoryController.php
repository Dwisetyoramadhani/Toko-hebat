<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use Illuminate\Http\Request;
use App\Services\CategoryService;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(
        CategoryService $categoryService
    ) {
        $this->categoryService = $categoryService;
    }

    public function index()
    {
        return response()->json(
            $this->categoryService->getAll()
        );
    }

    public function show($id)
    {
        return response()->json(
            $this->categoryService->getById($id)
        );
    }

    public function store(CategoryRequest $request)
    {
        $data = $request->validated();

        $category = $this->categoryService->create($data);

        return response()->json([
            'message' => 'Category created',
            'data' => $category
        ],201);
    }

    public function update(CategoryRequest $request, $id)
    {
        $data = $request->validated();

        $category = $this->categoryService->update($data, $id);

        return response()->json([
            'message' => 'Category updated',
            'data' => $category
        ],200);
    }

    public function destroy($id)
    {
        $this->categoryService->delete($id);

        return response()->json([
            'message' => 'Category deleted'
        ]);
    }
}
