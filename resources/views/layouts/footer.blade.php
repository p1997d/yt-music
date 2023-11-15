<div class="accordion">
    <div class="accordion-item border-0 border-top" style="background: none;">
        <div id="collapseVideo"
            class="accordion-collapse collapse position-absolute w-100 start-0 rounded-top border-top content"
            style="bottom: 0" data-bs-parent="#player-controls">
            <div class="accordion-body d-flex justify-content-center">
                <div id="player">
                    @if (Auth::check() && $currentPlaylist && $currentPlaylist->video_id)
                        <div id="plyr" data-plyr-provider="youtube"
                            data-plyr-embed-id="{{ $currentPlaylist->video_id }}"></div>
                    @else
                        <img src="{{ asset('images/video.png') }}" width="560" height="315"
                            class="border rounded" />
                    @endif
                </div>
            </div>
        </div>
        <div class="position-relative">
            <div class="progress progress-buffer position-absolute w-100 bottom-0" role="progressbar"
                style="cursor: pointer" aria-label="Interactive Progress" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">
                <div id="progress-bar-buffer" class="progress-bar-buffer" style="width: 0%"></div>
            </div>
            <div class="progress progress-seek position-absolute w-100 bottom-0" role="progressbar"
                style="cursor: pointer" aria-label="Interactive Progress" aria-valuenow="0" aria-valuemin="0"
                aria-valuemax="100">
                <div id="progress-bar-seek" class="progress-bar-seek" style="width: 0%"></div>
            </div>
        </div>
        <div class="accordion-header d-flex justify-content-between">
            <nav class="navbar navbar-expand-lg w-100">
                <div class="container-fluid">
                    <div class="d-flex">
                        <div class="btn-group py-3 px-1">
                            <button type="button" class="btn btn-outline-light btn-sm" id="prevButton">
                                <i class="bi bi-skip-start"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" id="pauseButton">
                                <i class="bi bi-play"></i>
                            </button>
                            <button type="button" class="btn btn-outline-light btn-sm" id="nextButton">
                                <i class="bi bi-skip-end"></i>
                            </button>
                        </div>
                        <div class="py-3 px-1 z-2">
                            <img src="" class="rounded-circle thumbnail" id="videoThumbnail" width="32"
                                height="32" style="display: none">
                        </div>
                        <div class="align-middle m-auto px-2 z-2">
                            <p class="fs-6 m-0 fw-medium" id="videoTitle"></p>
                            <p class="m-0 text-body-secondary fw-medium"><small id="videoChannel"></small></p>
                        </div>
                    </div>
                    <button class="btn btn-outline-light btn-sm" type="button" data-bs-toggle="collapse"
                        data-bs-target="#footerContent" aria-controls="footerContent"
                        aria-label="Toggle navigation">
                        <i class="bi bi-list"></i>
                    </button>
                    <div class="collapse navbar-collapse" id="footerContent">
                        <ul class="navbar-nav me-auto mb-2 mb-lg-0 w-100">
                            <li class="nav-item d-flex justify-content-between w-100">
                                <div class="btn-group py-3 px-1">
                                    <a target="_blank" class="btn btn-outline-light btn-sm" id="videoYouTubeLink"
                                        style="display: none">
                                        <i class="bi bi-youtube"></i>
                                    </a>
                                    <button type="button" class="btn btn-outline-light btn-sm likeButton"
                                        id="likeButton" style="display: none">
                                        <i class="bi bi-suit-heart"></i>
                                    </button>

                                    <div class="dropend" id="addToPlaylist" style="display: none">
                                        <button class="btn btn-outline-light btn-sm dropdown-toggle rounded-start-0"
                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-plus-lg"></i>
                                        </button>
                                        <ul class="dropdown-menu content position-absolute" id="addToPlaylistItems">
                                        </ul>
                                    </div>
                                </div>
                                <div>
                                    <div class="btn-group py-3 px-1" role="group">
                                        <button type="button" class="btn btn-outline-light btn-sm" id="loopButton">
                                            <i class="bi bi-repeat"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-light btn-sm"
                                            id="shuffleButton">
                                            <i class="bi bi-shuffle"></i>
                                        </button>
                                    </div>
                                    <div class="btn-group py-3 px-1 dropup" role="group">
                                        <button type="button" class="btn btn-outline-light btn-sm dropdown-toggle"
                                            data-bs-toggle="dropdown" data-bs-auto-close="outside"
                                            aria-expanded="false" id="volumeButton">
                                            <i class="bi bi-volume-up"></i>
                                        </button>
                                        <div class="dropdown-menu content px-2 mb-2" style="width: 15rem;">
                                            <div class="d-flex align-items-center">
                                                <button type="button" id="muteButton"
                                                    class="btn btn-outline-light btn-sm btn-sm me-2">
                                                    <i class="bi bi-volume-mute"></i>
                                                </button>
                                                <input type="range" class="form-range" id="volumeRange">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="btn-group py-3 px-1" role="group">
                                        <button class="btn btn-outline-light btn-sm d-flex custom-accordion-button"
                                            type="button" data-bs-toggle="collapse" data-bs-target="#collapseVideo"
                                            aria-expanded="false" aria-controls="collapseVideo">
                                            <div><i class="bi bi-film p-1"></i></div>
                                            <div class="arrow-icon"><i class="bi bi-chevron-up"></i></div>
                                        </button>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>
</div>
