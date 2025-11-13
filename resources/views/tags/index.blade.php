@extends('layouts.app')

@section('content')
<div class="max-w-[900px] mx-auto p-5">
    @if(session('success'))
      <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-md mb-5 text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-md mb-5 text-sm">
        {{ session('error') }}
      </div>
    @endif

    <div class="flex justify-between items-center mb-8 pb-4 border-b-2 border-gray-200">
      <h1 class="text-2xl font-semibold text-gray-900 m-0">Tags</h1>
      <span class="text-sm text-gray-500 px-3 py-1.5 bg-gray-100 rounded-full font-medium">
        {{ $tags->total() }} {{ Str::plural('tag', $tags->total()) }}
      </span>
    </div>

    @if($tags->count() > 0)
      <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
        <table class="w-full border-collapse">
          <thead class="bg-gray-50 border-b-2 border-gray-200">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">#</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tag Name</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tasks</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Created</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Updated</th>
              <th class="px-4 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($tags as $index => $tag)
              <tr class="border-b border-gray-200 transition-all duration-200 hover:bg-gray-50 last:border-b-0">
                <td class="px-4 py-4">
                  <div class="w-9 h-9 rounded-md flex items-center justify-center text-white font-semibold text-sm flex-shrink-0 
                    @switch(($tags->firstItem() + $index - 1) % 6)
                      @case(0) bg-gradient-to-br from-[#667eea] to-[#764ba2] @break
                      @case(1) bg-gradient-to-br from-[#f093fb] to-[#f5576c] @break
                      @case(2) bg-gradient-to-br from-[#4facfe] to-[#00f2fe] @break
                      @case(3) bg-gradient-to-br from-[#43e97b] to-[#38f9d7] @break
                      @case(4) bg-gradient-to-br from-[#fa709a] to-[#fee140] @break
                      @case(5) bg-gradient-to-br from-[#30cfd0] to-[#330867] @break
                    @endswitch">
                    {{ $tags->firstItem() + $index }}
                  </div>
                </td>
                <td class="px-4 py-4">
                  <span class="font-medium text-gray-900 capitalize">{{ $tag->name }}</span>
                </td>
                <td class="px-4 py-4">
                  <span class="bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full text-xs font-semibold inline-block">
                    {{ $tag->tasks_count }} {{ Str::plural('task', $tag->tasks_count) }}
                  </span>
                </td>
                <td class="px-4 py-4">
                  <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($tag->created_at)->diffForHumans() }}</span>
                </td>
                <td class="px-4 py-4">
                  <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($tag->updated_at)->diffForHumans() }}</span>
                </td>
                <td class="px-4 py-4">
                  <div class="flex gap-2">
                    <!-- Edit Button -->
                    <a href="{{ route('tags.edit', $tag->id) }}" 
                       class="bg-green-500 hover:bg-green-600 text-white text-xs py-1.5 px-3 rounded flex items-center gap-1 transition-colors">
                      <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                      Edit
                    </a>
                    
                    <!-- Delete Button -->
                    <form action="{{ route('tags.destroy', $tag->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this tag?');"
                          class="inline m-0">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="bg-red-500 hover:bg-red-600 text-white text-xs py-1.5 px-3 rounded flex items-center gap-1 transition-colors">
                        <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div class="mt-6">
        {{ $tags->links() }}
      </div>
    @else
      <div class="text-center py-16 px-5 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
        </svg>
        <h3 class="text-lg font-medium text-gray-500 mb-2">No tags yet</h3>
        <p class="text-gray-400 m-0">No tags available at the moment</p>
      </div>
    @endif
  </div>
@endsection