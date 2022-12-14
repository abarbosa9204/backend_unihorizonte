<?php

namespace App\Http\Middleware;

use DB;
use Illuminate\Auth\Middleware\ValidateConnectionDB as Middleware;
use Closure;
use \Illuminate\Http\Request;
use \Illuminate\Http\Response;

class ValidateConnectionDB
{
    /**
     * We only accept json
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {        
        try {
            if (DB::connection()) {
                return $next($request);
            }
        } catch (\Throwable $th) {
            return response(
                [
                    "status" => Response::HTTP_INTERNAL_SERVER_ERROR,
                    "message" => "A connection cannot be established because the target computer expressly denied the connection."
                ],Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
