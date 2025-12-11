<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <h1 class="login-heading">Sign In</h1>

    <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('EMAIL ADDRESS')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" placeholder="someone@emailaddress.com" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('PASSWORD')" />
            <div class="password-wrapper" style="position:relative">
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                placeholder="Password"
                                required autocomplete="current-password" />
                <button type="button" id="togglePassword" aria-label="Show password" class="toggle-password" style="position:absolute;right:10px;top:50%;transform:translateY(-50%);background:#fff;border-radius:8px;width:36px;height:36px;display:inline-flex;align-items:center;justify-content:center;color:#667085">
                    {!! '<svg viewBox="0 0 24 24" width="18" height="18" fill="currentColor"><path d="M12 5c-7 0-10 7-10 7s3 7 10 7 10-7 10-7-3-7-10-7Zm0 11a4 4 0 1 1 0-8 4 4 0 0 1 0 8Z"/></svg>' !!}
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember + Forgot in one row -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <!-- Submit button row -->
        <div class="mt-4">
            <x-primary-button class="login-submit w-full justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    <script>
      (function(){
        const btn = document.getElementById('togglePassword');
        const input = document.getElementById('password');
        if(btn && input){
          btn.addEventListener('click', function(){
            const isPwd = input.getAttribute('type') === 'password';
            input.setAttribute('type', isPwd ? 'text' : 'password');
            btn.setAttribute('aria-label', isPwd ? 'Hide password' : 'Show password');
          });
        }
        const form = document.querySelector('form.login-form');
        if(form){
          form.addEventListener('submit', function(e){
            const submitBtn = form.querySelector('button[type="submit"], .login-submit');
            if(submitBtn){
              submitBtn.disabled = true;
              submitBtn.setAttribute('aria-busy', 'true');
              submitBtn.textContent = 'logging in...';
            }
          });
        }
      })();
    </script>
</x-guest-layout>
