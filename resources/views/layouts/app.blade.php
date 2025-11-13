<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Taskly - @yield('title')</title>
  @vite('resources/css/app.css')
</head>

<body class="bg-gradient-to-br from-[#667eea] to-[#764ba2] min-h-screen p-5 text-gray-900 font-sans">
  <div class="max-w-[1200px] mx-auto">
    <header
      class="bg-white/95 backdrop-blur-sm py-5 px-8 rounded-2xl mb-6 shadow-2xl flex justify-between items-center flex-wrap gap-4">
      <a href="{{ auth()->check() ? route('tasks.index') : route('login') }}"
        class="flex items-center gap-3 no-underline text-inherit">
        <svg class="w-8 h-8 text-[#667eea]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
        </svg>
        <h1 class="text-2xl font-bold bg-gradient-to-br from-[#667eea] to-[#764ba2] bg-clip-text text-transparent">Task
          Manager</h1>
      </a>

      <nav class="flex gap-3 items-center">
        @auth
          <a href="{{ route('tasks.index') }}"
            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-[#667eea] transition-all duration-200 {{ request()->routeIs('tasks.index') ? 'bg-[#667eea] text-white' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            All Tasks
          </a>
          <a href="{{ route('tags.index') }}"
            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-[#667eea] transition-all duration-200 {{ request()->routeIs('tags.index') ? 'bg-[#667eea] text-white' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </svg>
            All Tags
          </a>
          <a href="{{ route('tasks.create') }}"
            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-[#667eea] transition-all duration-200 {{ request()->routeIs('tasks.create') ? 'bg-[#667eea] text-white' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            New Task
          </a>

          <div class="flex items-center gap-3 px-4 py-2 bg-gray-100 rounded-lg">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
            </svg>
            <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
          </div>

          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit"
              class="flex items-center gap-1.5 bg-gradient-to-br from-[#667eea] to-[#764ba2] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg">
              <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
              </svg>
              Logout
            </button>
          </form>
        @else
          <a href="{{ route('login') }}"
            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-[#667eea] transition-all duration-200 {{ request()->routeIs('login') ? 'bg-[#667eea] text-white' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
            </svg>
            Login
          </a>
          <a href="{{ route('register') }}"
            class="flex items-center gap-1.5 px-4 py-2 rounded-lg text-sm font-medium text-gray-500 hover:bg-gray-100 hover:text-[#667eea] transition-all duration-200 {{ request()->routeIs('register') ? 'bg-[#667eea] text-white' : '' }}">
            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
            </svg>
            Register
          </a>
        @endauth
      </nav>
    </header>

    <main>
      @if(isset($pageTitle))
        <h2 class="text-3xl font-bold text-white mb-6 drop-shadow-md">{{ $pageTitle }}</h2>
      @else
        <h2 class="text-3xl font-bold text-white mb-6 drop-shadow-md">@yield('title')</h2>
      @endif

      <div class="bg-white/95 backdrop-blur-sm p-8 rounded-2xl shadow-2xl min-h-[400px] animate-fade-in">
        @yield('content')
      </div>
    </main>
  </div>
</body>

</html>