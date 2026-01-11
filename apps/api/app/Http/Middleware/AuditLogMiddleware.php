<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuditLogMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        Log::info('audit', [
            'user_id' => optional($request->user())->id,
            'ip' => $request->ip(),
            'method' => $request->method(),
            'path' => $request->path(),
            'status' => $response->getStatusCode(),
        ]);

        return $response;
    }
}
