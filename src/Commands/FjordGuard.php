<?php

namespace Fjord\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class FjordGuard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fjord:guard';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = require config_path('auth.php');
        $replace = file_get_contents(config_path('auth.php'));

        if (!array_key_exists('fjord', $config['guards'])) {
            $this->info('generating fjord guard');
            $replace = str_replace(
                "'guards' => [",
                "'guards' => [
        'fjord' => [
            'driver' => 'session',
            'provider' => 'fjord_users',
        ],",
                $replace
            );
        }

        if (!array_key_exists('fjord_users', $config['providers'])) {
            $this->info('generating fjord_users provider');
            $replace = str_replace(
                "'providers' => [",
                "'providers' => [
        'fjord_users' => [
            'driver' => 'eloquent',
            'model' => Fjord\User\Models\FjordUser::class,
        ],",
                $replace
            );
        }

        if (!array_key_exists('fjord_users', $config['passwords'])) {
            $this->info('generating fjord_users broker');
            $replace = str_replace(
                "'passwords' => [",
                "'passwords' => [
        'fjord_users' => [
            'provider' => 'fjord_users',
            'table' => 'password_resets',
            'expire' => 60,
            'throttle' => 60,
        ],",
                $replace
            );
        }

        File::put(config_path('auth.php'), $replace);
    }
}
