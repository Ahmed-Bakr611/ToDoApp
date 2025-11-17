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

        <!-- Tags Field with Search -->
        <div class="mb-6">
          <label for="tag-search" class="block text-sm font-medium text-gray-700 mb-2">
            Tags
          </label>

          <!-- Search Input -->
          <div class="relative">
            <input type="text" id="tag-search"
              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
              placeholder="Search tags..." autocomplete="off">
            <div class="absolute right-3 top-2.5 text-gray-400">
            </div>

            <!-- Search Results Dropdown -->
            <div id="tag-results"
              class="absolute z-10 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-y-auto hidden">
              <div id="tag-results-list">
                <!-- Results will be inserted here -->
              </div>
              <div id="tag-loading" class="px-4 py-3 text-sm text-gray-500 text-center hidden">
                Searching...
              </div>
              <div id="tag-no-results" class="px-4 py-3 text-sm text-gray-500 text-center hidden">
                No tags found
              </div>
            </div>
          </div>

          @error('tags')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror

          <!-- Selected Tags Display -->
          <div id="selected-tags-container" class="flex flex-wrap gap-2 mt-3">
            <!-- Selected tags will appear here -->
          </div>

          <!-- Hidden inputs for selected tags -->
          <div id="hidden-tags-container">
            <!-- Hidden inputs will be added here -->
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
      const tagSearch = document.getElementById('tag-search');
      const tagResults = document.getElementById('tag-results');
      const tagResultsList = document.getElementById('tag-results-list');
      const tagLoading = document.getElementById('tag-loading');
      const tagNoResults = document.getElementById('tag-no-results');
      const selectedTagsContainer = document.getElementById('selected-tags-container');
      const hiddenTagsContainer = document.getElementById('hidden-tags-container');

      let selectedTags = new Map(); // Store selected tags: id => name
      let searchTimeout;

      // Initialize with old values if validation failed
      @if(old('tags'))
        const oldTags = @json(old('tags'));
        // You might want to fetch tag names for old IDs via AJAX
        oldTags.forEach(tagId => {
          selectedTags.set(tagId, `Tag ${tagId}`); // Temporary, should fetch real names
        });
        updateSelectedTagsDisplay();
      @endif

      // Debounced search function
      tagSearch.addEventListener('input', function () {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
          tagResults.classList.add('hidden');
          return;
        }

        tagLoading.classList.remove('hidden');
        tagResultsList.innerHTML = '';
        tagNoResults.classList.add('hidden');
        tagResults.classList.remove('hidden');

        searchTimeout = setTimeout(() => {
          searchTags(query);
        }, 300);
      });

      // Close dropdown when clicking outside
      document.addEventListener('click', function (e) {
        if (!tagSearch.contains(e.target) && !tagResults.contains(e.target)) {
          tagResults.classList.add('hidden');
        }
      });

      // Search tags via AJAX
      function searchTags(query) {
        fetch(`{{ route('tags.search') }}?q=${encodeURIComponent(query)}`, {
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        })
          .then(response => response.json())
          .then(data => {
            tagLoading.classList.add('hidden');

            if (data.tags && data.tags.length > 0) {
              renderTagResults(data.tags);
              tagNoResults.classList.add('hidden');
            } else {
              tagResultsList.innerHTML = '';
              tagNoResults.classList.remove('hidden');
            }
          })
          .catch(error => {
            console.error('Error searching tags:', error);
            tagLoading.classList.add('hidden');
            tagResultsList.innerHTML = '<div class="px-4 py-3 text-sm text-red-500">Error searching tags</div>';
          });
      }

      // Render search results
      function renderTagResults(tags) {
        tagResultsList.innerHTML = '';

        tags.forEach(tag => {
          const isSelected = selectedTags.has(tag.id.toString());

          const resultItem = document.createElement('div');
          resultItem.className = `px-4 py-2 hover:bg-gray-100 cursor-pointer flex items-center justify-between ${isSelected ? 'bg-blue-50' : ''}`;
          resultItem.innerHTML = `
                  <span class="text-sm text-gray-900">${escapeHtml(tag.name)}</span>
                  ${isSelected ? '<span class="text-xs text-blue-600">✓ Selected</span>' : ''}
                `;

          if (!isSelected) {
            resultItem.addEventListener('click', () => {
              addTag(tag.id, tag.name);
              tagSearch.value = '';
              tagResults.classList.add('hidden');
            });
          }

          tagResultsList.appendChild(resultItem);
        });
      }

      // Add a tag to selection
      function addTag(id, name) {
        selectedTags.set(id.toString(), name);
        updateSelectedTagsDisplay();
      }

      // Remove a tag from selection
      function removeTag(id) {
        selectedTags.delete(id.toString());
        updateSelectedTagsDisplay();
      }

      // Update the display of selected tags
      function updateSelectedTagsDisplay() {
        // Update visual display
        selectedTagsContainer.innerHTML = '';

        selectedTags.forEach((name, id) => {
          const tagPill = document.createElement('div');
          tagPill.className = 'inline-flex items-center bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm';
          tagPill.innerHTML = `
                  ${escapeHtml(name)}
                  <button 
                    type="button" 
                    class="ml-2 bg-transparent border-none cursor-pointer text-blue-600 hover:text-blue-800 text-lg w-5 h-5 rounded-full flex items-center justify-center hover:bg-blue-200 transition-colors"
                    data-tag-id="${id}"
                  >
                    &times;
                  </button>
                `;

          const removeBtn = tagPill.querySelector('button');
          removeBtn.addEventListener('click', () => removeTag(id));

          selectedTagsContainer.appendChild(tagPill);
        });

        // Update hidden inputs for form submission
        hiddenTagsContainer.innerHTML = '';
        selectedTags.forEach((name, id) => {
          const input = document.createElement('input');
          input.type = 'hidden';
          input.name = 'tags[]';
          input.value = id;
          hiddenTagsContainer.appendChild(input);
        });
      }

      // Escape HTML to prevent XSS
      function escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
      }
    });
  </script>

  <style>
    #tag-results::-webkit-scrollbar {
      width: 8px;
    }

    #tag-results::-webkit-scrollbar-track {
      background: #f1f1f1;
      border-radius: 4px;
    }

    #tag-results::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 4px;
    }

    #tag-results::-webkit-scrollbar-thumb:hover {
      background: #555;
    }
  </style>
@endsection