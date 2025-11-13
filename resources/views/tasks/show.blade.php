@extends('layouts.app')

@section('content')
    <div class="max-w-[900px] mx-auto py-8 px-4">
        {{-- <!-- Back Button -->
        <a href="{{ route('tasks.index') }}"
            class="inline-flex items-center text-gray-600 hover:text-black transition-colors duration-200 mb-6 no-underline">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Tasks
        </a> --}}

        <!-- Task Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-br from-[#667eea] to-[#764ba2] p-8 text-white">
                <h1 class="text-3xl font-bold mb-4">{{ $task->title }}</h1>
                <span
                    class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
                    @if($task->completed)
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                clip-rule="evenodd" />
                        </svg>
                        Completed
                    @else
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"
                                clip-rule="evenodd" />
                        </svg>
                        In Progress
                    @endif
                </span>
            </div>

            <!-- Content -->
            <div class="p-8">
                <!-- Description -->
                <div class="mb-8">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Description</h2>
                    <p class="text-gray-700 text-lg leading-relaxed">{{ $task->description ?? 'No description provided' }}
                    </p>
                </div>

                @if($task->long_description)
                    <!-- Long Description -->
                    <div class="mb-8">
                        <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Details</h2>
                        <p class="text-gray-600 leading-relaxed whitespace-pre-line">{{ $task->long_description }}</p>
                    </div>
                @endif

                <!-- Tags -->
                <div class="mb-8">
                    <h2 class="text-xs font-semibold uppercase tracking-wide text-gray-500 mb-3">Tags</h2>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if($task->tags && $task->tags->count() > 0)
                            @foreach($task->tags as $tag)
                                <span
                                    class="inline-flex items-center bg-gray-200 text-gray-700 px-3 py-1.5 rounded-full text-xs font-medium transition-all duration-200 hover:bg-gray-300 hover:-translate-y-0.5">
                                    {{ $tag->name }}
                                </span>
                            @endforeach
                        @else
                            <span class="text-gray-400 italic text-sm">No tags assigned</span>
                        @endif
                    </div>
                </div>

                <!-- Metadata -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-200">
                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Created</p>
                            <p class="text-gray-900 font-medium">{{ $task->created_at->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $task->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-500 mb-1">Last Updated</p>
                            <p class="text-gray-900 font-medium">{{ $task->updated_at->format('M d, Y') }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $task->updated_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 p-6 bg-gray-50 border-t border-gray-200">
                <a href="{{ route('tasks.edit', $task) }}"
                    class="inline-flex items-center px-6 py-2.5 bg-[#667eea] text-white rounded-lg font-medium text-sm no-underline transition-all duration-200 hover:bg-[#5568d3]">
                    Edit Task
                </a>
                <a href="{{ route('tasks.index') }}"
                    class="inline-flex items-center px-6 py-2.5 bg-white text-gray-700 border border-gray-300 rounded-lg font-medium text-sm no-underline transition-all duration-200 hover:bg-gray-50">
                    Back to List
                </a>
                <form method="POST" action="{{ route('tasks.destroy', $task) }}" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center px-6 py-2.5 bg-red-500 text-white rounded-lg font-medium text-sm transition-all duration-200 hover:bg-red-600"
                        onclick="return confirm('Are you sure you want to delete this task?')">
                        Delete Task
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection