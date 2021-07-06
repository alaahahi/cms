<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    protected $fillable = ['title','start','end'];
    protected $table = 'event';
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
