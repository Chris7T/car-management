<?php

namespace App\Repositories\Gender;

use App\Models\Gender;
use Illuminate\Support\Collection;

class GenderEloquentRepository implements GenderInterfaceRepository
{
    public function __construct(
        private readonly Gender $model,
    ) {
    }

    public function findById(int $id): ?Gender
    {
        return $this->model->find($id);
    }

    public function list(): Collection
    {
        return $this->model->all();
    }
}
