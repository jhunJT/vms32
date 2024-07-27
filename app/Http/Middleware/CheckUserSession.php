<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CheckUserSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is logged in
        $user = auth()->user();
        if ($user) {
            $sessionId = session()->getId();
            $existingSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $sessionId)
                ->latest()
                ->first();

            // If there's an existing session other than the current one
            if ($existingSession) {
                auth()->logout();
                session()->invalidate();

                return redirect()->route('login')->withErrors([
                    'message' => 'Your account is already logged in on another device.'
                ]);
            }
        }



        return $next($request);
    }
}
