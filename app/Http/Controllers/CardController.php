<?php

namespace App\Http\Controllers;

use App\Models\Card;
use Orion\Http\Controllers\Controller;
use Orion\Concerns\DisableAuthorization;

class CardController extends Controller
{
    use DisableAuthorization;
    protected $model = Card::class; 

}
