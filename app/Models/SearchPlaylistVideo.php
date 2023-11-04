<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPlaylistVideo extends Model
{
    use HasFactory;

    protected $table = 'search_playlist_video';
    protected $quarde = false;
    protected $guarded = [];
}
