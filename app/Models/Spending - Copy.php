<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Spending extends Model
{
    protected $table = 'spending';
    use HasFactory;
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    	protected $fillable = [
		'title', 'start', 'end'
	];
}
