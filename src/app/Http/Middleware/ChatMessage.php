<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChatMessage
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
        $item = $request->route('item');
        $user = $request->user();

        if ($user->id !== $item->seller_id && $user->id !== $item->buyer_id) {
            abort(403);
        }

        return $next($request);
    }
}
