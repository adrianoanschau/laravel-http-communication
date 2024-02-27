<?php

namespace App\Http\Requests\Traits;

trait AuthorizeIfIsAuthenticated {
    public function authorize(): bool
    {
        return !is_null($this->user());
    }
}
