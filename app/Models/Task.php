<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

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
        'status'
    ];

    protected $casts = [
        'employee_ids' => 'array',
    ];
}
