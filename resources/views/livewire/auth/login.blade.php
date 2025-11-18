<div class="max-w-md mx-auto">
  <div class="text-center mb-8">
    <h3 class="text-3xl font-bold text-gray-900 mb-2">Welcome Back</h3>
    <p class="text-gray-600">Sign in to continue managing your tasks</p>
  </div>

  @if (session('status'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
      <p class="text-sm">{{ session('status') }}</p>
    </div>
  @endif

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
      <p class="font-semibold text-sm mb-2">Whoops! There were some problems with your input.</p>
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form wire:submit="login" class="space-y-5">
    <div>
      <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
        Email Address
      </label>
      <input type="email" id="email" wire:model="email"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-4 focus:ring-[#667eea]/10 transition-all @error('email') border-red-500 @enderror"
        placeholder="john@example.com" required autofocus>
      @error('email')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password" class="block text-sm font-semibold text-gray-800 mb-2">
        Password
      </label>
      <input type="password" id="password" wire:model="password"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-4 focus:ring-[#667eea]/10 transition-all @error('password') border-red-500 @enderror"
        placeholder="Enter your password" required>
      @error('password')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div class="flex items-center justify-between">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" id="remember" wire:model="remember"
          class="w-4 h-4 text-[#667eea] border-gray-300 rounded focus:ring-[#667eea] cursor-pointer">
        <span class="text-sm text-gray-600">Remember me</span>
      </label>

      @if (Route::has('password.request'))
        <a href="{{ route('password.request') }}"
          class="text-sm text-[#667eea] hover:text-[#5568d3] font-semibold hover:underline">
          Forgot password?
        </a>
      @endif
    </div>

    <button type="submit"
      class="w-full bg-gradient-to-r from-[#667eea] to-[#764ba2] hover:from-[#5568d3] hover:to-[#6a3f8f] text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none mt-2"
      wire:loading.attr="disabled">
      <span wire:loading.remove>Sign In</span>
      <span wire:loading>Signing In...</span>
    </button>
  </form>

  <div class="text-center mt-6 pt-6 border-t border-gray-200">
    <p class="text-gray-600">
      Don't have an account?
      <a href="{{ route('register') }}" class="text-[#667eea] hover:text-[#5568d3] font-semibold hover:underline">
        Create one now
      </a>
    </p>
  </div>
</div>