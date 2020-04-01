<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class JsonValidationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($this->hasBodyAndIsJson($request)) {
            //try to decode the content
            json_decode($request->getContent());

            $jsonError = json_last_error();
            if ($jsonError !== JSON_ERROR_NONE) {
                throw new BadRequestHttpException('Invalid Json Data given!');
            }
        }

        return $next($request);
    }

    /**
     * This method checks the Request for Json Headers and Content.
     *
     * Some Clients send the "application/json" Content-Type-Header
     * although they send no response body.
     *
     * @param Request $request
     * @return bool
     */
    private function hasBodyAndIsJson(Request $request)
    {
        return !$request->isMethod('GET') && $request->isJson() && $request->getContent() !== '';
    }
}
