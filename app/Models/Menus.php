<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
//use TCG\Voyager\Traits\Translatable;

class Menus extends Model
{
    protected $table = 'menus';
   // use Translatable;
   // protected $translatable = ['full_name', 'phone'];
    use HasFactory;

}
