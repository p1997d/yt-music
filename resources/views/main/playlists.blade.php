<div class="col-lg-9 border-start h-100">
    @auth
        <div class="row border-bottom align-items-center" style="height: 57px">
            <div class="w-100 text-center">
                <p class="m-0">{{ $title }}</p>
                <p class="m-0 text-body-secondary fw-medium"><small>{{ $count }}</small></p>
            </div>
        </div>
        <div class="row justify-content-between h-100">
            @if ($videos != null && count($videos) != 0)
                <ul class="list-group w-100 p-3 h-75" id="videoList">
                    @foreach ($videos as $video)
                        <li class="list-group-item item" id="{{ $video->video_id }}">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="d-flex align-items-center">
                                    <div>
                                        <img src="{{ $video->thumbnails }}" class="rounded-circle me-2 thumbnail"
                                            width="32" height="32" alt="{{ $video->video_id }} thumbnail">
                                    </div>
                                    <div>
                                        <p class="m-0 videoTitle">{{ $video->title }}</p>
                                        <p class="m-0 text-body-secondary videoChannel">{{ $video->channel }}</p>
                                    </div>
                                </div>
                                <div>
                                    <button type="button" data-id="{{ $video->video_id }}"
                                        data-playlist="{{ Request::path() }}"
                                        class="btn btn-outline-light btn-sm playButton">
                                        <i class="bi bi-play"></i>
                                    </button>
                                    <a href="https://www.youtube.com/watch?v={{ $video->video_id }}" target="_blank"
                                        class="btn btn-outline-light btn-sm">
                                        <i class="bi bi-youtube"></i>
                                    </a>
                                    <button type="button" data-id="{{ $video->video_id }}"
                                        class="btn btn-outline-light btn-sm likeButton @if ($favoriteVideos->contains($video->video_id)) active @endif">
                                        <i class="bi bi-suit-heart"></i>
                                    </button>
                                    <div class="btn-group" role="group">
                                        <div class="dropdown">
                                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button"
                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            <ul class="dropdown-menu content position-absolute">
                                                @foreach ($playlists as $playlist)
                                                    <li>
                                                        <button class="dropdown-item addToPlaylistItem"
                                                            data-playlist="{{ $playlist->id }}"
                                                            data-id="{{ $video->video_id }}">
                                                            @if ($allPlaylistVideo->where('playlist_id', $playlist->id)->where('video_id', $video->video_id)->isNotEmpty())
                                                                <i class="bi checkIcon"></i>
                                                            @else
                                                                <i class="bi bi-plus plusIcon"></i>
                                                            @endif {{ $playlist->name }}
                                                        </button>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="text-center w-100 m-3">
                    <p>Этот плейлист сейчас пуст. Добавьте в него треки.</p>
                </div>
            @endif
            {{ $videos->links('partials.pagination') }}
        </div>
    @else
        <div class="row px-3 py-1 border-bottom d-flex align-items-center" style="height: 57px">
            <div class="text-center">Для доступа к спискам треков, пожалуйста, авторизуйтесь.</div>
        </div>
    @endif
</div>
