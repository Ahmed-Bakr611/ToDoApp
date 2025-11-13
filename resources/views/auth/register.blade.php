@extends('layouts.app')

@section('title', 'Create Account')

@section('content')
  <style>
    .auth-container {
      max-width: 480px;
      margin: 0 auto;
    }

    .auth-header {
      text-align: center;
      margin-bottom: 32px;
    }

    .auth-header h3 {
      font-size: 28px;
      font-weight: 700;
      color: #1f2937;
      margin-bottom: 8px;
    }

    .auth-header p {
      font-size: 14px;
      color: #6b7280;
    }

    .auth-form {
      display: flex;
      flex-direction: column;
      gap: 20px;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 8px;
    }

    .form-group label {
      font-size: 14px;
      font-weight: 500;
      color: #374151;
    }

    .form-input {
      padding: 12px 16px;
      border: 2px solid #e5e7eb;
      border-radius: 8px;
      font-size: 14px;
      transition: all 0.2s;
      background-color: white;
    }

    .form-input:focus {
      outline: none;
      border-color: #667eea;
      box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
    }

    .form-input::placeholder {
      color: #9ca3af;
    }

    .error-message {
      color: #ef4444;
      font-size: 13px;
      margin-top: 4px;
    }

    .submit-btn {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px;
      font-size: 16px;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.2s;
      margin-top: 8px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 16px rgba(102, 126, 234, 0.3);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    .auth-footer {
      text-align: center;
      margin-top: 24px;
      padding-top: 24px;
      border-top: 1px solid #e5e7eb;
    }

    .auth-footer p {
      font-size: 14px;
      color: #6b7280;
    }

    .auth-footer a {
      color: #667eea;
      text-decoration: none;
      font-weight: 500;
    }

    .auth-footer a:hover {
      text-decoration: underline;
    }

    .alert {
      padding: 12px 16px;
      border-radius: 8px;
      margin-bottom: 20px;
      font-size: 14px;
    }

    .alert-error {
      background-color: #fee;
      color: #dc2626;
      border: 1px solid #fecaca;
    }
  </style>

  <div class="auth-container">
    <div class="auth-header">
      <h3>Create Your Account</h3>
      <p>Join us to start managing your tasks efficiently</p>
    </div>

    @if ($errors->any())
      <div class="alert alert-error">
        <ul style="margin-left: 20px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form method="POST" action="{{ route('register') }}" class="auth-form">
      @csrf

      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="form-input" placeholder="John Doe" value="{{ old('name') }}"
          required autofocus>
        @error('name')
          <span class="error-message">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-input" placeholder="john@example.com"
          value="{{ old('email') }}" required>
        @error('email')
          <span class="error-message">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" class="form-input" placeholder="Enter a strong password"
          required>
        @error('password')
          <span class="error-message">{{ $message }}</span>
        @enderror
      </div>

      <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input"
          placeholder="Re-enter your password" required>
      </div>

      <button type="submit" class="submit-btn">Create Account</button>
    </form>

    <div class="auth-footer">
      <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
    </div>
  </div>
@endsection