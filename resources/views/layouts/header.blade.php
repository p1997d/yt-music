<nav class="navbar navbar-expand-lg border-bottom ">
    <div class="container-fluid d-flex justify-content-between align-items-center ms-2">
        <div>
            <a class="navbar-brand" href="/"><i class="bi bi-cassette"></i> @yield('title')</a>
        </div>
        <div class="w-25">
            @auth
                <form class="d-flex mb-0" role="search" id="searchForm">
                    @csrf
                    <input class="form-control me-1 text-input" type="search" placeholder="Поиск" aria-label="Search" name="query" id="searchInput" required>
                </form>
            @endauth
        </div>
    </div>
</nav>
