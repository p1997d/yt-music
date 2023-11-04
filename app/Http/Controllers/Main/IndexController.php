<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\FavoritePlaylistVideo;
use App\Models\Playlists;
use App\Models\PlaylistVideo;
use App\Models\CurrentPlaylist;
use App\Models\SearchPlaylists;
use App\Models\SearchPlaylistVideo;

class IndexController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect('/playlist/likes');
        } else {
            return view('main.index');
        }
    }

    public function search(Request $request)
    {
        $favoriteVideos = FavoritePlaylistVideo::where('user_id', auth()->id())->pluck('video_id');
        $playlists = Playlists::where('user_id', auth()->id())->get();
        $playlistsIDs = Playlists::where('user_id', auth()->id())->pluck('id');
        $playlistsVideos = PlaylistVideo::whereIn('playlist_id', $playlistsIDs)->get();

        $query = $request->input('query');
        $response = Http::get("https://youtube-redirect.b4a.app/search/$query");

        if ($response->successful()) {
            $search = $response->json();
            return compact('search', 'favoriteVideos', 'playlists', 'playlistsVideos');
        } else {
            return "Ошибка: " . $response->status();
        }
    }

    public function favoritePlaylist()
    {
        $favoriteVideos = FavoritePlaylistVideo::where('user_id', auth()->id())->pluck('video_id');
        $currentPlaylist = CurrentPlaylist::where('user_id', auth()->id())->first();

        $playlistVideo = FavoritePlaylistVideo::where('user_id', auth()->id());
        $videos = $playlistVideo->paginate(8);
        $count = getTrackForm($playlistVideo->count());
        $title = '«Мне нравится»';

        $playlists = Playlists::where('user_id', auth()->id())->get();
        $allPlaylistVideo = PlaylistVideo::whereIn('playlist_id', $playlists->pluck('id'))->get();

        return view('main.index', compact('videos', 'playlists', 'favoriteVideos', 'title', 'count', 'allPlaylistVideo', 'currentPlaylist'));
    }

    public function currentPlaylist()
    {
        $favoriteVideos = FavoritePlaylistVideo::where('user_id', auth()->id())->pluck('video_id');

        $currentPlaylist = CurrentPlaylist::where('user_id', auth()->id())->first();
        $playlistType = $currentPlaylist->playlist_type;
        $playlist = $currentPlaylist->playlist_id;
        $title = 'Текущий список воспроизведения';
        if (class_exists($playlistType)) {
            if ($playlistType === 'App\Models\FavoritePlaylistVideo') {
                $playlistVideo = FavoritePlaylistVideo::where('user_id', auth()->id());
                $title = $title . ": «Мне нравится»";
            } elseif ($playlistType === 'App\Models\Playlists' && Playlists::find($playlist) && Playlists::find($playlist)->user_id == auth()->id()) {
                $playlistVideo = PlaylistVideo::where('playlist_id', $playlist);
                $title = $title . ": «" . Playlists::find($playlist)->name . "»";
            } elseif ($playlistType === 'App\Models\SearchPlaylists') {
                $playlistVideo = SearchPlaylistVideo::where('playlist_id', $playlist);
                $title = $title . ": «Результаты поиска: " . SearchPlaylists::find($playlist)->query . "»";
            } else {
                abort(404);
            }
        }
        $videos = $playlistVideo->paginate(8);
        $count = getTrackForm($playlistVideo->count());

        $playlists = Playlists::where('user_id', auth()->id())->get();
        $allPlaylistVideo = PlaylistVideo::whereIn('playlist_id', $playlists->pluck('id'))->get();

        return view('main.index', compact('videos', 'playlists', 'favoriteVideos', 'title', 'count', 'allPlaylistVideo', 'currentPlaylist'));
    }

    public function playlist($playlist)
    {
        $favoriteVideos = FavoritePlaylistVideo::where('user_id', auth()->id())->pluck('video_id');
        $currentPlaylist = CurrentPlaylist::where('user_id', auth()->id())->first();

        if (Playlists::find($playlist)) {
            if (Playlists::find($playlist)->user_id == auth()->id()) {
                $playlistVideo = PlaylistVideo::where('playlist_id', $playlist);
                $videos = $playlistVideo->paginate(8);
                $count = getTrackForm($playlistVideo->count());
                $title = "«" . Playlists::find($playlist)->name . "»";
            } else {
                abort(403);
            }
        } else {
            abort(404);
        }

        $playlists = Playlists::where('user_id', auth()->id())->get();
        $allPlaylistVideo = PlaylistVideo::whereIn('playlist_id', $playlists->pluck('id'))->get();

        return view('main.index', compact('videos', 'playlists', 'favoriteVideos', 'title', 'count', 'allPlaylistVideo', 'currentPlaylist'));
    }

}

function getTrackForm($count)
{
    if ($count < 10 || $count > 20) {
        $lastDigit = $count % 10;
        if ($lastDigit == 1) {
            return "$count трек";
        } elseif ($lastDigit >= 2 && $lastDigit <= 4) {
            return "$count трека";
        } else {
            return "$count треков";
        }
    } else {
        return "$count треков";
    }
}
