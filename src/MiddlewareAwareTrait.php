<?php

namespace Support;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;

trait MiddlewareAwareTrait
{
    protected $tip;

    protected $middlewareLock = false;

    protected function addMiddleware(callable $callable)
    {
        if ($this->middlewareLock) {
            throw new RuntimeException('Middleware can’t be added once the stack is dequeuing');
        }

        if (is_null($this->tip)) {
            $this->seedMiddlewareStack();
        }

        $next = $this->tip;
        $this->tip = function (ServerRequestInterface $request, ResponseInterface $response) use ($callable, $next) {
            $result = call_user_func($callable, $request, $response, $next);

            if ($result instanceof ResponseInterface === false) {
                throw new UnexpectedValueException('Middleware must return instance of \Psr\Http\Message\ResponseInterface');
            }

            return $result;
        };

        return $this;
    }

    protected function sendMiddlewareStack(callable $kernel = null)
    {
        if (!is_null($this->tip)) {
            throw new RuntimeException('MiddlewareStack can only be seeded once.');
        }

        if ($kernel === null) {
            $kernel = $this;
        }

        $this->tip = $kernel;
    }

    public function callMiddlewareStack(ServerRequestInterface $request, ResponseInterface $response)
    {
        if (is_null($this->tip)) {
            $this->seedMiddlewareStack();
        }

        $start = $this->tip;
        $this->middlewareLock = true;
        $response = $start($request, $response);
        $this->middlewareLock = false;
        return $response;
    }
}
