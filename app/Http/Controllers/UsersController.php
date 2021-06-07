<?php


namespace App\Http\Controllers;


use App\Models\Users;
use Orion\Http\Controllers\RelationController;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class UsersController extends RelationController
{
    use DisablePagination;
    use DisableAuthorization;
    protected $model = Users::class; // or "App\Models\Post"
    protected $relation = 'user_company';
}