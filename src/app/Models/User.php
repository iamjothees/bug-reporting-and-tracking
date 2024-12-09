<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected function casts(): array
    {
        return [
            'role' => UserRole::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function reportedBugs(){
        return $this->hasMany(Bug::class, 'reporter_id');
    }

    public function assignedBugs(){
        return $this->hasMany(Bug::class, 'assignee_id');
    }

    public function updatedBugs(){
        return $this->hasMany(BugHistory::class, 'updater_id');
    }
}
