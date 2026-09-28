<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MiniAppSetting extends Model
{
    protected $fillable = [
        'mini_app_id', 'key', 'value', 'type',
    ];

    // -- Relationships --
    public function miniApp()
    {
        return $this->belongsTo(MiniApp::class);
    }
}
