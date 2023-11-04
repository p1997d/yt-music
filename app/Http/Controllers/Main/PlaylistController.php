<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\FavoritePlaylistVideo;
use App\Models\Playlists;
use App\Models\PlaylistVideo;
use App\Models\CurrentPlaylist;
use App\Models\SearchPlaylists;
use App\Models\SearchPlaylistVideo;

class PlaylistController extends Controller
{
    public function setCurrentPlaylist(Request $request)
    {
        $user_id = auth()->id();
        $video_id = $request->input('video');
        $path = $request->input('playlist');
        $playlistVideo = $request->input('playlist_video');

        if ($path) {
            if ($path === "playlist/likes") {
                CurrentPlaylist::updateOrCreate(
                    ['user_id' => $user_id],
                    ['video_id' => $video_id, 'playlist_id' => null, 'playlist_type' => 'App\Models\FavoritePlaylistVideo']
                );
            } elseif (preg_match('/^playlist\/\d+$/', $path)) {
                $playlist_id = (int) explode('/', $path)[1];
                $playlist = Playlists::find($playlist_id);
                CurrentPlaylist::updateOrCreate(
                    ['user_id' => $user_id],
                    ['video_id' => $video_id, 'playlist_id' => $playlist_id, 'playlist_type' => $playlist->getMorphClass()]
                );
            } elseif (str_starts_with($path, 'search:')) {
                $searchPlaylists = SearchPlaylists::updateOrCreate(
                    ['user_id' => $user_id],
                    ['query' => explode(': ', $path)[1]]
                );
                $searchPlaylists->relatedVideos()->detach();
                foreach ($playlistVideo as $info) {
                    $model = new SearchPlaylistVideo();
                    $model->playlist_id = $searchPlaylists->id;
                    $model->video_id = $info['id'];
                    $model->title = $info['title'];
                    $model->channel = $info['channel'];
                    $model->thumbnails = $info['thumbnails'];
                    $model->save();
                }
                CurrentPlaylist::updateOrCreate(
                    ['user_id' => $user_id],
                    ['video_id' => $video_id, 'playlist_id' => $searchPlaylists->id, 'playlist_type' => $searchPlaylists->getMorphClass()]
                );
            } else {
                abort(404);
            }
        } else {
            CurrentPlaylist::updateOrCreate(
                ['user_id' => $user_id],
                ['video_id' => $video_id]
            );
        }
    }

    public function getCurrentPlaylist(Request $request)
    {
        $currentPlaylist = CurrentPlaylist::where('user_id', auth()->id())->first();
        $playlistType = $currentPlaylist->playlist_type;
        $playlistId = $currentPlaylist->playlist_id;
        $video = $currentPlaylist->video_id;
        if (class_exists($playlistType)) {
            if ($playlistId) {
                $playlist = app($playlistType)->find($playlistId)->videos->pluck('video_id');
            } else {
                $playlist = app($playlistType)->where('user_id', auth()->id())->pluck('video_id');
            }
        }
        return compact('playlist', 'video');
    }

    public function addPlaylist(Request $request)
    {
        $name = $request->input('namePlaylist');
        $user_id = auth()->id();

        $model = new Playlists();
        $model->user_id = $user_id;
        $model->name = $name;
        $model->save();
    }

    public function removePlaylist(Request $request, Playlists $playlist)
    {
        if ($playlist->user_id == auth()->id()) {
            $playlist->relatedVideos()->detach();
            $playlist->delete();
        }
    }

    public function toggleFavorite(Request $request)
    {
        $video_id = $request->input('id');
        $user_id = auth()->id();
        if (!FavoritePlaylistVideo::where('user_id', $user_id)->where('video_id', $video_id)->exists()) {
            $infoUrl = "https://youtube-redirect.b4a.app/getinfo?v=$video_id";

            $responseInfo = Http::get($infoUrl);

            if ($responseInfo->successful()) {
                $infoJson = json_decode(json_encode($responseInfo->json()));
                $model = new FavoritePlaylistVideo();
                $model->user_id = $user_id;
                $model->video_id = $video_id;
                $model->title = $infoJson->title;
                $model->channel = $infoJson->channel;
                $model->thumbnails = $infoJson->thumbnails;
                $model->save();
            } else {
                return response("Ошибка: " . $responseInfo->status(), $responseInfo->status());
            }
        } else {
            FavoritePlaylistVideo::where('user_id', $user_id)->where('video_id', $video_id)->delete();
        }
    }

    public function addToPlaylist(Request $request, $playlist, $id)
    {
        $user_id = auth()->id();
        if (!PlaylistVideo::where('playlist_id', $playlist)->where('video_id', $id)->exists()) {
            $infoUrl = "https://youtube-redirect.b4a.app/getinfo?v=$id";
            $responseInfo = Http::get($infoUrl);
            if (Playlists::find($playlist)->user_id == $user_id) {
                if ($responseInfo->successful()) {
                    $infoJson = json_decode(json_encode($responseInfo->json()));
                    $model = new PlaylistVideo();
                    $model->playlist_id = $playlist;
                    $model->video_id = $id;
                    $model->title = $infoJson->title;
                    $model->channel = $infoJson->channel;
                    $model->thumbnails = $infoJson->thumbnails;
                    $model->save();
                } else {
                    return response("Ошибка: " . $responseInfo->status(), $responseInfo->status());
                }
            }
        } else {
            PlaylistVideo::where('playlist_id', $playlist)->where('video_id', $id)->delete();
        }
    }
}
