<?php

namespace App\View\Components;

use Illuminate\View\Component;

class FormModal extends Component
{
    public $isOpen;
    public $title;
    public $formConfig;
    public $initialData;
    public $imageField;
    public $submitAction;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct(
        $isOpen = false,
        $title = '',
        $formConfig = [],
        $initialData = null,
        $imageField = null,
        $submitAction = ''
    ) {
        $this->isOpen = $isOpen;
        $this->title = $title;
        $this->formConfig = $formConfig;
        $this->initialData = $initialData;
        $this->imageField = $imageField;
        $this->submitAction = $submitAction;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.form-modal');
    }
}