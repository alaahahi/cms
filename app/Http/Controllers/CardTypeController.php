<?php

namespace App\Http\Controllers;

use App\Models\Card_type;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;
use Orion\Concerns\DisablePagination;

class CardTypeController extends Controller
{
    use DisablePagination;
    use DisableAuthorization;
    protected $model = Card_type::class; 
}
