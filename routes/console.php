<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\DemoSeeder;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('smartpos:seed-demo', function () {
    $this->call('db:seed', ['--class' => DemoSeeder::class]);
    $this->info('Demo data seeded.');
})->purpose('Seed demo data (users, products, customers)');
