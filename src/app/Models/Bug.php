<?php

namespace App\Models;

use App\BugSeverity;
use App\BugStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bug extends Model
{
    use HasFactory;

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

    public function history(){
        return $this->hasMany(BugHistory::class);
    }
}
