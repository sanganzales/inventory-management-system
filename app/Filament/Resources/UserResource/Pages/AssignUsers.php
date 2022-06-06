<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
//use Filament\Pages\Page;
use Filament\Resources\Pages\Page;

class AssignUsers extends Page
{
    protected static string $resource = UserResource::class;

    protected static string $view = 'filament.resources.user-resource.pages.assign-users';

    // public static function route()
    // {
    //     return UserResource::getUrl('assign-users');

    // }

    //UserResource::getUrl('sort');

}
