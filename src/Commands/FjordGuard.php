<?php

namespace AwStudio\Fjord\Commands;

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
        $config = require config_path('auth.php');
        $replace = file_get_contents(config_path('auth.php'));

        if(!array_key_exists('fjord', $config['guards'])){
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

        if(!array_key_exists('fjord_users', $config['providers'])){
            $this->info('generating fjord_users provider');
            $replace = str_replace(
                "'providers' => [",
                "'providers' => [
            'fjord_users' => [
                'driver' => 'eloquent',
                'model' => AwStudio\Fjord\Fjord\Models\FjordUser::class,
            ],",
                $replace
            );
        }


        File::put(config_path('auth.php'), $replace);
    }


}
