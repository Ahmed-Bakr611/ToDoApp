<div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-[#667eea] to-[#764ba2]">
  <div class="w-full max-w-md px-6">
    <div class="bg-white rounded-lg shadow-xl p-8">
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h1>
        <p class="text-sm text-gray-500">Sign in to your account to continue</p>
      </div>

      <form wire:submit="login" class="space-y-5">
        <div>
          <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
          <input wire:model="email" type="email" id="email"
            class="mt-2 w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] transition @error('email') border-red-500 @enderror"
            placeholder="you@example.com" />
          @error('email')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div>
          <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
          <input wire:model="password" type="password" id="password"
            class="mt-2 w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] transition @error('password') border-red-500 @enderror"
            placeholder="••••••••" />
          @error('password')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

        <div class="flex items-center">
          <input wire:model="remember" type="checkbox" id="remember" class="w-4 h-4 rounded border-gray-300" />
          <label for="remember" class="ml-2 text-sm text-gray-600">Remember me</label>
        </div>

        <button type="submit"
          class="w-full bg-gradient-to-r from-[#667eea] to-[#764ba2] text-white font-semibold py-3 rounded-lg hover:shadow-lg transition transform hover:-translate-y-0.5">
          Sign In
        </button>
      </form>

      <div class="text-center mt-6 pt-6 border-t border-gray-200">
        <p class="text-sm text-gray-600">
          Don't have an account?
          <a href="{{ route('register') }}" class="text-[#667eea] font-semibold hover:underline">Create one</a>
        </p>
      </div>
    </div>
  </div>
</div>