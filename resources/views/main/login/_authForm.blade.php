<nav class="navbar navbar-expand-lg col-lg align-items-start">
    <div class="container-fluid flex-lg-column p-0 mb-1">
        <div class="d-flex justify-content-between align-items-center w-100 border-bottom px-3 pb-2">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <h6 class="m-0">Здравствуйте, {{ auth()->user()->name }}</h6>
                <button type="submit"
                    class="btn link-secondary link-offset-2 link-underline-opacity-25 link-underline-opacity-100-hover p-0">
                    выйти
                </button>
            </form>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#playlistContent"
                aria-controls="playlistContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse w-100" id="playlistContent">
            <ul class="nav nav-pills flex-column p-3 w-100" id="playlists">
                <li class="nav-item">
                    <a href="/playlist/likes" class="nav-link link-light item mb-2" aria-current="page">
                        <i class="bi bi-heart"></i>
                        Мне нравится
                    </a>
                </li>
                <li class="nav-item">
                    <a href="/playlist/current" class="nav-link link-light item mb-2" aria-current="page">
                        <i class="bi bi-music-note-list"></i>
                        Список воспроизведения
                    </a>
                </li>
                @foreach ($playlists as $playlist)
                    <li class="nav-item">
                        <a href="/playlist/{{ $playlist->id }}"
                            class="nav-link link-light item mb-2 d-flex justify-content-between" aria-current="page">
                            <span>
                                <i class="bi bi-music-note-beamed"></i>
                                {{ $playlist->name }}
                            </span>
                            <button id="removePlaylist" data-id="{{ $playlist->id }}" class="btn btn-outline-danger"
                                style="--bs-btn-padding-y: .1rem; --bs-btn-padding-x: .3rem; --bs-btn-font-size: .75rem;"><i
                                    class="bi bi-trash"></i></button>
                        </a>
                    </li>
                @endforeach
                <li class="nav-item">
                    <button class="nav-link link-light text-center w-100 item mb-2" data-bs-toggle="modal"
                        data-bs-target="#addPlaylist">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </li>
            </ul>
        </div>
    </div>
</nav>
