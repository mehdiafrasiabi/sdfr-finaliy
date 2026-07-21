<?php

use Illuminate\Support\Facades\Route;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;

Route::prefix('admin')->group(function () {
    require __DIR__ . '/admin.php';
});

Route::prefix('manager')->group(function () {
    require __DIR__ . '/manager.php';
});

Route::get('/test-error/{code}', function ($code) {
    abort($code);
});

// 👇 اینجا اضافه کن
Route::get('/sitemap.xml', function () {

    $sitemap = Sitemap::create();

    $sitemap->add(
        Url::create('/')
            ->setPriority(1.0)
            ->setChangeFrequency('weekly')
    );

    $sitemap->add(
        Url::create('/start')
            ->setPriority(0.9)
            ->setChangeFrequency('monthly')
    );

    $sitemap->add(
        Url::create('/about-us')
            ->setPriority(0.5)
            ->setChangeFrequency('yearly')
    );

    $sitemap->add(
        Url::create('/contact-us')
            ->setPriority(0.5)
            ->setChangeFrequency('yearly')
    );

    $sitemap->add(
        Url::create('/terms')
            ->setPriority(0.3)
            ->setChangeFrequency('yearly')
    );

    return $sitemap->toResponse(request());
});

require __DIR__ . '/client.php';
