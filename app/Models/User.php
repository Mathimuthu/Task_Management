<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Department;
use Spatie\Permission\Models\Role;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'mobile',
        'registration_no',
        'department_id',
        'status',
        'address',
        'dob',
        'blood_group',
        'photo',
        'created_by'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin()
    {
        return $this->role === '1';
    }

    public function isManager()
    {
        return $this->role === 'manager';
    }

    public function hasRole($role)
    {
        return $this->role == $role;
    }

    public function scopeRole($query, $roleId)
    {
        return $query->whereHas('roles', function ($q) use ($roleId) {
            $q->where('id', $roleId);
        });
    }
    public function hasAnyRole($roles)
    {
        if (is_array($roles)) {
            // Convert role names to role IDs
            $roleIds = Role::whereIn('name', $roles)->pluck('id')->toArray();
            return in_array($this->role, $roleIds);
        }
        // Convert a single role name to ID
        return $this->role == Role::where('name', $roles)->value('id');
    }


    /**
     * The attributes that should be cast to dates (including the soft delete column).
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    public function departments() 
    {
        return $this->hasOne(Department::class, 'id', 'manager_id');
    }
}
