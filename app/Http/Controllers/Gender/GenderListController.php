<?php

namespace App\Http\Controllers\Gender;

use App\Actions\Gender\GenderListAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\GenderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class GenderListController extends Controller
{
    public function __construct(
        private GenderListAction $genderListAction
    ) {
    }

    public function __invoke(): AnonymousResourceCollection|JsonResponse
    {
        try {
            $list = $this->genderListAction->execute();

            return GenderResource::collection($list);
        } catch (\Exception $ex) {
            Log::critical('Controller: ' . self::class, ['exception' => $ex->getMessage()]);

            return Response::json(
                ['message' => config('messages.error.server')],
                HttpResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
