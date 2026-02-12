<?php

namespace App\Http\Controllers\Api\V1\Sheba;

use App\Enums\ShebaRequestStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateShebaRequest;
use App\Http\Resources\ApiResponse\ApiResponseResource;
use App\Http\Resources\ApiResponse\ResourcesTrait;
use App\Services\ShebaRequest\ShebaRequestServiceInterface;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ShebaUpdateController extends Controller
{
    use ResourcesTrait;

    protected ShebaRequestServiceInterface $shebaRequestService;

    public function __construct( ShebaRequestServiceInterface $shebaRequestService )
    {
        $this->shebaRequestService = $shebaRequestService;
    }

    /**
     * @param \App\Http\Requests\UpdateShebaRequest $request
     * @param string $id
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     */
    public function __invoke( UpdateShebaRequest $request, string $id ): ApiResponseResource
    {
        $validated = $request->validated();

        if ( $validated[ 'status' ] === ShebaRequestStatus::CANCELED->value && empty( $validated[ 'note' ] ) )
        {
            return $this->error(
                [
                    trans( 'messages.sheba.note_required_on_cancel' ),
                    HttpFoundationResponse::HTTP_UNPROCESSABLE_ENTITY
                ]
            );
        }

        $result = $this->shebaRequestService->updateStatus( $id, $validated[ 'status' ], $validated[ 'note' ] ?? null );

        $message = $validated[ 'status' ] === ShebaRequestStatus::CONFIRMED->value
            ? trans( 'messages.sheba.confirmed' )
            : trans( 'messages.sheba.canceled' );

        return $this->success(
            [
                'request' => $result,
            ],
            $message,
            HttpFoundationResponse::HTTP_OK
        );
    }
}

