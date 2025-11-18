@extends('layouts.app')

@section('content')
  @livewire('tasks.task-form', ['task' => $task]);
@endsection