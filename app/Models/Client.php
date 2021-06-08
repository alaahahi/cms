<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
//use TCG\Voyager\Traits\Translatable;

class Client extends Model
{
    protected $table = 'client';
   // use Translatable;
   // protected $translatable = ['full_name', 'phone'];
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
