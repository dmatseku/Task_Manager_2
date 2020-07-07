@extends('layouts.app')

@section('title', 'Home')

@section('page_style')
    <link href="{{ asset('css/list.css') }}" rel="stylesheet">
@endsection

@section('page_script')
    <script src="{{ asset('js/list.js') }}" defer></script>
@endsection

@section('content')

    {{--  Header  --}}
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Task List</h2>
        <form id="search" class="w-25 ml-5 mr-auto" method="get" action="{{ route('home') }}">
            @csrf
            <input type="text" class="form-control w-100 @error('search') is-invalid @enderror" name="search" placeholder="Search">
        </form>
        <a href="{{ route('task/next_status') }}" role="button" class="btn btn-secondary"><span class="h4 mb-0">+</span></a>
    </div>

    {{-- Table --}}
    <div id="table" class="w-100 rounded-lg mt-3 overflow-hidden">
        <div id="thead" class="w-100 thead-dark d-flex bg-dark text-white">
            <div class="h4 mb-0 cell-name px-3 py-2">Name</div>
            <div class="h4 mb-0 cell-type px-3 py-2">Type</div>
            <div class="h4 mb-0 cell-status px-3 py-2">Status</div>
            <div class="h4 mb-0 cell-actions px-3 py-2"></div>
        </div>
        @include('tasks.part_list')
        <div class="w-100 py-1 bg-dark"></div>
    </div>
@endsection
