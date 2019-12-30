<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class AdminCreate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "
        admin:create
        {email? : The administrator's email address (leave blank to prompt)}
        {password? : The administrator's password (leave blank to prompt)}
        {username? : The administrator's username (leave blank to prompt)}
    ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an user with admin privileges';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->argument('email') ?? $this->ask('Email address');
        $password = $this->argument('password') ?? $this->secret('Password');
        $username = $this->argument('username') ?? $this->ask('Username');

        // Create an user, mark it as an administrator then save it
        // (`markEmailAsVerified()` automatically saves the model)
        $user = new User();
        $user->forceFill([
            'username' => $username,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ])->markEmailAsVerified();

        $this->info("Administrator \"$username\" <$email> created!");
    }
}
