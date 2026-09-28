<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiRequestLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'mini_app_id', 'token_id', 'method', 'path',
        'status_code', 'ip_address', 'response_time_ms', 'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // -- Relationships --
    public function miniApp()
    {
        return $this->belongsTo(MiniApp::class);
    }
}
