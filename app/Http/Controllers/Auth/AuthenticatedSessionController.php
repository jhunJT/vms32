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
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $activity = [
            'name' =>   $request->user()->name,
            'descrption' => 'Log In',
            'role' =>  $request->user()->role,
            'date_time' => $todayDate,
        ];

        if($request->user()->role === 'admin'){
            DB::table('activitylogs')->insert($activity);
            return redirect()->route('dashboard.admin');

        }elseif($request->user()->role === 'encoder'){
            DB::table('activitylogs')->insert($activity);
            return redirect()->route('dashboard.encoder');

        }elseif($request->user()->role === 'supervisor'){
            DB::table('activitylogs')->insert($activity);
            return redirect()->route('dashboard.supervisor');
        }
        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {

        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $activity = [
            'name' =>   $request->user()->name,
            'descrption' => 'Log Out',
            'role' =>  $request->user()->role,
            'date_time' => $todayDate,
        ];

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        DB::table('activitylogs')->insert($activity);

        return redirect('/');
    }
}
