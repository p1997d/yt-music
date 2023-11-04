<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchPlaylists extends Model
{
    use HasFactory;

    protected $table = 'search_playlists';
    protected $quarde = false;
    protected $guarded = [];

    public function videos()
    {
        return $this->hasMany(SearchPlaylistVideo::class, 'playlist_id', 'id');
    }

    public function relatedVideos()
    {
        return $this->belongsToMany(SearchPlaylistVideo::class, 'search_playlist_video', 'playlist_id', 'video_id');
    }
}
