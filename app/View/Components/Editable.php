<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Editable extends Component {
    public $key;

    public $route;

    /**
     * Create a new component instance.
     */
    public function __construct(
        $key, $route = null
    ) {
        $this->key = $key;
        $this->route = $route ?? route('moniz.update');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.editable');
    }
}
