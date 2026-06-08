<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['name', 'theme_identifier', 'preview_image', 'fields_schema', 'is_active'])]
class Template extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'fields_schema' => 'array',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the events associated with the template.
     */
    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }
}
