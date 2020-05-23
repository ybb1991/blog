<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ApiController extends Controller
{
    protected $statusCode = 200;

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;

        return $this;
    }


    public function responseNotFound($message = 'Not Found')
    {
        return $this->responseError($message);
    }

    public function responseError($message)
    {
        return Response()->json([
            'status'       => 'failed',
            'status_code'  => $this->getStatusCode(),
            'data'         => $message,
       ]);
    }

}
