<?php
// app/View/Components/Modal.php
namespace App\View\Components;

use Illuminate\View\Component;

class Modal extends Component
{
    public $show = false;
    
    public function __construct()
    {
        //
    }

    public function render()
    {
        return view('components.modal');
    }
}