<?php

namespace App\Http\Middleware;

use Closure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RequestBodyValidation
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  array  $fields
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$fields)
    {

        if (empty($request->all())) {
            return response()->json([
                'error' => 'post request body cannot be empty',
            ], 400);
        }

        $trimRequest = $request->only($fields);

        if ($trimRequest != $request->all()) {
            return response()->json([
                'error' => 'one or more invalid or extraneous fields were included in the request',
                'accepted fields list' => $fields,
            ], 422);
        }

        return $next($request);
    }
}
