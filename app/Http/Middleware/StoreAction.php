<?php

namespace App\Http\Middleware;

use App\Services\Action\ActionService;
use Closure;
use Illuminate\Http\Request;

class StoreAction
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param  Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            $url = $request->url();
            (new ActionService())->store($url);
        } catch (\Exception $exception) {
        }
        return $next($request);
    }
}
