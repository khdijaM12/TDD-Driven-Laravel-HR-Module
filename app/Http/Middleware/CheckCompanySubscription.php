<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckCompanySubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
{
    if ($request->is('company/login')) {
        return $next($request);
    }

    $user = Auth::guard('company')->user();

    if (!$user) {
        return redirect('/company/login');
    }

    $company = $user->company;

    $subscription = $company->subscriptions()
        ->orderByDesc('subscribe_end')
        ->first();

    $today = now()->toDateString();

    if (!$subscription || $subscription->subscribe_end < $today) {
        Auth::guard('company')->logout();

        return redirect('/company/login')->withErrors([
            'email' => 'Your company\'s subscription has expired. Please contact the administration to renew it.',
        ]);
    }

    return $next($request);
}
}
