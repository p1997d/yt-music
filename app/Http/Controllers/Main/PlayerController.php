<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FavoritePlaylistVideo;
use App\Models\Playlists;
use App\Models\PlaylistVideo;
use App\Models\CurrentPlaylist;

class PlayerController extends Controller
{
    public function getLocalInfo(Request $request)
    {
        $video_id = $request->input('video');

        $user_id = auth()->id();

        $favoriteVideos = FavoritePlaylistVideo::where('user_id', auth()->id())->pluck('video_id');
        $playlists = Playlists::where('user_id', auth()->id())->get();
        $playlistsIDs = Playlists::where('user_id', auth()->id())->pluck('id');
        $playlistsVideos = PlaylistVideo::whereIn('playlist_id', $playlistsIDs)->get();

        $currentPlaylist = CurrentPlaylist::where('user_id', $user_id)->first();
        $playlistType = $currentPlaylist->playlist_type;
        $playlistId = $currentPlaylist->playlist_id;

        if (class_exists($playlistType)) {

            if (class_exists($playlistType)) {
                if ($playlistId) {
                    $info = app($playlistType)->find($playlistId)->videos->where('video_id', $video_id)->first();
                } else {
                    $info = app($playlistType)->where('user_id', auth()->id())->where('video_id', $video_id)->first();
                }
            }
        }
        return compact('info', 'favoriteVideos', 'playlists', 'playlistsVideos');
    }
}
