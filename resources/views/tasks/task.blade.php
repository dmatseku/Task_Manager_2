@extends('layouts.app')

@section('title', $task['name'])

@section('page_style')
    <link href="{{ asset('css/task.css') }}" rel="stylesheet">
@endsection

@section('page_script')
    <script src="{{ asset('js/task.js') }}" defer></script>
@endsection

@section('content')
    <p><span class="text-danger">*</span> Click on name or text to edit. Changes are saved automatically.</p>
    <div id="task" class="w-100 rounded-lg mt-2 overflow-hidden">
        <div id="header" class="w-100">
            {{-- Name --}}
            <form id="name-form" class="w-100 px-1 bg-dark" method="post" action="{{ route('task/change') }}">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task['id'] }}">

                <div class="form-group w-100 mb-0">
                    <input id="name-field" class="btn text-white h4 mb-0 py-1 font-weight-bold w-100 @error('name') is-invalid @enderror" type="text" name="name" value="{{ $task['name'] }}" placeholder="Name" autocomplete="off">
                    @error('name')
                    <span class="invalid-feedback d-block text-center mt-0" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </form>
        </div>

        <div id="main" class="w-100 pb-3">
            {{-- Description --}}
            <form id="description-form" class="w-100 px-2" method="post" action="{{ route('task/change') }}">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task['id'] }}">

                <div class="form-group w-100 mb-0">
                    <textarea id="description-field" class="btn w-100 my-2 text-left is-invalid @error('description') is-invalid @enderror" name="description" placeholder="Description">{{ $task['description'] }}</textarea>
                    @error('description')
                    <span class="invalid-feedback d-block mt-0" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </form>

            <hr class="mx-4 my-3 border-dark">

            {{-- Type --}}
            <form id="type-form" class="w-100 px-2" method="post" action="{{ route('task/change') }}">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                <div class="form-group w-100 row">
                    <label for="type-field" class="col-md-5 col-form-label text-md-right">Type:</label>
                    <select id="type-field" class="form-control col-md-3" name="type">
                        @foreach($types as $key => $type)
                            <option value="{{ $key }}" @if($type === $task['type']) selected @endif>{{ $type }}</option>
                        @endforeach
                    </select>
                </div>
            </form>

            {{-- Dates --}}
            <form id="dates-form" class="w-100 px-2 mb-2" method="post" action="{{ route('task/change') }}">
                @csrf
                <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                <div class="form-group w-100 row">
                    <label for="begin-field" class="col-md-5 col-form-label text-md-right">Begin in:</label>
                    <div class="form-group mb-0 p-0 col-md-3">
                        <input id="begin-field" type="date" class="form-control @error('begin_in') is-invalid @enderror @error('finish_in') is-invalid @enderror" name="begin_in" value="{{ $task['begin_in'] }}">
                        @error('begin_in')
                        <span class="invalid-feedback d-block mt-0" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group w-100 row">
                    <label for="finish-field" class="col-md-5 col-form-label text-md-right">Finish in:</label>
                    <div class="form-group mb-0 p-0 col-md-3">
                        <input id="finish-field" type="date" class="form-control @error('begin_in') is-invalid @enderror @error('finish_in') is-invalid @enderror" name="finish_in" value="{{ $task['finish_in'] }}">
                        @error('finish_in')
                        <span class="invalid-feedback d-block mt-0" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
            </form>
        </div>

        <div id="footer" class="bg-dark d-flex align-items-center py-2">
            <div class="h-100 w-25 d-flex align-items-center pl-4 text-white">
                Status:&nbsp;<span id="saving-status">Saved</span>
            </div>
            <div id="buttons-panel" class="w-50 d-flex align-items-center justify-content-center">
                <form class="mr-3" method="post" action="{{ route('task/delete') }}">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                    <button class="btn mr-auto ml-0 btn-danger"><span class="h5 mb-0">Delete</span></button>
                </form>
                <button type="button" class="btn btn-primary" onclick="location.href='{{ route('home') }}';"><span class="h5 mb-0">Main menu</span></button>
                @php
                    $status_actions = ['begin', 'finish', 'begin'];
                @endphp
                <form id="btn-next-status" class="ml-3 @if($task['status'] > 2) d-none @else d-block @endif" method="post" action="{{ route('task/next_status') }}">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                    <button class="btn btn-{{ $status_actions[$task['status'] - 1] }}">
                        <span class="h5 mb-0">
                            {{ ucfirst($status_actions[$task['status'] - 1]) }}
                        </span>
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
