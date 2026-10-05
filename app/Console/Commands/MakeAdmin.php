<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class MakeAdmin extends Command
{
    protected $signature = 'app:make-admin {email : Email address of the admin account}';

    protected $description = 'Create an admin account, or promote an existing user to admin';

    public function handle(): int
    {
        $email = $this->argument('email');
        $user = User::where('email', $email)->first();

        if ($user) {
            $user->forceFill(['is_admin' => true])->save();
            $this->info("{$email} is now an admin.");

            return self::SUCCESS;
        }

        $name = text('Name', required: true);
        $password = password('Password', required: true, validate: fn ($value) => strlen($value) < 8 ? 'Use at least 8 characters.' : null);

        $user = new User(['name' => $name, 'email' => $email, 'password' => Hash::make($password)]);
        $user->forceFill(['is_admin' => true, 'email_verified_at' => now()])->save();

        $this->info("Created admin account for {$email}.");

        return self::SUCCESS;
    }
}
