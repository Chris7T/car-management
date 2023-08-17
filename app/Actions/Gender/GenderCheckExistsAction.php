<?php

namespace App\Actions\Gender;

use App\Repositories\Gender\GenderInterfaceRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GenderCheckExistsAction
{
    public function __construct(
        private readonly GenderInterfaceRepository $genderInterfaceRepository
    ) {
    }

    public function execute(int $id): void
    {
        $gender = $this->genderInterfaceRepository->findById($id);

        if (is_null($gender)) {
            throw new NotFoundHttpException('Gender is not found');
        }
    }
}
