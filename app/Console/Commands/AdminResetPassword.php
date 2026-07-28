<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

#[Signature('admin:reset-password {email} {password}')]
#[Description('Reset the password for any user by email — recovery path if the sole Admin forgets their password.')]
class AdminResetPassword extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email    = $this->argument('email');
        $password = $this->argument('password');

        $validator = Validator::make(compact('email', 'password'), [
            'email'    => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return self::FAILURE;
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Uživatel s e-mailem [{$email}] neexistuje.");
            return self::FAILURE;
        }

        $user->password = Hash::make($password);
        $user->save();

        $this->info("Heslo pro [{$email}] bylo úspěšně změněno.");
        return self::SUCCESS;
    }
}
