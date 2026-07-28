<?php

namespace App\Models\Concerns;

trait Translatable
{
    /**
     * Return the value of "{$field}_{locale}", falling back to "{$field}_cs".
     */
    public function trans(string $field): ?string
    {
        $locale = app()->getLocale();

        return $this->{$field.'_'.$locale} ?? $this->{$field.'_cs'};
    }
}
