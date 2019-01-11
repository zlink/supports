<?php

namespace Support\Traits;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response as FoundationResponse;

trait ResponseTriat
{
    protected $statusCode = FoundationResponse::HTTP_OK;

    /**
     * @return int
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @param int $statusCode
     * @return Response
     */
    public function setStatusCode(int $statusCode)
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * @param $data
     * @param array $header
     * @return string
     */
    public function respond($data, $header = [])
    {
        return JsonResponse::create($data, $this->getStatusCode(), $header)->send();
    }

    public function status($status, array $data, $code = null)
    {
        if ($code) {
            $this->setStatusCode($code);
        }

        $status = [
            'status' => $status,
            'code'   => $this->statusCode,
        ];

        $data = array_merge($status, $data);
        return $this->respond($data);
    }

    public function failed($message, $code = FoundationResponse::HTTP_BAD_REQUEST, $status = 'error')
    {
        return $this->setStatusCode($code)->message($message, $status);
    }

    /**
     * @param string $message
     * @param string $code
     * @param string $status
     * @return string
     */
    public function notLogin($message = '请先登录', $code = '-10001', $status = 'error')
    {
        return $this->status($status, [
            'message' => $message,
            'code'    => $code,
        ]);
    }

    /**
     * @param $message
     * @param string $status
     * @return string
     */
    public function message($message, $status = "success")
    {
        return $this->status($status, ['message' => $message]);
    }

    /**
     * @param string $message
     * @return
     */
    public function internalError($message = "Internal Error!")
    {
        return $this->failed($message, FoundationResponse::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param string $message
     * @return
     */
    public function created($message = "created")
    {
        return $this->setStatusCode(FoundationResponse::HTTP_CREATED)->message($message);
    }

    /**
     * @param $data
     * @param string $status
     * @return string
     */
    public function success($data, $status = "success")
    {
        return $this->status($status, compact('data'));
    }

    /**
     * @param string $message
     * @return
     */
    public function notFond($message = 'Not Fond!')
    {
        return $this->failed($message, FoundationResponse::HTTP_NOT_FOUND);
    }
}