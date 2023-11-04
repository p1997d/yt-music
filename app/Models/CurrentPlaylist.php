<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class CurrentPlaylist extends Model
{
    use HasFactory;

    protected $table = 'current_playlist';
    protected $quarde = false;
    protected $guarded = [];

    public function playlist(): MorphTo
    {
        return $this->morphTo();
    }
}
