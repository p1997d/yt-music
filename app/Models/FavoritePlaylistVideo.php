<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavoritePlaylistVideo extends Model
{
    use HasFactory;

    protected $table = 'favorite_playlist_video';
    protected $quarde = false;
    protected $guarded = [];
}
