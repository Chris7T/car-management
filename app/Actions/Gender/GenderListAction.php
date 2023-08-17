<?php

namespace App\Actions\Gender;

use App\Repositories\Gender\GenderInterfaceRepository;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class GenderListAction
{
    public function __construct(
        private readonly GenderInterfaceRepository $genderInterfaceRepository
    ) {
    }

    public function execute(): Collection
    {
        return Cache::remember(
            'gender-list',
            config('cache.time.one_month'),
            function () {
                return $this->genderInterfaceRepository->list();
            }
        );
    }
}
