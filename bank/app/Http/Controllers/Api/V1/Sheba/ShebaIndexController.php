<?php

namespace App\Http\Controllers\Api\V1\Sheba;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApiResponse\ApiResponseResource;
use App\Http\Resources\ApiResponse\ResourcesTrait;
use App\Services\ShebaRequest\ShebaRequestServiceInterface;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as HttpFoundationResponse;

class ShebaIndexController extends Controller
{
    use ResourcesTrait;

    protected ShebaRequestServiceInterface $shebaRequestService;

    public function __construct( ShebaRequestServiceInterface $shebaRequestService )
    {
        $this->shebaRequestService = $shebaRequestService;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\ApiResponse\ApiResponseResource
     */
    public function __invoke( Request $request ): ApiResponseResource
    {
        $list = $this->shebaRequestService->list();

        return $this->success(
            [ 'requests' => $list ],
            trans( 'messages.welcome' ),
            HttpFoundationResponse::HTTP_OK
        );
    }
}

