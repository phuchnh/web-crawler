<?php
/*
|--------------------------------------------------------------------------
| TypeRocket Routes
|--------------------------------------------------------------------------
|
| Manage your web routes here.
|
*/

tr_route()->post()->match('crawler/preview')->do([\App\Controllers\CrawlPreviewController::class, 'handle']);