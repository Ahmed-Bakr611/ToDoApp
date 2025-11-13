@extends('layouts.app')

@section('content')
  <div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
      <div class="border-b border-gray-300 pb-4 mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Edit Tag</h1>
      </div>

      <!-- Success Message -->
      @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
          {{ session('success') }}
        </div>
      @endif

      <!-- Error Messages -->
      @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('tags.update', $tag->id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tag Name Field -->
        <div class="mb-6">
          <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
            Tag Name <span class="text-red-500">*</span>
          </label>
          <input type="text" id="name" name="name"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
            value="{{ old('name', $tag->name) }}" placeholder="Enter tag name" required autofocus>
          @error('name')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500">
            Choose a descriptive name for your tag. This will update across all tasks using this tag.
          </p>
        </div>

        <!-- Tag Information Display -->
        <div class="bg-gray-50 border border-gray-200 rounded-md p-4 mb-6">
          <h3 class="text-sm font-medium text-gray-700 mb-2">Tag Information</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
            <div>
              <span class="font-medium">Created:</span>
              {{ $tag->created_at->format('M j, Y \a\t g:i A') }}
            </div>
            <div>
              <span class="font-medium">Last Updated:</span>
              {{ $tag->updated_at->format('M j, Y \a\t g:i A') }}
            </div>
            <div class="md:col-span-2">
              <span class="font-medium">Used in:</span>
              <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-semibold ml-2">
                {{ $tag->tasks_count ?? 0 }} {{ Str::plural('task', $tag->tasks_count ?? 0) }}
              </span>
            </div>
          </div>
        </div>

        <!-- Form Actions -->
        <div class="flex gap-3 pt-6 mt-6 border-t border-gray-300">
          <a href="{{ route('tags.index') }}"
            class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-center transition-colors">
            Cancel
          </a>
          <button type="submit"
            class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md flex items-center justify-center gap-2 transition-colors">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            Update Tag
          </button>
        </div>
      </form>
    </div>
  </div>
@endsection