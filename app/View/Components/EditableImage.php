<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditableImage extends Component {
    /**
     * Create a new component instance.
     */
    public function __construct(
        public $type = 'img',
        public $key = '',
        public $iconCenter = false,
        public $iconSize = 24,
        public $iconColor = 'var(--moniz-primary)',
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.editable-image');
    }
}
