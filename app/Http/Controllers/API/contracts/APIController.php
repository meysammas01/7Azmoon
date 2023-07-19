<?php

namespace App\Http\Controllers\API\contracts;

use App\Http\Controllers\Controller;

class APIController extends Controller
{
    protected $statusCode;
    public function respondSuccess(string $message, array $data)
    {
        return $this->setStatusCode(200)->respond($message, true, $data);
    }
    public function respondInvalidValidation(string $message)
    {
        return  $this->setStatusCode(405)->respond($message, false);
    }
    public function respondForbidden(string $message)
    {
        return  $this->setStatusCode(403)->respond($message, false);
    }
    public function respondCreated(string $message, array $data)
    {
        return $this->setStatusCode(201)->respond($message, true, $data);
    }
    public function respondNotFound(string $message)
    {
        return $this->setStatusCode(404)->respond($message, true);
    }
    public function respondInternalError(string $message)
    {
        return $this->setStatusCode(500)->respond($message);
    }
    private function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }
    public function respond(string $message = '', bool $isSuccess = false, array $data = null)
    {
        $responseData = [
          'success' => $isSuccess,
            'message' => $message,
            'data' => $data,
        ];
        return response()->json($responseData)->setStatusCode($this->getStatusCode());
    }
    private function getStatusCode()
    {
        return $this->statusCode;
    }
}
