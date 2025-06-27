<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('payslip:migrate {--fresh} {--seed} {--data} {--force}', function () {
    // Running migrations
    if ($this->option('fresh')) {

        Artisan::call('migrate:fresh', [
            '--force' => $this->option('force'),
        ]);
    } else {
        Artisan::call('migrate', [
            '--force' => $this->option('force'),
        ]);
    }

    $this->line(Artisan::output());




    // Running seeeders
    if ($this->option('seed')) {
        $this->info('Seeding database...');

        Artisan::call('db:seed', [
            '--force' => $this->option('force'),
        ]);

        $this->line(Artisan::output());
    }


    

    // Running user data seeder
    if ($this->option('data')) {
        Artisan::call('db:seed', [
            '--class' => 'UserDataSeeder',
            '--force' => $this->option('force'),
        ]);

        $this->line(Artisan::output());
    }
})->purpose('Run fresh migrations and optionally seed the database.');
