<?php

namespace App\Services\Auth;

use App\Models\AuthChallenge;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PasswordlessAuth
{
    public function issue(string $email, string $name): void
    {
        if (! app()->environment('local', 'testing') && in_array(config('mail.default'), ['log', 'failover'], true)) {
            throw new \LogicException('Configure a non-logging mail transport outside local development.');
        }

        $delivery = DB::transaction(function () use ($email, $name): ?array {
            // The unique email row also serializes concurrent first-time requests.
            DB::table('auth_challenges')->insertOrIgnore([
                'email' => $email, 'name' => $name, 'created_at' => now()->subMinute(), 'updated_at' => now()->subMinute(),
            ]);
            $challenge = AuthChallenge::where('email', $email)->lockForUpdate()->firstOrFail();
            if ($challenge->updated_at->gt(now()->subSeconds(60))) {
                return null;
            }

            $code = (string) random_int(100000, 999999);
            $token = bin2hex(random_bytes(32));
            $tokenHash = hash('sha256', $token);
            $challenge->forceFill([
                'name' => $name, 'token_hash' => $tokenHash,
                'code_hash' => $this->codeHash($code, $tokenHash),
                'attempts' => 0, 'expires_at' => now()->addMinutes(10), 'consumed_at' => null,
            ])->save();

            return [$code, $token];
        }, 3);

        if ($delivery) {
            [$code, $token] = $delivery;
            // Use configured application URL, never an untrusted incoming Host header.
            $url = rtrim(config('app.url'), '/').'/auth/magic/'.$token;
            Mail::raw("Your ICHES sign-in code: {$code}\n\nOr confirm your sign-in: {$url}\n\nExpires in 10 minutes. If you did not request this, ignore this email.", function ($message) use ($email): void {
                $message->to($email)->subject('Your ICHES sign-in');
            });
        }
    }

    public function redeem(?string $email, string $secret, bool $magic = false): ?User
    {
        return DB::transaction(function () use ($email, $secret, $magic): ?User {
            $challenge = AuthChallenge::query()
                ->when($magic, fn ($query) => $query->where('token_hash', $secret), fn ($query) => $query->where('email', $email))
                ->lockForUpdate()->first();
            if (! $challenge || $challenge->consumed_at || ! $challenge->expires_at || $challenge->expires_at->lte(now()) || $challenge->attempts >= 5) {
                return null;
            }
            if (! $magic && ! hash_equals($challenge->code_hash, $this->codeHash($secret, $challenge->token_hash))) {
                $challenge->increment('attempts');

                return null;
            }

            $challenge->forceFill(['consumed_at' => now()])->save();
            $user = User::where('normalized_email', $challenge->email)->first()
                ?? User::create(['email' => $challenge->email, 'name' => $challenge->name]);
            if (! $user->email_verified_at) {
                $user->forceFill(['email_verified_at' => now()])->save();
            }

            return $user;
        }, 3);
    }

    private function codeHash(string $code, string $tokenHash): string
    {
        return hash_hmac('sha256', $tokenHash.':'.$code, config('app.key'));
    }
}
