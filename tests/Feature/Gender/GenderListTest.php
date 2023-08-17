<?php

namespace Tests\Feature\User;

use App\Actions\Gender\GenderListAction;
use App\Models\Gender;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Mockery;
use Tests\TestCase;

class GenderListTest extends TestCase
{
    private const ROUTE = 'gender.list';

    public function test_expected_true_when_route_exists()
    {
        $this->assertTrue(Route::has(self::ROUTE));
    }

    public function test_expected_server_error_when_jwt_auth_throw_exception()
    {
        $userRegisterActionMock = Mockery::mock(GenderListAction::class);
        $userRegisterActionMock->shouldReceive('execute')
            ->andThrow(new Exception());
        $this->app->instance(GenderListAction::class, $userRegisterActionMock);

        $response = $this->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR)
            ->assertJson([
                'message' => config('messages.error.server'),
            ]);
    }

    public function test_expected_gender_list()
    {
        Gender::updateOrCreate(
            [
                'description' => 'male',
            ],
            []
        );
        Gender::updateOrCreate(
            [
                'description' => 'female',
            ],
            []
        );

        $response = $this->getJson(route(self::ROUTE));

        $response->assertStatus(Response::HTTP_OK)
            ->assertJson([
                'data' => [
                    [
                        'description' => 'male',
                    ],
                    [
                        'description' => 'female',
                    ],
                ]
            ]);
    }
}
