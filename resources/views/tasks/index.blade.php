@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto p-5">
    @if(session('success'))
      <div class="bg-green-100 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-5 text-sm">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-5 text-sm">
        {{ session('error') }}
      </div>
    @endif

    <!-- Header -->
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-3xl font-bold text-gray-900">My Tasks</h1>
      <a href="{{ route('tasks.create') }}" 
         class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-sm hover:shadow-md flex items-center gap-2">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        New Task
      </a>
    </div>

    <!-- Tabs -->
    <div class="mb-6 border-b border-gray-200">
      <nav class="flex gap-6">
        <a href="{{ route('tasks.index', ['tab' => 'active']) }}" 
           class="pb-3 px-1 text-sm font-medium border-b-2 {{ $tab === 'active' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-colors">
          Active Tasks
          <span class="ml-2 {{ $tab === 'active' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full text-xs">
            {{ $activeCount }}
          </span>
        </a>
        <a href="{{ route('tasks.index', ['tab' => 'completed']) }}" 
           class="pb-3 px-1 text-sm font-medium border-b-2 {{ $tab === 'completed' ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} transition-colors">
          Completed
          <span class="ml-2 {{ $tab === 'completed' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }} px-2 py-0.5 rounded-full text-xs">
            {{ $completedCount }}
          </span>
        </a>
      </nav>
    </div>

    @if($tasks->count() > 0)
      <!-- Tasks Grid -->
      <div class="grid gap-4">
        @foreach ($tasks as $task)
          <div class="bg-white border border-gray-200 rounded-lg p-5 hover:shadow-md transition-all duration-200 {{ $task->completed ? 'bg-gray-50 opacity-75' : '' }}">
            <div class="flex items-start gap-4">
              <!-- Checkbox -->
              <div class="flex-shrink-0 mt-1">
                <input type="checkbox" 
                       {{ $task->completed ? 'checked' : '' }}
                       onchange="toggleTask({{ $task->id }}, this)"
                       class="w-5 h-5 {{ $task->completed ? 'text-green-600' : 'text-blue-600' }} border-gray-300 focus:ring-blue-500 cursor-pointer rounded">
              </div>

              <!-- Task Content -->
              <div class="flex-1 min-w-0">
                <div class="flex items-start justify-between gap-4 mb-2">
                  <h3 class="text-lg font-semibold {{ $task->completed ? 'text-gray-500 line-through' : 'text-gray-900' }}">
                    {{ $task->title }}
                  </h3>
                  
                  <!-- Actions -->
                  <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('tasks.edit', $task->id) }}" 
                       class="text-green-600 hover:text-green-700 p-1.5 hover:bg-green-50 rounded transition-colors"
                       title="Edit">
                      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </a>
                    
                    <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this task?');"
                          class="inline m-0">
                      @csrf
                      @method('DELETE')
                      <button type="submit" 
                              class="text-red-600 hover:text-red-700 p-1.5 hover:bg-red-50 rounded transition-colors"
                              title="Delete">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </form>
                  </div>
                </div>

                @if($task->description)
                  <p class="{{ $task->completed ? 'text-gray-500' : 'text-gray-600' }} text-sm mb-3">{{ Str::limit($task->description, 150) }}</p>
                @endif

                <!-- Meta Information -->
                <div class="flex flex-wrap items-center gap-3">
                  @if($task->status)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                      @switch($task->status)
                        @case('pending') bg-yellow-100 text-yellow-700 @break
                        @case('in_progress') bg-blue-100 text-blue-700 @break
                        @case('completed') bg-green-100 text-green-700 @break
                        @default bg-gray-100 text-gray-700
                      @endswitch">
                      {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                    </span>
                  @endif

                  @if($task->priority)
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                      @switch($task->priority)
                        @case('low') bg-gray-100 text-gray-700 @break
                        @case('medium') bg-orange-100 text-orange-700 @break
                        @case('high') bg-red-100 text-red-700 @break
                      @endswitch">
                      {{ ucfirst($task->priority) }} Priority
                    </span>
                  @endif

                  @if($task->due_date)
                    <span class="flex items-center gap-1 text-xs {{ $task->completed ? 'text-gray-400' : 'text-gray-500' }}">
                      <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                      </svg>
                      {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}
                    </span>
                  @endif

                  @if($task->tags->count() > 0)
                    <div class="flex flex-wrap gap-1">
                      @foreach($task->tags as $tag)
                        <span class="{{ $task->completed ? 'bg-purple-50 text-purple-600' : 'bg-purple-100 text-purple-700' }} px-2.5 py-1 rounded-full text-xs font-medium">
                          {{ $tag->name }}
                        </span>
                      @endforeach
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Pagination -->
      <div class="mt-8">
        {{ $tasks->links() }}
      </div>
    @else
      <div class="text-center py-16 px-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No {{ $tab === 'completed' ? 'completed' : 'active' }} tasks</h3>
        <p class="text-gray-500 mb-4">
          @if($tab === 'completed')
            Complete some tasks to see them here
          @else
            Get started by creating your first task
          @endif
        </p>
        @if($tab === 'active')
          <a href="{{ route('tasks.create') }}" 
             class="inline-flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Create Task
          </a>
        @endif
      </div>
    @endif
  </div>

<script>
async function toggleTask(taskId, checkbox) {
  const taskCard = checkbox.closest('.grid > div');
  const grid = document.querySelector('.grid.gap-4');
  const pagination = document.querySelector('.mt-8');
  const currentTab = '{{ $tab }}';
  
  checkbox.disabled = true;
  taskCard.style.transition = 'all 0.3s ease-out';
  taskCard.style.opacity = '0';
  taskCard.style.transform = 'translateX(50px)';

  try {
    const response = await fetch(`/tasks/${taskId}/toggle`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      keepalive: true
    });

    const data = await response.json();
    if (!data.success) throw new Error();

    setTimeout(async () => {
      taskCard.remove();
      updateCounters(data.completed);

      // Try to backfill one more task if pagination exists
      if (pagination) {
        const nextLink = pagination.querySelector('a[rel="next"]');
        if (nextLink) {
          const nextUrl = nextLink.href;

          try {
            const nextPageHtml = await fetch(nextUrl, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
              .then(res => res.text());

            // Parse the new page to get its first task card HTML
            const parser = new DOMParser();
            const doc = parser.parseFromString(nextPageHtml, 'text/html');
            const newTask = doc.querySelector('.grid.gap-4 > div');
            const newPagination = doc.querySelector('.mt-8');

            // Append new task if found
            if (newTask) grid.appendChild(newTask);

            // Replace pagination HTML if exists, or remove if not
            if (newPagination) {
              pagination.outerHTML = newPagination.outerHTML;
            } else {
              pagination.remove();
            }
          } catch (err) {
            console.warn('Failed to fetch next page:', err);
          }
        } else {
          // No next page → check if empty
          checkIfEmpty();
        }
      } else {
        checkIfEmpty();
      }
    }, 300);
  } catch (error) {
    taskCard.style.opacity = '1';
    taskCard.style.transform = 'translateX(0)';
    checkbox.checked = !checkbox.checked;
    checkbox.disabled = false;
    alert('Failed to update task');
  }
}

function updateCounters(wasCompleted) {
  const activeSpan = document.querySelector('a[href*="tab=active"] span');
  const completedSpan = document.querySelector('a[href*="tab=completed"] span');
  
  if (activeSpan && completedSpan) {
    let activeCount = parseInt(activeSpan.textContent);
    let completedCount = parseInt(completedSpan.textContent);
    
    if (wasCompleted) {
      activeCount--;
      completedCount++;
    } else {
      activeCount++;
      completedCount--;
    }
    
    activeSpan.textContent = Math.max(0, activeCount);
    completedSpan.textContent = Math.max(0, completedCount);
  }
}

function checkIfEmpty() {
  const grid = document.querySelector('.grid.gap-4');
  const currentTab = '{{ $tab }}';

  if (grid && grid.children.length === 0) {
    const emptyMessage = currentTab === 'completed'
      ? 'Complete some tasks to see them here'
      : 'Get started by creating your first task';
    
    const emptyHTML = `
      <div class="text-center py-16 px-5 bg-gradient-to-br from-gray-50 to-gray-100 rounded-xl border border-gray-200">
        <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <h3 class="text-xl font-semibold text-gray-600 mb-2">No ${currentTab} tasks</h3>
        <p class="text-gray-500">${emptyMessage}</p>
      </div>
    `;
    
    grid.outerHTML = emptyHTML;
  }
}
</script>

@endsection