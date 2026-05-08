<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Str;

class CategoryService
{
    protected $categoryRepository;

    public function __construct(
        CategoryRepositoryInterface $categoryRepository
    ) {
        $this->categoryRepository = $categoryRepository;
    }

    public function getAll()
    {
        return $this->categoryRepository->getAll();
    }

    public function getById($id)
    {
        return $this->categoryRepository->getById($id);
    }

    public function create(array $data)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->categoryRepository->create($data);
    }

    public function update(array $data, $id)
    {
        $data['slug'] = Str::slug($data['name']);

        return $this->categoryRepository->update($data, $id);
    }

    public function delete($id)
    {
        return $this->categoryRepository->delete($id);
    }
}
