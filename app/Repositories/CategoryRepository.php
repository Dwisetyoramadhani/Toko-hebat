<?php

namespace App\Repositories;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function getAll()
    {
        return Category::latest()->get();
    }

    public function getById($id)
    {
        return Category::findOrFail($id);
    }

    public function create(array $data)
    {
        return Category::create($data);
    }

    public function update(array $data, $id)
    {
        $category = $this->getById($id);

        $category->update($data);

        return $category;
    }

    public function delete($id)
    {
        $category = $this->getById($id);

        return $category->delete();
    }
}
