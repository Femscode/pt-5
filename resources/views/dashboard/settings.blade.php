@extends('dashboard.master')
@section('header')
    <script src="https://cdn.tailwindcss.com"></script>
@endsection

@section('page_title')
Settings
@endsection

@section('content')
  <div class="p-6">
    <div class="relative w-full max-w-5xl h-44 bg-gradient-to-r from-blue-700 to-indigo-600 border border-slate-200 rounded-xl shadow-md overflow-hidden mb-6">
      <img class="absolute -top-40 -left-40 w-[1440px] h-[1024px] opacity-20 pointer-events-none select-none" src="{{ url('assets/images/grid-layers-v2.png') }}" alt="" />
      <div class="absolute left-6 top-1/2 -translate-y-1/2 w-26 h-26 rounded-full bg-white/20 flex items-center justify-center">
        <svg viewBox="0 0 100 100" class="w-20 h-20">
          <text x="50" y="50" text-anchor="middle" dy=".35em" fill="white" font-size="40" font-weight="600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</text>
        </svg>
      </div>
      <div class="absolute left-40 top-1/2 -translate-y-1/2 flex flex-col gap-2">
        <div id="heroName" class="text-white text-2xl font-bold">Hi, {{ $user->full_name }}</div>
        <p class="text-white/90 text-base">Navigate through your user profile</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[260px,1fr] gap-4 items-start">
      <aside id="settingsTabs" class="flex flex-col gap-2 order-1 sticky top-2">
        <div class="bg-white border border-slate-200 rounded-md overflow-hidden shadow-sm">
          <button class="tab w-full text-left flex items-center justify-between px-4 h-11 bg-blue-50 text-blue-600 font-semibold" data-panel="account"><span>Account Information</span></button>
          <button class="tab w-full text-left px-4 h-11 text-slate-700" data-panel="profile"><span>Professional Profile</span></button>
          <button class="tab w-full text-left px-4 h-11 text-slate-700" data-panel="notifications"><span>Notifications</span></button>
          <button class="tab w-full text-left px-4 h-11 text-red-600" data-panel="delete"><span>Delete Account</span></button>
        </div>
      </aside>

      <section id="settingsContent" class="flex flex-col gap-6 lg:gap-10 order-2 lg:order-2">
        <div id="panel-account">
          <form method="POST" action="{{ route('settings.update') }}" id="formAccount" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-6">
            @csrf
            <div class="grid grid-cols-1 lg:grid-cols-[1fr,240px] gap-6 items-start">
              <div class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
                  <label class="text-xs text-slate-600">FULL NAME</label>
                  <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
                  <label class="text-xs text-slate-600">EMAIL ADDRESS</label>
                  <input type="email" value="{{ $user->email }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-500" />
                </div>
                <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
                  <label class="text-xs text-slate-600">PHONE</label>
                  <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
                <div class="flex justify-start gap-3">
                  <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 text-white px-4 py-2 shadow-sm hover:bg-blue-700">Save changes</button>
                </div>
              </div>
              <div class="flex flex-col items-center gap-3">
                <div id="accountAvatar" class="w-24 h-24 rounded-full overflow-hidden bg-blue-800 shadow-sm flex items-center justify-center">
                  @php $imgUrl = $user->image ?: null; @endphp
                  @if($imgUrl)
                    <img id="accountAvatarImg" src="{{ $imgUrl }}" alt="Profile" class="w-full h-full object-cover" />
                  @else
                    <svg viewBox="0 0 100 100" class="w-16 h-16">
                      <text x="50" y="50" text-anchor="middle" dy=".35em" fill="white" font-size="40" font-weight="600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</text>
                    </svg>
                  @endif
                </div>
                <input type="file" id="imageInput" accept="image/*" class="hidden" />
                <button type="button" class="px-3 py-2 rounded-full border border-blue-600 text-blue-600 hover:bg-blue-50" id="btnChangeImage">Change</button>
              </div>
            </div>
          </form>

          <form method="POST" action="{{ route('settings.password') }}" id="formPassword" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4 lg:mt-2">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">Old Password</label>
              <input type="password" name="current_password" placeholder="Old Password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">New Password</label>
              <input type="password" name="password" placeholder="New Password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">Confirm Password</label>
              <input type="password" name="password_confirmation" placeholder="Confirm Password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>
            <div class="flex justify-start gap-3">
              <button type="submit" class="inline-flex items-center rounded-lg bg-blue-600 text-white px-4 py-2 shadow-sm hover:bg-blue-700">Save changes</button>
            </div>
          </form>
        </div>

        <div id="panel-profile" style="display:none;">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">SPECIALIZATION</label>
              <input type="text" value="{{ $user->specialisation ?? '—' }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">CATEGORY</label>
              <input type="text" value="{{ $user->category ?? '—' }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">INSTITUTION</label>
              <input type="text" value="{{ $user->institution ?? '—' }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">LICENSE NUMBER</label>
              <input type="text" value="{{ $user->license_number ?? '—' }}" disabled class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-slate-700" />
            </div>
          </div>
        </div>

        <div id="panel-notifications" style="display:none;">
          <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            <h3 class="text-lg font-semibold">Actionable Notifications.</h3>
            <ul class="grid gap-3">
              <li>
                <div class="font-semibold">Network</div>
                <div class="text-sm text-slate-700">Receive notifications when you get new connection requests or when someone accepts your invite</div>
              </li>
              <li>
                <div class="font-semibold">Messages Activity</div>
                <div class="text-sm text-slate-700">Get instant alerts for new direct messages, message replies, or group chat updates from your professional network.</div>
              </li>
              <li>
                <div class="font-semibold">Events</div>
                <div class="text-sm text-slate-700">Information about upcoming medical events, conferences, and webinars. Receive reminders for sessions you’ve registered for.</div>
              </li>
            </ul>
            <div class="h-px bg-slate-200"></div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">System Updates</label>
              <div class="flex items-center gap-2">
                <input type="checkbox" checked class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" />
                <span class="text-sm">Get notified of maintenance, new features, policy changes, etc.</span>
              </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">Notification Frequency</label>
              <div class="flex gap-4 text-sm">
                <label class="flex items-center gap-2"><input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" /> Instant</label>
                <label class="flex items-center gap-2"><input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" /> Daily</label>
                <label class="flex items-center gap-2"><input type="checkbox" class="rounded border-slate-300 text-blue-600 focus:ring-blue-500" /> Weekly</label>
              </div>
            </div>
            <div class="flex justify-start gap-3">
              <button type="button" class="inline-flex items-center rounded-lg bg-blue-600 text-white px-4 py-2 shadow-sm hover:bg-blue-700">Save changes</button>
            </div>
          </div>
        </div>

        <div id="panel-delete" style="display:none;">
          <form method="POST" action="{{ route('settings.delete') }}" id="formDelete" class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm space-y-4">
            @csrf
            <div class="flex items-start gap-3">
              <div class="w-10 h-10 rounded-full bg-red-100 text-red-600 flex items-center justify-center">
                <svg viewBox="0 0 24 24" class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 9v4"/><path d="M12 17h.01"/><path d="M21 21L3 3"/><path d="M12 2a10 10 0 1 0 10 10"/></svg>
              </div>
              <div class="flex-1">
                <div class="text-lg font-semibold text-red-600">Delete Account</div>
                <p class="text-sm text-slate-700">You are about to perform a critical action. Deleting your account will permanently remove all your data, including connections, messages, products, and event registrations.</p>
              </div>
            </div>
            <div class="h-px bg-slate-200"></div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-center">
              <label class="text-xs text-slate-600">Confirm with Password</label>
              <input type="password" name="password" placeholder="Enter your password" class="w-full rounded-lg border border-slate-200 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500" />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-[220px,1fr] gap-4 items-start">
              <label class="text-xs text-slate-600">Agreement</label>
              <label class="flex items-start gap-2 text-sm text-slate-700">
                <input type="checkbox" name="agree" id="agreeDelete" class="rounded border-slate-300 text-red-600 focus:ring-red-500" />
                <span>I understand that deleting my account will remove all information related to me and cannot be undone.</span>
              </label>
            </div>
            <div class="flex justify-start gap-3">
              <button type="submit" id="btnDeleteAccount" class="inline-flex items-center rounded-lg bg-red-600 text-white px-4 py-2 shadow-sm hover:bg-red-700 disabled:opacity-60">Delete Account</button>
            </div>
          </form>
        </div>
      </section>
    </div>
  </div>

  <script>
    (function(){
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const tabs = Array.from(document.querySelectorAll('#settingsTabs .tab'));
      const panels = { account: document.getElementById('panel-account'), profile: document.getElementById('panel-profile'), notifications: document.getElementById('panel-notifications'), delete: document.getElementById('panel-delete') };
      function setTabClasses(el, active){
        el.classList.toggle('bg-blue-50', active);
        el.classList.toggle('text-blue-600', active);
        el.classList.toggle('font-semibold', active);
        el.classList.toggle('text-slate-700', !active);
      }
      function show(panel){ Object.values(panels).forEach(p=>{ if(p) p.style.display='none'; }); if (panels[panel]) panels[panel].style.display='block'; }
      tabs.forEach(tab=>{ tab.addEventListener('click', ()=>{ tabs.forEach(t=>setTabClasses(t, false)); setTabClasses(tab, true); const panel = tab.dataset.panel; show(panel); }); });
      setTabClasses(tabs[0], true); show('account');

      function showToast(text, ok=true){
        let wrap = document.getElementById('toastContainer');
        if (!wrap){ wrap = document.createElement('div'); wrap.id='toastContainer'; wrap.className='fixed top-5 right-5 z-[1000] space-y-3 pointer-events-none'; document.body.appendChild(wrap); }
        const el = document.createElement('div');
        el.className = 'relative pointer-events-auto flex items-center gap-3 bg-white text-slate-900 px-4 py-3 rounded-xl shadow-lg border-l-4 '+(ok ? 'border-blue-600' : 'border-red-500');
        const icon = ok
          ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>'
          : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12" y2="16"/></svg>';
        el.innerHTML = '<div class="w-7 h-7 rounded-full flex items-center justify-center text-white '+(ok ? 'bg-blue-600' : 'bg-red-500')+'">'+ icon +'</div>'+
                       '<div class="flex flex-col">'+
                         '<div class="font-semibold text-sm">'+(ok?'Success':'Error')+'</div>'+
                         '<div class="text-sm">'+ text +'</div>'+
                       '</div>'+
                       '<div class="toast-progress absolute left-0 bottom-0 h-0.5 w-full '+(ok?'bg-blue-500/20':'bg-red-500/20')+'"></div>';
        wrap.appendChild(el);
        const prog = el.querySelector('.toast-progress');
        let start = null; const duration = 2500;
        function step(ts){ if(!start) start = ts; const p = Math.min((ts-start)/duration, 1); prog.style.width = (100*(1-p))+'%'; if(p<1) requestAnimationFrame(step); }
        requestAnimationFrame(step);
        setTimeout(()=>{ el.style.opacity='0'; el.style.transform='translateY(6px)'; setTimeout(()=>{ el.remove(); }, 300); }, duration);
      }

      const formAccount = document.getElementById('formAccount');
      const heroName = document.getElementById('heroName');
      formAccount.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const fd = new FormData(formAccount);
        try {
          const res = await fetch(formAccount.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
          const data = await res.json();
          if (res.ok && data && data.ok) {
            if (data.user && data.user.full_name) heroName.textContent = 'Hi, '+data.user.full_name;
            showToast('Profile Updated Successfully!');
          } else {
            const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || 'Update failed';
            showToast(msg, false);
          }
        } catch(err) { showToast('Network error', false); }
      });

      const formPassword = document.getElementById('formPassword');
      const imageInput = document.getElementById('imageInput');
      const btnChangeImage = document.getElementById('btnChangeImage');
      formPassword.addEventListener('submit', async (e)=>{
        e.preventDefault();
        const fd = new FormData(formPassword);
        try {
          const res = await fetch(formPassword.action, { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
          const data = await res.json();
          if (res.ok && data && data.ok) {
            showToast('Password updated');
            formPassword.reset();
          } else {
            const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || 'Update failed';
            showToast(msg, false);
          }
        } catch(err) { showToast('Network error', false); }
      });

      btnChangeImage.addEventListener('click', ()=> imageInput.click());
      imageInput.addEventListener('change', async ()=>{
        if (!imageInput.files || imageInput.files.length === 0) return;
        const fd = new FormData();
        fd.append('image', imageInput.files[0]);
        try {
          const res = await fetch('{{ route('settings.update') }}', { method: 'POST', headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: fd });
          const data = await res.json();
          if (res.ok && data && data.ok && data.image) {
            const imgEl = document.getElementById('accountAvatarImg');
            const holder = document.getElementById('accountAvatar');
            if (imgEl) { imgEl.src = data.image; }
            else { holder.innerHTML = '<img id="accountAvatarImg" class="w-full h-full object-cover" src="'+data.image+'" alt="Profile">'; }
            showToast('Image updated');
          } else {
            const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || 'Update failed';
            showToast(msg, false);
          }
        } catch(err) { showToast('Network error', false); }
      });

      const formDelete = document.getElementById('formDelete');
      const btnDelete = document.getElementById('btnDeleteAccount');
      if (formDelete) {
        formDelete.addEventListener('submit', async (e)=>{
          e.preventDefault();
          const pwd = formDelete.querySelector('input[name="password"]').value.trim();
          const agree = formDelete.querySelector('#agreeDelete').checked;
          if (!pwd) { showToast('Please enter your password', false); return; }
          if (!agree) { showToast('Please confirm the agreement checkbox', false); return; }
          btnDelete.disabled = true;
          try {
            const res = await fetch(formDelete.action, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' }, body: JSON.stringify({ password: pwd, agree: true }) });
            const data = await res.json();
            if (res.ok && data && data.ok) {
              showToast('Account deleted');
              setTimeout(()=>{ window.location.href = data.redirect || '{{ route('login') }}'; }, 800);
            } else {
              const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || 'Deletion failed';
              showToast(msg, false);
            }
          } catch(err) {
            showToast('Network error', false);
          } finally {
            btnDelete.disabled = false;
          }
        });
      }
    })();
  </script>
@endsection
