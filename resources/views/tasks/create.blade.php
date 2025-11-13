@extends('layouts.app')

@section('content')
  <div class="max-w-2xl mx-auto px-4 py-8">
    <div class="bg-white border border-gray-300 rounded-lg p-6 shadow-sm">
      <div class="border-b border-gray-300 pb-4 mb-6">
        <h1 class="text-2xl font-semibold text-gray-900">Create New Task</h1>
      </div>

      <form action="{{ route('tasks.store') }}" method="POST">
        @csrf

        <!-- Title Field -->
        <div class="mb-6">
          <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
            Title <span class="text-red-500">*</span>
          </label>
          <input type="text" id="title" name="title"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('title') border-red-500 @enderror"
            value="{{ old('title') }}" placeholder="Enter task title" required>
          @error('title')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
        </div>

        <!-- Description Field -->
        <div class="mb-6">
          <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
            Description
          </label>
          <textarea id="description" name="description" rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
            placeholder="Enter task description (optional)">{{ old('description') }}</textarea>
          @error('description')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500">Provide additional details about this task</p>
        </div>

        <!-- Tags Field -->
        <div class="mb-6">
          <label for="tags" class="block text-sm font-medium text-gray-700 mb-2">
            Tags
          </label>
          <select id="tags" name="tags[]" multiple
            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('tags') border-red-500 @enderror">
            @foreach($tags as $tag)
              <option value="{{ $tag->id }}" {{ in_array($tag->id, old('tags', [])) ? 'selected' : '' }}
                class="px-2 py-1 hover:bg-gray-100 cursor-pointer">
                {{ $tag->name }}
              </option>
            @endforeach
          </select>
          @error('tags')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
          <p class="mt-1 text-sm text-gray-500">
            Hold down the Ctrl (Windows) or Command (Mac) key to select multiple tags
          </p>

          <!-- Selected Tags Display -->
          <div id="selected-tags-container" class="flex flex-wrap gap-2 mt-3">
            <!-- Selected tags will appear here -->
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
            Create Task
          </button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
      const tagsSelect = document.getElementById('tags');
      const selectedTagsContainer = document.getElementById('selected-tags-container');

      // Function to update the selected tags display
      function updateSelectedTags() {
        // Clear the container
        selectedTagsContainer.innerHTML = '';

        // Get selected options
        const selectedOptions = Array.from(tagsSelect.selectedOptions);

        // Create a tag pill for each selected option
        selectedOptions.forEach(option => {
          const tagPill = document.createElement('div');
          tagPill.className = 'inline-flex items-center bg-gray-200 text-gray-800 px-3 py-1 rounded-full text-sm';
          tagPill.innerHTML = `
                  ${option.text}
                  <button 
                      type="button" 
                      class="ml-2 bg-transparent border-none cursor-pointer text-gray-500 hover:text-gray-700 text-xs w-4 h-4 rounded-full flex items-center justify-center hover:bg-gray-300 transition-colors"
                      data-value="${option.value}"
                  >
                      &times;
                  </button>
              `;
          selectedTagsContainer.appendChild(tagPill);
        });

        // Add event listeners to remove buttons
        document.querySelectorAll('#selected-tags-container .bg-transparent').forEach(button => {
          button.addEventListener('click', function () {
            const valueToRemove = this.getAttribute('data-value');
            const optionToDeselect = tagsSelect.querySelector(`option[value="${valueToRemove}"]`);
            if (optionToDeselect) {
              optionToDeselect.selected = false;
              updateSelectedTags();
            }
          });
        });
      }

      // Initialize selected tags display
      updateSelectedTags();

      // Update display when selection changes
      tagsSelect.addEventListener('change', updateSelectedTags);
    });
  </script>

  <style>
    /* Custom styles for multiple select options */
    select[multiple] option:checked {
      background-color: #3b82f6;
      color: white;
    }

    select[multiple] option:hover {
      background-color: #f3f4f6;
    }
  </style>
@endsection