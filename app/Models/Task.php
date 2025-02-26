<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'attachments',
        'priority',
        'assign_date',
        'deadline',
        'department_id',
        'role_id',
        'employee_ids',
        'status',
        'upload_task'
    ];

    protected $casts = [
        'employee_ids' => 'array',
    ];
    protected $dates = ['deleted_at']; 
}
