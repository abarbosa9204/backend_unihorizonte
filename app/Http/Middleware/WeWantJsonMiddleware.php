<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\WeWantJsonMiddleware as Middleware;
use Closure;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;

class WeWantJsonMiddleware
{
    /**
     * We only accept json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        try {
            if (!$request->isMethod('post')) return $next($request);

            $acceptHeader = $request->header('Accept');
            if ($acceptHeader != 'application/json') {
                return response([
                    'status'    =>  Response::HTTP_NOT_ACCEPTABLE,
                    'messaage'  =>  'Only JSON type requests are allowed (header{Content-Type:application/json, Accept:application/json})'  
                ], Response::HTTP_NOT_ACCEPTABLE);
            }
            return $next($request);
        } catch (\Throwable $th) {
            return response(
                [
                    "status"    => Response::HTTP_INTERNAL_SERVER_ERROR,
                    'message'   =>  $th->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
