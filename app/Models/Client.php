<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Traits\Translatable;

class Client extends Model
{
    protected $table = 'client';
    use Translatable;
    protected $translatable = ['full_name', 'phone'];
    use HasFactory;
}
