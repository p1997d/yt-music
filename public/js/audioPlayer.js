$(document).ready(() => {
    let player, videoURL;
    const progressBarSeek = $('#progress-bar-seek');
    const progressBarBuffer = $('#progress-bar-buffer');
    const progress = $('.progress');

    const loopButton = [
        { icon: '<i class="bi bi-repeat"></i>', active: false },
        { icon: '<i class="bi bi-repeat"></i>', active: true },
        { icon: '<i class="bi bi-repeat-1"></i>', active: true }
    ];

    (function start() {
        const id = $('#plyr').data('plyr-embed-id')
        if (id) {
            getVideo(id, false);
        }

        if (Cookies.get('loop') == undefined) {
            Cookies.set('loop', 0);
        }
        if (Cookies.get('shuffle') == undefined) {
            Cookies.set('shuffle', false);
        }

        $('#loopButton').html(loopButton[Cookies.get('loop')].icon).toggleClass('active', loopButton[Cookies.get('loop')].active);
        $('#shuffleButton').toggleClass('active', JSON.parse(Cookies.get('shuffle')));
    })();

    function progressHandler() {
        var clicking = false;

        player.on('timeupdate', () => {
            const currentTime = player.currentTime;
            const duration = player.duration;
            const buffered = player.buffered;

            const seekPercentage = (currentTime / duration) * 100;
            const bufferedPercentage = buffered * 100;

            progressBarBuffer.css('width', bufferedPercentage + '%');
            progressBarSeek.css('width', seekPercentage + '%');
            progress.attr('aria-valuenow', seekPercentage);
        });

        player.on('ended', () => {
            progressBarSeek.css('width', 0 + '%');
            progress.attr('aria-valuenow', 0);
            nextVideo();
        });

        progress.mousedown((event) => {
            clicking = true;
            updateProgress(event);
        });

        $(document).mousemove((event) => {
            if (clicking) {
                updateProgress(event);
            }
        });

        $(document).mouseup(() => {
            clicking = false;
        });
    }

    function updateProgress(event) {
        const totalWidth = progress.width();
        const clickX = event.clientX - progress.offset().left;
        const newValue = (clickX / totalWidth) * 100;

        const seekTime = player.duration * (newValue / 100);
        player.currentTime = seekTime;

        progressBarSeek.width(newValue + '%').attr('aria-valuenow', newValue);
    }

    function getVideo(id, canPlay) {
        $('#player').html(`<div id="plyr" data-plyr-provider="youtube" data-plyr-embed-id="${id}"></div>`);

        player = new Plyr('#plyr', {
            controls: false,
        });

        player.on('ready', () => {
            videoURL = new URL(player.source);
            $('#volumeRange').val(player.volume * 100);
            setVolumeIcon(player.volume, player.muted);

            $('#videoYouTubeLink').attr('href', `https://www.youtube.com/watch?v=${id}`);

            getInfo(id).then(data => {
                $('#videoTitle').text(data.info.title);
                $('#videoChannel').text(data.info.channel);
                $('#videoThumbnail').attr('src', data.info.thumbnails);
                $('#likeButton').data('id', id).toggleClass('active', data.favoriteVideos.includes(id));

                $('#addToPlaylistItems').html('');
                data.playlists.forEach(playlist => {
                    let icon = data.playlistsVideos.some(video => video.video_id === id && video.playlist_id === playlist.id) ? '<i class="bi bi-check"></i>' : '<i class="bi bi-plus plusIcon"></i>';

                    $('#addToPlaylistItems').append(`<li>
                        <button class="dropdown-item addToPlaylistItem" data-playlist="${playlist.id}" data-id="${id}">
                            ${icon} ${playlist.name}
                        </button>
                    </li>`);
                });
            });

            $('#likeButton, #videoYouTubeLink, #addToPlaylist, #videoThumbnail').show();

            progressHandler();
            if (canPlay) {
                playVideo(id);
            }
        });
    }

    function playVideo(id) {
        $('.playButton').html('<i class="bi bi-play"></i>');
        $(`.playButton[data-id="${id}"]`).html('<i class="bi bi-pause"></i>').prop('disabled', false);
        $('#pauseButton').html('<i class="bi bi-pause"></i>').prop('disabled', false);
        player.play();
    }

    async function getInfo(id) {
        const info = await fetch(`/getLocalInfo`, {
            method: 'POST',
            headers: {
                "X-CSRF-Token": $('input[name="_token"]').val(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "video": id }),
        }).then(response => response.json());
        return info;
    }


    async function setCurrentPlaylist(video, playlist, playlistVideo) {
        await fetch(`/setCurrentPlaylist`, {
            method: 'POST',
            headers: {
                "X-CSRF-Token": $('input[name="_token"]').val(),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ "video": video, "playlist": playlist, 'playlist_video': playlistVideo }),
        });
    }

    async function getCurrentPlaylist() {
        const current = await fetch(`/getCurrentPlaylist`).then(response => response.json());
        return current;
    }

    function setVolumeIcon(volume, muted) {
        if (muted) {
            $('#volumeButton').html('<i class="bi bi-volume-mute"></i>');
            $('#volumeButton').addClass('btn-danger');
            $('#muteButton').addClass('btn-danger');
        }
        else {
            $('#volumeButton').removeClass('btn-danger');
            $('#muteButton').removeClass('btn-danger');
            if (volume >= 0.5) {
                $('#volumeButton').html('<i class="bi bi-volume-up"></i>');
            } else if (volume < 0.5 && volume != 0) {
                $('#volumeButton').html('<i class="bi bi-volume-down"></i>');
            } else if (volume == 0) {
                $('#volumeButton').html('<i class="bi bi-volume-off"></i>');
            }
        }
    }

    function setPause() {
        player.togglePlay();
        if (player.paused) {
            $('#pauseButton').html('<i class="bi bi-play"></i>');
            $(`.playButton[data-id="${videoURL.searchParams.get('v')}"]`).html('<i class="bi bi-play"></i>');
        } else {
            $('#pauseButton').html('<i class="bi bi-pause"></i>');
            $(`.playButton[data-id="${videoURL.searchParams.get('v')}"]`).html('<i class="bi bi-pause"></i>');
        }
    }

    function shufflePlaylist(playlist) {
        playlist.sort(() => Math.random() - 0.5);
    }

    async function prevVideo() {
        let newId;
        const current = await getCurrentPlaylist();
        const playlist = current.playlist;
        const loop = Cookies.get('loop');
        const shuffle = JSON.parse(Cookies.get('shuffle'));

        if (shuffle) {
            shufflePlaylist(playlist);
        }

        const id = videoURL.searchParams.get('v');
        let index = playlist.indexOf(id);
        if (loop == 2) {
            newId = playlist[index];
        } else {
            if (index - 1 >= 0) {
                newId = playlist[index - 1];
            } else {
                if (loop == 1) {
                    newId = playlist[playlist.length - 1];
                }
            }
        }

        if (newId) {
            await setCurrentPlaylist(newId);
            getVideo(newId, true);
        }
    }

    async function nextVideo() {
        let newId;
        const { playlist } = await getCurrentPlaylist();
        const loop = Cookies.get('loop');
        const shuffle = JSON.parse(Cookies.get('shuffle'));

        if (shuffle) {
            shufflePlaylist(playlist);
        }

        const id = videoURL.searchParams.get('v');
        let index = playlist.indexOf(id);

        if (loop == 2) {
            newId = playlist[index];
        } else {
            if (index + 1 < playlist.length) {
                newId = playlist[index + 1];
            } else {
                if (loop == 1) {
                    newId = playlist[0];
                }
            }
        }

        if (newId) {
            await setCurrentPlaylist(newId);
            getVideo(newId, true);
        }
    }

    $(document).on('click', '.playButton', async function () {
        let id = $(this).data('id');
        let playlist = $(this).data('playlist');

        if (!videoURL || id != videoURL.searchParams.get('v')) {
            if (playlist.startsWith('search:')) {
                await setCurrentPlaylist(id, playlist, searchPlaylistVideo);
            }
            else {
                await setCurrentPlaylist(id, playlist);
            }
            getVideo(id, true);
        } else {
            setPause();
        }
    });

    $(document).on('click', '#pauseButton', () => {
        setPause();
    })

    $(document).on('click', '#prevButton', prevVideo)

    $(document).on('click', '#nextButton', nextVideo)

    $('#volumeRange').on('input', function () {
        var volume = $(this).val() / 100;
        player.volume = volume;
        setVolumeIcon(volume);
    });

    $('#muteButton').click(() => {
        player.muted = !player.muted;
        setVolumeIcon(player.volume, player.muted);
    })

    $('#loopButton').click(function () {
        let loop = Cookies.get('loop');
        loop++;
        if (loop > 2) {
            loop = 0;
        }
        Cookies.set('loop', loop);
        $(this).html(loopButton[loop].icon).toggleClass('active', loopButton[loop].active)
    })

    $('#shuffleButton').click(function () {
        let shuffle = JSON.parse(Cookies.get('shuffle'));
        Cookies.set('shuffle', !shuffle);
        $(this).toggleClass('active', !shuffle)
    })
})
