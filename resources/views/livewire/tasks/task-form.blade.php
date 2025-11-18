<div class="max-w-2xl mx-auto px-4 py-8">
  <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-lg">
    <!-- Header -->
    <div class="border-b border-gray-200 pb-5 mb-8">
      <h1 class="text-3xl font-bold text-gray-900">
        {{ $task ? 'Edit Task' : 'Create New Task' }}
      </h1>
      <p class="mt-2 text-sm text-gray-600">
        {{ $task ? 'Update the details of your task' : 'Fill in the information below to create a new task' }}
      </p>
    </div>

    <form wire:submit="save" class="space-y-6">
      <!-- Title Field -->
      <div>
        <label for="title" class="block text-sm font-semibold text-gray-800 mb-2">
          Task Title <span class="text-red-500">*</span>
        </label>
        <input type="text" id="title" wire:model="title"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('title') border-red-500 ring-2 ring-red-200 @enderror"
          placeholder="Enter a descriptive title for your task" required>
        @error('title')
          <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
            </svg>
            {{ $message }}
          </p>
        @enderror
      </div>

      <!-- Description Field -->
      <div>
        <label for="description" class="block text-sm font-semibold text-gray-800 mb-2">
          Description
        </label>
        <textarea id="description" wire:model="description" rows="4"
          class="w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none @error('description') border-red-500 ring-2 ring-red-200 @enderror"
          placeholder="Provide additional details about this task..."></textarea>
        @error('description')
          <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
            </svg>
            {{ $message }}
          </p>
        @enderror
        <p class="mt-2 text-xs text-gray-500 flex items-center gap-1">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Optional: Add any relevant details or context
        </p>
      </div>

      <!-- Tags Field with Search -->
      <div>
        <label for="tag-search" class="block text-sm font-semibold text-gray-800 mb-2">
          Tags
        </label>

        <!-- Search Input -->
        <div class="relative">
          <div class="relative">
            <input type="text" id="tag-search" wire:model.live="tagSearch"
              class="w-full px-4 py-3 pr-10 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
              placeholder="Search for tags..." autocomplete="off">
            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
          </div>

          <!-- Search Results Dropdown -->
          @if($showResults && !empty($searchResults))
            <div class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden">
              <div class="py-1">
                @foreach($searchResults as $tag)
                  <button type="button" wire:click="addTag({{ $tag['id'] }})"
                    class="w-full text-left px-4 py-3 hover:bg-blue-50 text-sm text-gray-900 flex items-center justify-between transition-colors group">
                    <span class="font-medium">{{ $tag['name'] }}</span>
                    <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none"
                      stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                  </button>
                @endforeach
              </div>
              <div class="bg-gray-50 px-4 py-2 border-t border-gray-200">
                <p class="text-xs text-gray-500 text-center">Showing top {{ count($searchResults) }} matches</p>
              </div>
            </div>
          @elseif($tagSearch && strlen($tagSearch) >= 2)
            <div class="absolute z-10 w-full mt-2 bg-white border border-gray-200 rounded-lg shadow-xl overflow-hidden">
              <div class="px-4 py-8 text-center">
                <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <p class="text-sm text-gray-500 font-medium">No tags found</p>
                <p class="text-xs text-gray-400 mt-1">Try a different search term</p>
              </div>
            </div>
          @endif
        </div>

        @error('selectedTags')
          <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd"
                d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z"
                clip-rule="evenodd" />
            </svg>
            {{ $message }}
          </p>
        @enderror

        <!-- Selected Tags Display -->
        @if(!empty($selectedTags))
          <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
            <p class="text-xs font-semibold text-gray-700 uppercase tracking-wide mb-3">Selected Tags</p>
            <div class="flex flex-wrap gap-2">
              @foreach($selectedTags as $tagId)
                @php
                  $tag = \App\Models\Tag::find((int) $tagId);
                @endphp
                @if($tag)
                  <div
                    class="inline-flex items-center bg-blue-500 text-white px-4 py-2 rounded-full text-sm font-medium shadow-sm hover:bg-blue-600 transition-colors">
                    <svg class="w-3.5 h-3.5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd"
                        d="M17.707 9.293a1 1 0 010 1.414l-7 7a1 1 0 01-1.414 0l-7-7A.997.997 0 012 10V5a3 3 0 013-3h5c.256 0 .512.098.707.293l7 7zM5 6a1 1 0 100-2 1 1 0 000 2z"
                        clip-rule="evenodd" />
                    </svg>
                    {{ $tag->name }}
                    <button type="button" wire:click="removeTag('{{ $tagId }}')"
                      class="ml-2 bg-transparent border-none cursor-pointer text-white hover:text-blue-100 text-lg w-5 h-5 rounded-full flex items-center justify-center hover:bg-blue-600 transition-colors">
                      &times;
                    </button>
                  </div>
                @endif
              @endforeach
            </div>
          </div>
        @else
          <p class="mt-3 text-xs text-gray-500 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
            </svg>
            Start typing to search and add tags (min. 2 characters)
          </p>
        @endif
      </div>

      <!-- Form Actions -->
      <div class="flex gap-4 pt-6 mt-8 border-t border-gray-200">
        <a href="{{ route('tasks.index') }}"
          class="flex-1 bg-white hover:bg-gray-50 text-gray-700 font-semibold py-3 px-6 rounded-lg text-center transition-all border-2 border-gray-300 hover:border-gray-400">
          Cancel
        </a>
        <button type="submit"
          class="flex-1 bg-gradient-to-r from-[#667eea] to-[#764ba2] hover:from-[#5568d3] hover:to-[#6a3f8f] text-white font-semibold py-3 px-6 rounded-lg flex items-center justify-center gap-2 transition-all shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
          </svg>
          {{ $task ? 'Update Task' : 'Create Task' }}
        </button>
      </div>
    </form>
  </div>
</div>