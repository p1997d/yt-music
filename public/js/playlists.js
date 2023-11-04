$(document).pjax('a', '#pjaxContainer');
$(document).ready(() => {
    setActiveLink();

    $('#addPlaylistForm').on('submit', async function (event) {
        event.preventDefault();
        $.ajax({
            url: "/addPlaylist",
            method: "POST",
            data: $(this).serialize(),
            beforeSend: function () {
                $('#addPlaylist').modal('hide');
            }
        }).done(() => {
            $.pjax.reload({
                container: "#pjaxContainer",
            });
        });
    });

    $(document).on('click', '#removePlaylist', async function (event) {
        event.stopPropagation();
        event.preventDefault();
        const id = $(this).data('id');
        $.ajax({
            url: `/${id}/removePlaylist`,
            method: "POST",
            headers: {
                "X-CSRF-Token": $('input[name="_token"]').val(),
            },
        }).done(() => {
            $.pjax.reload({
                container: "#pjaxContainer",
                url: window.location.pathname === `/playlist/${id}` ? '/' : '',
            });
        });
    });

    $(document).on('click', '.likeButton', async function () {
        let button = $(this).html();
        $(this).html('<span class="spinner-border spinner-border-sm" aria-hidden="true"></span>').prop('disabled', true);
        const id = $(this).data('id');
        $.ajax({
            url: `/toggleFavorite`,
            method: "POST",
            data: `id=${id}`,
            headers: {
                "X-CSRF-Token": $('input[name="_token"]').val(),
            },
        }).done(() => {
            $.pjax.reload({
                container: "#pjaxContainer",
            });
            $(this).html(button).toggleClass('active').prop('disabled', false);
            $(`.likeButton[data-id="${id}"]`).toggleClass('active');
        });
    });

    $(document).on('click', '.addToPlaylistItem', async function () {
        const id = $(this).data('id');
        const playlist = $(this).data('playlist');
        $.ajax({
            url: `/${playlist}/addToPlaylist/${id}`,
            method: "POST",
            headers: {
                "X-CSRF-Token": $('input[name="_token"]').val(),
            },
        }).done(() => {
            $.pjax.reload({
                container: "#pjaxContainer",
            });
        });
    });
})

$(document).on('pjax:end', function () {
    setActiveLink();
})

function setActiveLink() {
    $("#playlists a").each(function () {
        var href = $(this).attr("href");
        if (href == window.location.pathname) {
            $("#playlists a").removeClass('active');
            $(this).addClass('active');
        }
    });
}
