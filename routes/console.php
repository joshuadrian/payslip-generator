<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('payslip:migrate {--fresh} {--seed} {--force}', function () {
    $this->info('Refreshing database...');

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

    $this->info('Migration completed.');

    if ($this->option('seed')) {
        $this->info('Seeding database...');

        Artisan::call('db:seed', [
            '--force' => $this->option('force'),
        ]);

        $this->line(Artisan::output());

        $this->info('Seeding completed.');
    }
})->purpose('Run fresh migrations and optionally seed the database.');
