<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlists extends Model
{
    use HasFactory;

    protected $table = 'playlists';
    protected $quarde = false;
    protected $guarded = [];

    public function videos()
    {
        return $this->hasMany(PlaylistVideo::class, 'playlist_id', 'id');
    }
    public function relatedVideos()
    {
        return $this->belongsToMany(PlaylistVideo::class, 'playlist_video', 'playlist_id', 'video_id');
    }
}
