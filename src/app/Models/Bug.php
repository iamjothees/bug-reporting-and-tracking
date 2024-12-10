<?php

namespace App\Models;

use App\BugSeverity;
use App\BugStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bug extends Model
{
    use HasFactory, SoftDeletes;

    protected $casts = [
        'severity' => BugSeverity::class,
        'status' => BugStatus::class,
    ];

    public function reporter(){
        return $this->belongsTo(User::class);
    }
    
    public function assignee(){
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class);
    }

    public function histories(){
        return $this->hasMany(BugHistory::class);
    }
}
