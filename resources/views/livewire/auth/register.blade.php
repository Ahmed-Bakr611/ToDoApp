<div class="max-w-md mx-auto">
  <div class="text-center mb-8">
    <h3 class="text-3xl font-bold text-gray-900 mb-2">Create Your Account</h3>
    <p class="text-gray-600">Join us to start managing your tasks efficiently</p>
  </div>

  @if ($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
      <ul class="list-disc list-inside space-y-1">
        @foreach ($errors->all() as $error)
          <li class="text-sm">{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form wire:submit="register" class="space-y-5">
    <div>
      <label for="name" class="block text-sm font-semibold text-gray-800 mb-2">
        Full Name
      </label>
      <input type="text" id="name" wire:model="name"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-4 focus:ring-[#667eea]/10 transition-all @error('name') border-red-500 @enderror"
        placeholder="John Doe" required autofocus>
      @error('name')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="email" class="block text-sm font-semibold text-gray-800 mb-2">
        Email Address
      </label>
      <input type="email" id="email" wire:model="email"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-4 focus:ring-[#667eea]/10 transition-all @error('email') border-red-500 @enderror"
        placeholder="john@example.com" required>
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
        placeholder="Enter a strong password" required>
      @error('password')
        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="password_confirmation" class="block text-sm font-semibold text-gray-800 mb-2">
        Confirm Password
      </label>
      <input type="password" id="password_confirmation" wire:model="password_confirmation"
        class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg focus:outline-none focus:border-[#667eea] focus:ring-4 focus:ring-[#667eea]/10 transition-all"
        placeholder="Re-enter your password" required>
    </div>

    <button type="submit"
      class="w-full bg-gradient-to-r from-[#667eea] to-[#764ba2] hover:from-[#5568d3] hover:to-[#6a3f8f] text-white font-semibold py-3 px-6 rounded-lg transition-all duration-200 hover:-translate-y-0.5 hover:shadow-lg disabled:opacity-60 disabled:cursor-not-allowed disabled:transform-none mt-2"
      wire:loading.attr="disabled">
      <span wire:loading.remove>Create Account</span>
      <span wire:loading>Creating Account...</span>
    </button>
  </form>

  <div class="text-center mt-6 pt-6 border-t border-gray-200">
    <p class="text-gray-600">
      Already have an account?
      <a href="{{ route('login') }}" class="text-[#667eea] hover:text-[#5568d3] font-semibold hover:underline">
        Sign in here
      </a>
    </p>
  </div>
</div>