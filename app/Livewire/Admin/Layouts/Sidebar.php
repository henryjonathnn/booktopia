<?php

namespace App\Livewire\Admin\Layouts;

use Livewire\Component;

class Sidebar extends Component
{

    protected $listeners = ['toggleSidebar'];
    
    public function render()
    {
        return view('livewire.admin.layouts.sidebar');
    }
}
