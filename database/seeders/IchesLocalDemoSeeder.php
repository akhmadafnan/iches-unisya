<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\RoleAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class IchesLocalDemoSeeder extends Seeder
{
    public function run(): void
    {
        if (! app()->environment('local') || ! config('iches-auth.demo_mode')
            || config('database.default') !== 'mysql'
            || config('database.connections.mysql.host') !== '127.0.0.1'
            || (string) config('database.connections.mysql.port') !== '3306'
            || DB::selectOne('SELECT DATABASE() AS db')->db !== 'iches_unisya') {
            throw new \LogicException('Demo seeding requires opt-in local mode and the owned local schema.');
        }

        DB::transaction(function (): void {
            $event = Event::firstOrCreate(['slug' => 'iches-demo-local'], [
                'name' => 'Synthetic Local Demo', 'year' => 2099, 'timezone' => 'Asia/Jakarta',
            ]);
            foreach (['admin', 'reviewer', 'presenter', 'participant'] as $label) {
                $user = User::firstOrCreate(['email' => $label.'@iches.test'], ['name' => 'Synthetic '.ucfirst($label)]);
                if (! $user->password || ! Hash::check('password', $user->password)) {
                    $user->password = Hash::make('password');
                }
                $user->email_verified_at ??= now();
                $user->save();

                // Admin/reviewer labels confer no unapproved authority. Only existing participant/presenter keys are seeded.
                if (in_array($label, ['presenter', 'participant'], true)
                    && ! RoleAssignment::where('user_id', $user->id)->where('event_id', $event->id)->where('role_key', $label)->exists()) {
                    RoleAssignment::forceCreate(['user_id' => $user->id, 'event_id' => $event->id, 'role_key' => $label]);
                }
            }
        });
    }
}
