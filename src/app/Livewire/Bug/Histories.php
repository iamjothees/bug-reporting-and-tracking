<?php

namespace App\Livewire\Bug;

use App\Models\Bug;
use Livewire\Component;

class Histories extends Component
{
    public Bug $bug;

    public function render(){
        return view('livewire.bug.histories');
    }
}
