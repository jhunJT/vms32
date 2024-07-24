<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {

        $user = auth()->user();
        if ($user) {
            $sessionId = session()->getId();
            $existingSession = DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', $sessionId)
                // ->latest()
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
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
