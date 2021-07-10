<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    protected $fillable = ['title','client_id','service_id','start','end','color'];
    protected $table = 'event';
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
}
