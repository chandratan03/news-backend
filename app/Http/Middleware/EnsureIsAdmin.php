<?php

namespace App\Http\Middleware;

use App\Constants\HttpResponse;
use App\Helper\MyHelper;
use Closure;
use Illuminate\Http\Request;

class EnsureIsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()["is_admin"]) {
            return MyHelper::customResponse(
                [],
                "Admin only",
                HttpResponse::HTTP_FORBIDDEN
            );
        }
        return $next($request);
    }
}
