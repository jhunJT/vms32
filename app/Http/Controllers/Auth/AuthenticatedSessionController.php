<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();
        $request->session()->regenerate();

        // Update session details in user's record
        $this->updateSessionDetails($request);

        // Redirect based on user role
        return $this->redirectUserBasedOnRole($request->user());
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Update session details before logging out
        $this->updateSessionDetails($request);

        // Logout user
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }

    /**
     * Update session details in user's record.
     */
    protected function updateSessionDetails(Request $request)
    {
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $user = $request->user();

        $id = $request->session()->getId();
        $ip = $request->ip();
        $agent = $request->userAgent();


        if ($user) {
            $user->last_session_id = $id;
            $user->last_ip_address = $ip;
            $user->last_user_agent = $agent;
            $user->save();
        }

        $sessiondetails = [
            'user_id' => $user->id,
            'payload' => $id,
            'ip_address' => $ip,
            'user_agent' => $agent,
            'last_activity' => $todayDate,
        ];
        DB::table('sessions')->insert($sessiondetails);
    }

    /**
     * Redirect user based on their role.
     */
    protected function redirectUserBasedOnRole($user): RedirectResponse
    {
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $activity = [
            'name' => $user->name,
            'descrption' => 'Log In',
            'role' => $user->role,
            'date_time' => $todayDate,
        ];

        // Insert activity log
        DB::table('activitylogs')->insert($activity);

        // Redirect based on user role
        switch ($user->role) {
            case 'admin':
                return redirect()->route('dashboard.admin');
            case 'encoder':
                return redirect()->route('dashboard.encoder');
            case 'supervisor':
                return redirect()->route('dashboard.supervisor');
            case 'superuser':
                return redirect()->route('dashboard.superuser');
            default:
                return redirect()->intended(RouteServiceProvider::HOME);
        }
    }
}
