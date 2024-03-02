<?php

namespace App\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ApiException extends HttpException
{

    public function __construct(
        private int $status,
        private mixed $response
    ) {
        $this->status = $status;
        $this->response = $response;
    }

    /**
     * Render this Exception
     *
     * @return Illuminate\Contracts\Routing\ResponseFactory::json
     */
    public function render()
    {
        return response()->json($this->response, $this->status);
    }
}
