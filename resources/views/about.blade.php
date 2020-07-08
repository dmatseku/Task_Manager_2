@extends('layouts.app')

@section('title', 'About Us')

@section('content')
    <h1>About</h1>
    <p>This is a task management site. Here you can view a list of tasks and each one individually, as well as create and modify them.</p>
    <h4>Main menu</h4>
    <p>Here you can see a list of tasks. Each task you can review, delete or modify the status. Also, you can find tasks by name using the search field (press enter after input).
        You can open the task by clicking on its name or create it by clicking on the button "+". When you create new task, it is saved automatically.</p>
    <h4>Task page</h4>
    <p>Here you can view the task and edit it. For editing, you can click on the name or description. You can see the saving status bar at the left-bottom of the task. Also, you can edit other fields.
        Changes take effect automatically immediately after editing. When you edit the date, the status changes automatically. But you can choose next status manually.</p>
    <h4>General</h4>
    <p>Status of the task updates automatically by reloading pages. But you can begin or finish the task earlier than its dates.</p>
@endsection
