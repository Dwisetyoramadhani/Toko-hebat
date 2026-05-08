<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll()
    {
        return Product::with('category')
            ->latest()
            ->get();
    }

    public function getById($id)
    {
        return Product::with('category')
            ->findOrFail($id);
    }

    public function create(array $data)
    {
        return Product::create($data);
    }

    public function update(array $data, $id)
    {
        $product = $this->getById($id);

        $product->update($data);

        return $product;
    }

    public function delete($id)
    {
        $product = $this->getById($id);

        return $product->delete();
    }
}
