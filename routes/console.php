<?php

use Illuminate\Support\Facades\Artisan;

Artisan::command('app:about-tv', function (): void {
    $this->comment('Laravel TV Admin is ready.');
})->purpose('Display project info');
