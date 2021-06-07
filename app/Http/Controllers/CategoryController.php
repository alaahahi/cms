<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class CategoryController extends Controller
{
    use DisablePagination;
    use DisableAuthorization;
    protected $model = Category::class; 
}
