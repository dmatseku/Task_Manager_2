@extends('layouts.app')

@section('title', 'Home')

@section('page_style')
    <link href="{{ asset('css/list.css') }}" rel="stylesheet">
@endsection

@section('content')

    {{--  Header  --}}
    <div class="container d-flex justify-content-between align-items-center">
        <h2 class="mb-0">Task List</h2>
        <form id="search" class="w-25 ml-5 mr-auto" method="post" action="{{ route('home') }}">
            @csrf
            <input type="text" class="form-control w-100 @error('search') is-invalid @enderror" name="search" placeholder="Search">
        </form>
        <a href="{{ route('task/next_status') }}" role="button" class="btn btn-secondary"><span class="h4">+</span></a>
    </div>

    {{-- Table --}}
    <div class="w-100 rounded-lg mt-3 overflow-hidden">
        <table class="table w-100 mb-0">
            <thead class="thead-dark">
                <tr class="w-100">
                    <th class="h4 cell-name px-3 py-1">Name</th>
                    <th class="h4 cell-type px-3 py-1">Type</th>
                    <th class="h4 cell-status px-3 py-1">Status</th>
                    <th class="h4 cell-actions px-3 py-1"></th>
                </tr>
            </thead>
        </table>
        <table id="content-table" class="table w-100 my-0">
            <tbody>
            @php
            $status_strings = ['created', 'began', 'finished'];
            $status_actions = ['begin', 'finish'];
            @endphp
            @foreach($taskList as $task)
                @php
                $status_display = "Begin in ".$task['begin_in'];
                switch ($task['status']) {
                    case 2:
                        $status_display = "Finish in ".$task['finish_in'];
                        break;
                    case 3:
                        $status_display = "Finished";
                }
                @endphp

                <tr class="w-100">

                    <td class="mx-0 px-0 py-0 cell-name">
                        <form class="h-100 w-100 mx-0" method="get" action="{{ route('task') }}">
                            @csrf
                            <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                            <button class="btn rounded-0 w-100 h-100 m-0 px-3 text-left">
                                <span class="mb-0 h4">{{ $task['name'] }}</span>
                            </button>
                        </form>
                    </td>

                    <td class="mx-0 px-3 py-0 h5 cell-type">
                        <div class="h-100 w-100 d-flex align-items-center">{{ $task['type'] }}</div>
                    </td>

                    <td class="mx-0 px-3 py-0 h5 cell-status bg-{{ $status_strings[$task['status'] - 1] }}">
                        <div class="h-100 w-100 d-flex align-items-center">{{ $status_display }}</div>
                    </td>

                    <td class="mx-0 px-3 py-0 cell-actions">
                        <div class="h-100 w-100 d-flex align-items-center">
                            @if($task['status'] < 3)
                                <form class="ml-2 next-status" method="post" action="{{ route('task/next_status') }}">
                                    <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                                    <button class="btn btn-{{ $status_actions[$task['status'] - 1] }}">
                                        <span class="h5">
                                            @if($task['status'] === 1) Begin @else Finish @endif
                                        </span>
                                    </button>
                                </form>
                            @endif
                            <form class="ml-2 delete" method="post" action="{{ route('task/delete') }}">
                                <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                                <button class="btn btn-danger"><span class="h5">Delete</span></button>
                            </form>
                        </div>
                    </td>

                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="w-100 py-1 bg-dark"></div>
    </div>
@endsection
