<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskDetail extends Model
{
    protected $fillable = [
        'task_id',
        'meta_data',
    ];

}