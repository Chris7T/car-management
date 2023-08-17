<?php

namespace App\Repositories\Gender;

use App\Models\Gender;
use Illuminate\Support\Collection;

interface GenderInterfaceRepository
{
    public function findById(int $id): ?Gender;

    public function list(): Collection;
}
