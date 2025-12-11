@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/settings.css') }}">
@endsection

@section('page_title')
Settings
@endsection

@section('content')
  <div class="settings-page">
    <div class="frame">
      <img class="grid-layers" src="{{ url('assets/images/grid-layers-v2.png') }}" alt="" />
      <div class="div">
        <div class="text-wrapper" id="heroName">Good Morning, {{ $user->full_name }}</div>
        <p class="p">Navigate through your user profile</p>
      </div>
      <div class="ellipse">
        <svg viewBox="0 0 100 100" class="avatar-svg">
          <text x="50" y="50" text-anchor="middle" dy=".35em" fill="white" font-size="40" font-weight="600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</text>
        </svg>
      </div>
    </div>

    <div class="settings-wrap">
      <aside class="settings-tabs container" id="settingsTabs">
        <div class="menu">
          <button class="tab section active" data-panel="account"><div class="text-wrapper">Account Information</div></button>
          <button class="tab div-wrapper" data-panel="profile"><div class="div">Professional Profile</div></button>
          <button class="tab div-wrapper" data-panel="privacy"><div class="privacy-display">Privacy &amp; Display</div></button>
          <button class="tab div-wrapper" data-panel="notifications"><div class="text-wrapper-2">Notifications</div></button>
          <button class="tab div-wrapper" data-panel="communications"><div class="text-wrapper-3">Communications</div></button>
          <button class="tab div-wrapper" data-panel="security"><div class="text-wrapper-4">Security</div></button>
          <button class="tab div-wrapper" data-panel="billing"><div class="text-wrapper-5">Billings</div></button>
          <button class="tab div-wrapper danger" data-panel="delete"><div class="text-wrapper-6">Delete Account</div></button>
        </div>
      </aside>

      <section class="settings-content">
        <div class="panel" id="panel-account">
          <div class="content-row">
            <form method="POST" action="{{ route('settings.update') }}" class="form-card simple-form" id="formAccount">
              @csrf
              <div class="form-row">
                <label>FULL NAME</label>
                <input type="text" name="full_name" value="{{ old('full_name', $user->full_name) }}" />
              </div>
              <div class="form-row">
                <label>EMAIL ADDRESS</label>
                <input type="email" value="{{ $user->email }}" disabled />
              </div>
              <div class="form-row">
                <label>PHONE</label>
                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" />
              </div>
              <div class="actions">
                <button type="submit" class="btn btn-primary">Save changes</button>
              </div>
            </form>
            <aside class="right-logo">
              <div class="big-avatar">
                @php $imgUrl = $user->image ?: null; @endphp
                @if($imgUrl)
                  <img src="{{ $imgUrl }}" alt="Profile" class="avatar-img" />
                @else
                  <svg viewBox="0 0 100 100" class="avatar-svg">
                    <text x="50" y="50" text-anchor="middle" dy=".35em" fill="white" font-size="40" font-weight="600">{{ strtoupper(substr($user->full_name, 0, 1)) }}</text>
                  </svg>
                @endif
              </div>
              <input type="file" id="imageInput" accept="image/*" style="display:none;" />
              <button type="button" class="btn btn-outline small" id="btnChangeImage">Change</button>
            </aside>
          </div>

          <form method="POST" action="{{ route('settings.password') }}" class="form-card simple-form" id="formPassword">
            @csrf
            <div class="form-row">
              <label>Old Password</label>
              <input type="password" name="current_password" placeholder="Old Password" />
            </div>
            <div class="form-row">
              <label>New Password</label>
              <input type="password" name="password" placeholder="New Password" />
            </div>
            <div class="form-row">
              <label>Confirm Password</label>
              <input type="password" name="password_confirmation" placeholder="Confirm Password" />
            </div>
            <div class="actions">
              <button type="submit" class="btn btn-primary">Save changes</button>
            </div>
          </form>
        </div>

        <div class="panel" id="panel-notifications" style="display:none;">
          <div class="form-card">
            <h3 class="panel-title">Actionable Notifications.</h3>
            <ul class="note-list">
              <li>
                <div class="note-title">Network</div>
                <div class="note-desc">Receive notifications when you get new connection requests or when someone accepts your invite</div>
              </li>
              <li>
                <div class="note-title">Messages Activity</div>
                <div class="note-desc">Get instant alerts for new direct messages, message replies, or group chat updates from your professional network.</div>
              </li>
              <li>
                <div class="note-title">Events</div>
                <div class="note-desc">Information about upcoming medical events, conferences, and webinars. Receive reminders for sessions you’ve registered for.</div>
              </li>
            </ul>
            <div class="divider"></div>
            <div class="form-row">
              <label>System Updates</label>
              <div class="checkbox-line">
                <input type="checkbox" checked />
                <span>Get notified of maintenance, new features, policy changes, etc.</span>
              </div>
            </div>
            <div class="form-row">
              <label>Notification Frequency</label>
              <div class="checks">
                <label><input type="checkbox" /> Instant</label>
                <label><input type="checkbox" /> Daily</label>
                <label><input type="checkbox" /> Weekly</label>
              </div>
            </div>
            <div class="actions">
              <button type="button" class="btn btn-primary">Save changes</button>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>

  <script>
    (function(){
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const tabs = Array.from(document.querySelectorAll('#settingsTabs .tab'));
      const panels = {
        account: document.getElementById('panel-account'),
        notifications: document.getElementById('panel-notifications')
      };
      function show(panel){
        Object.values(panels).forEach(p=>{ if(p) p.style.display='none'; });
        if (panels[panel]) panels[panel].style.display='block';
      }
      tabs.forEach(tab=>{
        tab.addEventListener('click', ()=>{
          tabs.forEach(t=>t.classList.remove('active'));
          tab.classList.add('active');
          show(tab.dataset.panel);
        });
      });
      show('account');

      function showToast(text, ok=true){
        let wrap = document.querySelector('.toast-container');
        if (!wrap){ wrap = document.createElement('div'); wrap.className='toast-container'; document.body.appendChild(wrap); }
        const el = document.createElement('div');
        el.className = 'toast '+(ok ? 'toast-success' : 'toast-error');
        el.textContent = text;
        wrap.appendChild(el);
        setTimeout(()=>{ el.classList.add('hide'); setTimeout(()=>{ el.remove(); }, 300); }, 2500);
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
            if (data.user && data.user.full_name) heroName.textContent = 'Good Morning, '+data.user.full_name;
            showToast('Profile updated');
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
            const imgEl = document.querySelector('.right-logo .avatar-img');
            if (imgEl) { imgEl.src = data.image; }
            else {
              const holder = document.querySelector('.right-logo .big-avatar');
              holder.innerHTML = '<img class="avatar-img" src="'+data.image+'" alt="Profile">';
            }
            showToast('Image updated');
          } else {
            const msg = (data && (data.message || (data.errors && Object.values(data.errors)[0][0]))) || 'Update failed';
            showToast(msg, false);
          }
        } catch(err) { showToast('Network error', false); }
      });
    })();
  </script>
@endsection
