<?php

namespace App\Actions\User;

use App\Actions\Gender\GenderCheckExistsAction;
use App\Repositories\User\UserInterfaceRepository;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class UserRegisterAction
{
    public function __construct(
        private readonly UserInterfaceRepository $userInterfaceRepository,
        private readonly UserEmailUniqueVerifyAction $userEmailUniqueVerifyAction,
        private readonly GenderCheckExistsAction $genderCheckExistsAction
    ) {
    }

    public function execute(array $userData): string
    {
        $this->userEmailUniqueVerifyAction->execute($userData['email']);
        $this->genderCheckExistsAction->execute($userData['gender_id']);
        $userData['password'] = Hash::make($userData['password']);
        $user = $this->userInterfaceRepository->create($userData);

        return JWTAuth::fromUser($user);
    }
}
