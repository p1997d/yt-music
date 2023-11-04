let searchPlaylistVideo = [];
$(document).ready(() => {
    $("#searchForm").on("submit", async function (event) {
        event.preventDefault();
        const searchInput = $('#searchInput').val();
        const offcanvasSearch = new bootstrap.Offcanvas('#offcanvasSearch')
        $('#offcanvasSearchLabel').text(searchInput);

        $.ajax({
            url: "/search",
            data: $(this).serialize(),
            beforeSend: function (xhr) {
                $('#searchResult').html(`
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `);
                offcanvasSearch.show();
                $('#searchButton')
                    .prop('disabled', true)
                    .html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>');
            }
        }).done((data) => {
            $('#searchResult').html('');
            searchPlaylistVideo = [];
            $.each(data.search, function (index, item) {
                const $li = $(`<li class="list-group-item pb-3 item" data-id="${item.id}"><hr></li>`);
                const $header = $(`<div class="d-flex align-items-center">
                    <div><img src="${item.bestThumbnail.url}" class="rounded-circle me-2" width="32" height="32" alt="${item.id} thumbnail"></div>
                    <div>
                        <p class="m-0 videoTitle">${item.title}</p>
                        <p class="m-0 text-body-secondary videoChannel">${item.author.name}</p>
                    </div>
                </div>`);
                const $footer = $(`<div class="d-flex buttonsSearch"></div>`);
                const $playButton = $(`<button type="button" data-id="${item.id}" data-playlist="search: ${searchInput}" class="btn btn-outline-light btn-sm playButton mx-1"><i class="bi bi-play"></i></button>`);
                const $youtubeLink = $(`<a href="${item.url}" target="_blank" class="btn btn-outline-light btn-sm mx-1"><i class="bi bi-youtube"></i></a>`);
                const $likeButton = $(`<button type="button" data-id="${item.id}" class="btn btn-outline-light btn-sm likeButton mx-1"><i class="bi bi-suit-heart"></i></button>`);

                $likeButton.toggleClass('active', data.favoriteVideos.includes(item.id));

                const $addToPlaylistButton = $(`<div class="dropdown mx-1">
                    <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-plus-lg"></i>
                    </button>
                </div>`)
                const $addToPlaylistButtonItems = $(`<ul class="dropdown-menu content position-absolute"></ul>`);
                data.playlists.forEach(playlist => {
                    let icon = data.playlistsVideos.some(video => video.video_id === item.id && video.playlist_id === playlist.id) ? '<i class="bi bi-check"></i>' : '<i class="bi bi-plus plusIcon"></i>';

                    $addToPlaylistButtonItems.append(`<li>
                        <button class="dropdown-item addToPlaylistItem" data-playlist="${playlist.id}" data-id="${item.id}">
                            ${icon} ${playlist.name}
                        </button>
                    </li>`);
                });
                $addToPlaylistButton.append($addToPlaylistButtonItems);

                if(!isAuth){
                    $likeButton.hide();
                    $addToPlaylistButton.hide();
                }

                $footer.append($playButton, $youtubeLink, $likeButton, $addToPlaylistButton);

                $li.prepend($header);
                $li.append($footer);

                $('#searchResult').append($li)
                searchPlaylistVideo.push({
                    id: item.id,
                    title: item.title,
                    channel: item.author.name,
                    thumbnails: item.bestThumbnail.url,
                });
            });
            $('#searchButton')
                .prop('disabled', false)
                .html('<i class="bi bi-search"></i>');
            $('#searchResult').trigger('change');
        });
    });
});
