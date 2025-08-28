<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Item;

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
        $itemId = $request->route('item_id');

        if (!$itemId) {
            abort(404);
        }

        $item = Item::find($itemId);

        if (!$item) {
            abort(404);
        }

        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        if (!$item->sold) {
            abort(403);
        }

        if ($user->id !== $item->seller_id && $user->id !== $item->buyer_id) {
            abort(403);
        }

        $request->merge(['item' => $item]);

        return $next($request);
    }
}