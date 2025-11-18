<div class="max-w-[900px] mx-auto py-8 px-4">
  <!-- Task Card -->
  <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
    <!-- Header -->
    <div class="bg-gradient-to-br from-[#667eea] to-[#764ba2] p-8 text-white">
      <h1 class="text-3xl font-bold mb-4">{{ $task->title }}</h1>
      <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium bg-white/20 backdrop-blur-sm">
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
        <p class="text-gray-700 text-lg leading-relaxed">{{ $task->description ?? 'No description provided' }}</p>
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
            <p class="text-xs font-medium text-gray-500 uppercase">Created</p>
            <p class="text-sm text-gray-900">{{ $task->created_at->format('M d, Y \a\t g:i A') }}</p>
          </div>
        </div>
        <div class="flex gap-3">
          <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
            </svg>
          </div>
          <div>
            <p class="text-xs font-medium text-gray-500 uppercase">Last Updated</p>
            <p class="text-sm text-gray-900">{{ $task->updated_at->format('M d, Y \a\t g:i A') }}</p>
          </div>
        </div>
      </div>

      <!-- Actions -->
      <div class="flex gap-3 pt-6 mt-6 border-t border-gray-200">
        <a href="{{ route('tasks.index') }}"
          class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-center transition-colors">
          Back to Tasks
        </a>
        <a href="{{ route('tasks.edit', $task->id) }}"
          class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
          Edit Task
        </a>
        <button wire:click="deleteTask" wire:confirm="Are you sure you want to delete this task?"
          class="flex-1 bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-md text-center transition-colors">
          Delete Task
        </button>
      </div>
    </div>
  </div>
</div>