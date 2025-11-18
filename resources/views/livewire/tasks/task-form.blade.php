<div class="max-w-2xl mx-auto px-4 py-8">
  <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
    <div class="border-b border-gray-300 pb-4 mb-6">
      <h1 class="text-2xl font-semibold text-gray-900">
        {{ $task ? 'Edit Task' : 'Create New Task' }}
      </h1>
    </div>

    <form wire:submit="save" class="space-y-6">
      <!-- Title Field -->
      <div>
        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
          Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" wire:model="title"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
          placeholder="Enter task title" required>
        @error('title')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
      </div>

      <!-- Description Field -->
      <div>
        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
          Description
        </label>
        <textarea id="description" wire:model="description" rows="4"
          class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
          placeholder="Enter task description (optional)"></textarea>
        @error('description')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
        <p class="mt-1 text-sm text-gray-500">Provide additional details about this task</p>
      </div>

      <!-- Tags Field with Search -->
      <div>
        <label for="tag-search" class="block text-sm font-medium text-gray-700 mb-2">
          Tags
        </label>

        <!-- Search Input -->
        <div class="relative">
          <input type="text" id="tag-search" wire:model.debounce.300ms="tagSearch"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
            placeholder="Search tags..." autocomplete="off">

          <!-- Search Results Dropdown -->
          @if($showResults && !empty($searchResults))
            <div
              class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto">
              @foreach($searchResults as $tag)
                <button type="button" wire:click="addTag('{{ $tag['id'] }}', '{{ $tag['name'] }}')"
                  class="w-full text-left px-4 py-2 hover:bg-gray-100 text-sm text-gray-900 flex items-center justify-between">
                  <span>{{ $tag['name'] }}</span>
                </button>
              @endforeach
            </div>
          @elseif($tagSearch && strlen($tagSearch) >= 2)
            <div class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg">
              <div class="px-4 py-3 text-sm text-gray-500 text-center">
                No tags found
              </div>
            </div>
          @endif
        </div>

        @error('selectedTags')
          <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror

        <!-- Selected Tags Display -->
        <div class="flex flex-wrap gap-2 mt-3">
          @foreach($selectedTags as $tagId)
            @php
              $tag = \App\Models\Tag::find((int) $tagId);
            @endphp
            @if($tag)
              <div class="inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                {{ $tag->name }}
                <button type="button" wire:click="removeTag('{{ $tagId }}')"
                  class="ml-2 bg-transparent border-none cursor-pointer text-blue-600 hover:text-blue-800 text-lg w-5 h-5 rounded-full flex items-center justify-center hover:bg-blue-200 transition-colors">
                  &times;
                </button>
              </div>
            @endif
          @endforeach
        </div>
      </div>

      <!-- Form Actions -->
      <div class="flex gap-3 pt-6 mt-6 border-t border-gray-300">
        <a href="{{ route('tasks.index') }}"
          class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-md text-center transition-colors">
          Cancel
        </a>
        <button type="submit"
          class="flex-1 bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded-md flex items-center justify-center gap-2 transition-colors">
          <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          {{ $task ? 'Update Task' : 'Create Task' }}
        </button>
      </div>
    </form>
  </div>
</div>