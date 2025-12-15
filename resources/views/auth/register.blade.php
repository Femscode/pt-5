<x-guest-layout>
    <h1 style="font-size:2.23em;font-weight:600;color:black;font-family:'DM sans', sans-serif;" class="login-heading">Join Our Community</h1>
    <p class="text-gray-500 mb-4">Create your professional healthcare account</p>
    <form method="POST" action="{{ route('register') }}" class="login-form" id="registerForm">
        @csrf

        <div id="reg-step-1">
            <div>
                <x-input-label for="full_name" :value="__('FULL NAME')" />
                <x-text-input id="full_name" class="block mt-1 w-full" type="text" name="full_name" :value="old('full_name')" required autofocus autocomplete="name" placeholder="Full name" />
                <x-input-error :messages="$errors->get('full_name')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="email" :value="__('EMAIL ADDRESS')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="someone@emailaddress.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password" :value="__('PASSWORD')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('CONFIRM PASSWORD')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                <div id="passMatchHint" style="font-size:12px;margin-top:6px;"></div>
            </div>
            <div class="mt-6">
                <button type="button" id="reg-next-1" class="login-submit w-full justify-center">Next</button>
            </div>
        </div>

        <div id="reg-step-2" style="display:none">
            <div>
                <x-input-label for="category" :value="__('CATEGORY')" />
                <select id="category" name="category" class="block mt-1 w-full">
                    <option value="">Select a category</option>
                    <option>Clinical & Medical Practice</option>
                    <option>Nursing & Midwifery</option>
                    <option>Therapeutic & Rehabilitation Professionals</option>
                    <option>Allied Health Professionals (AHPs)</option>
                    <option>Behavioural, Mental Health & Social Care Professionals</option>
                    <option>Public Health & Community Services</option>
                    <option>Dental Health Professionals</option>
                    <option>Research, Academia and Education</option>
                    <option>Pharmaceuticals and Life Sciences</option>
                    <option>Healthcare Management & Administration</option>
                    <option>Technology, Engineering & Data</option>
                    <option>Manufacturing, Supply & Logistics</option>
                    <option>Corporate, Legal & Finance (Health Sector)</option>
                    <option>Other / Support Roles</option>
                </select>
            </div>
            <div class="mt-4">
                <x-input-label for="institution" :value="__('INSTITUTION/ORGANIZATION')" />
                <x-text-input id="institution" class="block mt-1 w-full" type="text" name="institution" placeholder="Your organization" />
            </div>
            <div class="mt-6">
                <button type="button" id="reg-next-2" class="login-submit w-full justify-center">Next</button>
            </div>
        </div>

        <div id="reg-step-3" style="display:none">
            <div>
                <x-input-label for="role" :value="__('PROFESSIONAL ROLE')" />
                <select id="role" name="role" class="block mt-1 w-full">
                    <option value="">Select your role</option>
                    <option>Doctor</option>
                    <option>Nurse</option>
                    <option>Pharmacist</option>
                    <option>Lab Scientist</option>
                    <option>Radiographer</option>
                    <option>Physiotherapist</option>
                    <option>Administrator</option>
                </select>
            </div>
            <div class="mt-4">
                <x-input-label for="specialisation" :value="__('SPECIALIZATION')" />
                <select id="specialisation" name="specialisation" class="block mt-1 w-full">
                    <option value="">Select your specialization</option>
                    <option>Cardiology</option>
                    <option>Neurology</option>
                    <option>Paediatrics</option>
                    <option>Surgery</option>
                    <option>Emergency Medicine</option>
                </select>
            </div>
            <div class="mt-4">
                <x-input-label for="license_number" :value="__('LICENSE NUMBER')" />
                <x-text-input id="license_number" class="block mt-1 w-full" type="text" name="license_number" placeholder="License number" />
            </div>
            <div class="mt-6">
                <x-primary-button class="login-submit w-full justify-center">Create Account</x-primary-button>
            </div>
        </div>
    </form>
    <script>
      (function(){
        const s1 = document.getElementById('reg-step-1');
        const s2 = document.getElementById('reg-step-2');
        const s3 = document.getElementById('reg-step-3');
        const n1 = document.getElementById('reg-next-1');
        const n2 = document.getElementById('reg-next-2');
        const p1 = document.getElementById('password');
        const p2 = document.getElementById('password_confirmation');
        const hint = document.getElementById('passMatchHint');
        function show(step){
          s1.style.display = step===1 ? 'block' : 'none';
          s2.style.display = step===2 ? 'block' : 'none';
          s3.style.display = step===3 ? 'block' : 'none';
        }
        show(1);
        n1.addEventListener('click', function(){ show(2); });
        n2.addEventListener('click', function(){ show(3); });
        function checkMatch(){
          if(!p1 || !p2 || !hint) return;
          const a = p1.value;
          const b = p2.value;
          if(!a && !b){ hint.textContent=''; return; }
          if(a === b){
            hint.textContent = 'Passwords match';
            hint.style.color = '#0f766e';
          } else {
            hint.textContent = 'Passwords do not match';
            hint.style.color = '#b91c1c';
          }
        }
        if(p1 && p2){
          p1.addEventListener('input', checkMatch);
          p2.addEventListener('input', checkMatch);
        }
        const form = document.getElementById('registerForm');
        if(form){
          form.addEventListener('submit', function(){
            const submitBtn = form.querySelector('button[type="submit"], .login-submit');
            if(submitBtn){
              submitBtn.disabled = true;
              submitBtn.setAttribute('aria-busy', 'true');
              submitBtn.textContent = 'Creating Account...';
            }
          });
        }
      })();
    </script>
</x-guest-layout>
