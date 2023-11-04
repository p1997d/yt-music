@extends('layouts.main')

@section('title', 'YT-Music')

@section('content')
    <div class="app content-main d-flex flex-column">
        <div class="row">
            <div class="col">
                <div class="header">@include('layouts.header')</div>
            </div>
        </div>
        <div class="row flex-fill d-flex m-0" id="pjaxContainer">
            @include('main.login.index')
            @include('main.playlists')
        </div>
        <div class="row">
            <div class="footer">@include('layouts.footer')</div>
        </div>

        <div class="offcanvas offcanvas-end z-1 w-50 content" tabindex="-1" id="offcanvasSearch"
            aria-labelledby="offcanvasRightLabel" data-bs-scroll="true" data-bs-backdrop="false">
            <div class="offcanvas-header">
                <h5 class="offcanvas-title" id="offcanvasSearchLabel"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <ul class="list-group" id="searchResult"></ul>
            </div>
        </div>
    </div>
    @include('main.modals.index')
@endsection
