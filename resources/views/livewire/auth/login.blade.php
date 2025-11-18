<div class="min-h-screen bg-gradient-to-br from-[#667eea] via-[#6f7ee5] to-[#764ba2] relative overflow-hidden">
  <!-- Decorative elements -->
  <div class="absolute top-0 left-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-x-1/2 -translate-y-1/2">
  </div>
  <div class="absolute bottom-0 right-0 w-96 h-96 bg-white/5 rounded-full blur-3xl translate-x-1/2 translate-y-1/2">
  </div>

  <!-- Main content -->
  <div class="flex items-center justify-center min-h-screen relative z-10 px-4">
    <div class="w-full max-w-md">
      <!-- Card with backdrop blur -->
      <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl p-8 border border-white/20">
        <div class="text-center mb-8">
          <h1
            class="text-4xl font-bold bg-gradient-to-r from-[#667eea] to-[#764ba2] bg-clip-text text-transparent mb-2">
            Welcome Back</h1>
          <p class="text-sm text-gray-600">Sign in to your account to continue</p>
        </div>

        <form wire:submit="login" class="space-y-5">
          <div>
            <label for="email" class="block text-sm font-semibold text-gray-800">Email Address</label>
            <input wire:model="email" type="email" id="email"
              class="mt-2 w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea]/20 transition @error('email') border-red-500 @enderror"
              placeholder="you@example.com" />
            @error('email')
              <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div>
            <label for="password" class="block text-sm font-semibold text-gray-800">Password</label>
            <input wire:model="password" type="password" id="password"
              class="mt-2 w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-2 focus:ring-[#667eea]/20 transition @error('password') border-red-500 @enderror"
              placeholder="••••••••" />
            @error('password')
              <p class="mt-1 text-sm text-red-500 font-medium">{{ $message }}</p>
            @enderror
          </div>

          <div class="flex items-center">
            <input wire:model="remember" type="checkbox" id="remember"
              class="w-4 h-4 rounded border-gray-300 text-[#667eea] focus:ring-[#667eea]" />
            <label for="remember" class="ml-2 text-sm text-gray-700 font-medium">Remember me</label>
          </div>

          <button type="submit"
            class="w-full bg-gradient-to-r from-[#667eea] to-[#764ba2] text-white font-semibold py-3 rounded-lg hover:shadow-xl transition transform hover:-translate-y-0.5 active:translate-y-0">
            Sign In
          </button>
        </form>

        <div class="text-center mt-6 pt-6 border-t border-gray-300">
          <p class="text-sm text-gray-700">
            Don't have an account?
            <a href="{{ route('register') }}" class="text-[#667eea] font-semibold hover:underline">Create one</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>