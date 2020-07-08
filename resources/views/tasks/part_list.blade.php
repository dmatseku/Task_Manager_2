<div id="tbody" class="list-group w-100 my-0">
    @php
        $status_strings = ['created', 'began', 'finished'];
        $status_actions = ['begin', 'finish'];
    @endphp
    @foreach($taskList as $task)
        @php
            switch ($task['status']) {
                case 1:
                    $status_display = "Begin in ".$task['begin_in'];
                    break;
                case 2:
                    $status_display = "Finish in ".$task['finish_in'];
                    break;
                default:
                    $status_display = "Finished";
            }
        @endphp

        <div class="list-group-item rounded-0 border-left-0 border-right-0 d-flex p-0 m-0 w-100">

            <div class="mx-0 px-0 py-0 cell cell-name">
                <form class="h-100 w-100 mx-0" method="get" action="{{ route('task') }}">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                    <button class="btn rounded-0 w-100 h-100 m-0 px-3 text-left">
                        <span class="h4 mb-0">{{ $task['name'] }}</span>
                    </button>
                </form>
            </div>

            <div class="h5 mx-0 mb-0 px-3 py-0 d-flex align-items-center cell cell-type">
                {{ $task['type'] }}
            </div>

            <div class="h5 mx-0 mb-0 px-3 py-0 d-flex align-items-center cell cell-status bg-{{ $status_strings[$task['status'] - 1] }}">
                {{ $status_display }}
            </div>

            <div class="mx-0 px-3 py-0 d-flex align-items-center cell cell-actions">
                <form class="ml-2 delete" method="post" action="{{ route('task/delete') }}">
                    @csrf
                    <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                    <button class="btn btn-danger"><span class="h5 mb-0">Delete</span></button>
                </form>
                @if($task['status'] < 3)
                    <form class="ml-2 next-status" method="post" action="{{ route('task/next_status') }}">
                        @csrf
                        <input type="hidden" name="task_id" value="{{ $task['id'] }}">
                        <button class="btn btn-{{ $status_actions[$task['status'] - 1] }}">
                            <span class="h5 mb-0">
                                {{ ucfirst($status_actions[$task['status'] - 1]) }}
                            </span>
                        </button>
                    </form>
                @endif
            </div>

        </div>
    @endforeach
</div>
