<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'pending_date',
        'inprogress_date',
        'completed_date',
        'cancelled_date'
    ];
}
