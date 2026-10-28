<?php

namespace App\Http\Controllers\Auth;

use App\Http\Requests\Auth\AccessRequest;
use App\Models\User;
use App\Services\Auth\PasswordlessAuth;
use App\Services\Workspaces;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class SessionController
{
    public function request(AccessRequest $request, PasswordlessAuth $auth)
    {
        $key = 'auth-email:'.hash('sha256', $request->validated('email'));
        if (! RateLimiter::tooManyAttempts($key, 5)) {
            RateLimiter::hit($key, 900);
            $auth->issue($request->validated('email'), trim($request->validated('name') ?? '') ?: 'Participant');
        }

        return back()->with('status', 'If permitted, check your email for a code or sign-in link.');
    }

    public function verify(AccessRequest $request, PasswordlessAuth $auth)
    {
        $request->validate(['code' => ['required', 'string', 'regex:/^[0-9]{6}$/']]);
        $user = $auth->redeem($request->validated('email'), $request->input('code'));
        if (! $user) {
            throw ValidationException::withMessages(['code' => 'This code is invalid or expired. Request a new code.']);
        }

        return $this->login($request, $user);
    }

    public function capture(Request $request, string $token)
    {
        abort_unless(preg_match('/^[a-f0-9]{64}$/', $token), 404);
        $request->session()->put('magic_hash', hash('sha256', $token));

        return redirect('/auth/magic');
    }

    public function magic(Request $request, PasswordlessAuth $auth)
    {
        $user = $auth->redeem(null, $request->session()->pull('magic_hash', ''), true);
        if (! $user) {
            throw ValidationException::withMessages(['link' => 'This link is invalid or expired. Request a new link.']);
        }

        return $this->login($request, $user);
    }

    public function demo(Request $request)
    {
        $request->validate(['email' => ['required', 'string', 'email'], 'password' => ['required', 'string', 'max:255']]);
        $email = mb_strtolower(trim($request->input('email')));
        $user = User::where('normalized_email', $email)->first();
        if (! $user || ! $user->password || ! in_array($email, ['admin@iches.test', 'reviewer@iches.test', 'presenter@iches.test', 'participant@iches.test'], true)
            || ! Hash::check($request->input('password'), $user->password)) {
            throw ValidationException::withMessages(['email' => 'Unable to sign in with these details.']);
        }

        return $this->login($request, $user);
    }

    public function dashboard(Request $request, Workspaces $workspaces)
    {
        return Inertia::render('dashboard/Index', [
            'account' => $request->user()->only('name', 'email'),
            'workspaces' => $workspaces->choices($request->user()),
            'activeWorkspace' => $workspaces->current($request),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    private function login(Request $request, User $user)
    {
        Auth::login($user);
        $request->session()->regenerate();

        return redirect('/dashboard');
    }
}
