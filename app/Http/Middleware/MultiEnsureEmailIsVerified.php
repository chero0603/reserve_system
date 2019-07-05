<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class MultiEnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next, ...$guards)
    {
        if (empty($guards)) {
            $guards = [null];
        }

        $verify_flg = false;
        $path = $request->route()->getPrefix().'/email/verify';

        foreach ($guards as $guard) {
            if ($request->user($guard) &&
            ($request->user($guard) instanceof MustVerifyEmail && $request->user($guard)->hasVerifiedEmail())) {
                $verify_flg = true;
            }
        }

        if ($verify_flg) {
            return $next($request);
        } else {
            return $request->expectsJson()
            ? abort(403, 'Your email address is not verified.')
            : redirect($path);
        }
    }
}
