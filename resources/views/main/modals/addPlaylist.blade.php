<div class="modal fade" id="addPlaylist" tabindex="-1" aria-labelledby="addPlaylistLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content content-main">
            <form id="addPlaylistForm">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="addPlaylistLabel">Добавить плейлист</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @csrf
                    {{-- <label for="namePlaylistInput" class="form-label">Название плейлиста</label> --}}
                    <input class="form-control text-input" type="text" id="namePlaylistInput" name="namePlaylist" placeholder="Название плейлиста">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn button" data-bs-dismiss="modal">Закрыть</button>
                    <button type="submit" class="btn button">Сохранить</button>
                </div>
            </form>
        </div>
    </div>
</div>
