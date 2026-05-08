<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Str;

class ProductService
{
    protected $productRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository
    ) {
        $this->productRepository = $productRepository;
    }

    public function getAll()
    {
        return $this->productRepository->getAll();
    }

    public function getById($id)
    {
        return $this->productRepository->getById($id);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->productRepository->create($data);
    }

    public function update(array $data, $id)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->productRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->productRepository->delete($id);
    }
}
