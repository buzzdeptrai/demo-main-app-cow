<?php

namespace App\MiniApps\NnvnApisGo\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class BoxConfig extends Model
{
    protected $table = 'nnvn_box_configs';

    protected $fillable = [
        'box_index',
        'url',
        'label',
        'is_active',
    ];

    protected $casts = [
        'box_index' => 'integer',
        'is_active' => 'boolean',
    ];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
