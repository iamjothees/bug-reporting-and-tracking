<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BugHistory extends Model
{
    use HasFactory;

    public function bug(){
        return $this->belongsTo(Bug::class);
    }

    public function updater(){
        return $this->belongsTo(User::class);
    }
}
