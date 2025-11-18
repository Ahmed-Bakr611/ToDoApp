@extends('layouts.app')

@section('content')
    @livewire('tasks.task-detail', ['task' => $task])
@endsection