@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/event_show.css') }}">
@endsection

@section('page_title')
Events
@endsection

@section('content')
  <div class="event-detail">
    <div class="event-card">
      <div class="media-wrap">
        @if(!empty($event->image_url))
          <img class="media" src="{{ $event->image_url }}" alt="{{ $event->title }}">
        @else
          <div class="media placeholder"></div>
        @endif
        <span class="mode-badge {{ $event->is_online ? 'online' : 'inperson' }}">{{ $event->is_online ? 'online' : 'in-person' }}</span>
      </div>
      <div class="content">
        <div class="badges">
          @if(!empty($event->category))
            <span class="badge">{{ $event->category }}</span>
          @endif
          <span class="badge mode {{ $event->is_online ? 'online' : 'inperson' }}">{{ $event->is_online ? 'online' : 'in-person' }}</span>
        </div>
        <h2 class="title">{{ $event->title }}</h2>
        @if(!empty($event->description))
          <p class="desc">{{ $event->description }}</p>
        @endif
        <div class="meta-row">
          <div class="meta">
            <div class="label">Start Date</div>
            <div class="value">{{ $event->start_date }}</div>
          </div>
          <div class="meta">
            <div class="label">Start Time</div>
            <div class="value">{{ $event->start_time }}</div>
          </div>
        </div>
        @if(!$event->is_online)
        <div class="meta-row">
          <div class="meta">
            <div class="label">Venue</div>
            <div class="value">{{ $event->venue }}</div>
          </div>
          @if($event->country)
          <div class="meta">
            <div class="label">Location</div>
            <div class="value">{{ $event->country }}  @if($event->state) {{ $event->state }}, @endif{{ $event->country }}</div>
          </div>
          @endif
        </div>
        @endif
        <div class="actions">
          <button type="button" class="btn btn-primary" id="detailRegister" {{ $isSubscribed ? 'disabled' : '' }}>{{ $isSubscribed ? 'Registered' : 'Register' }}</button>
          @if(!empty($event->meeting_link))
            <a href="{{ $event->meeting_link }}" class="btn btn-outline">Visit Link</a>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="modal-overlay" id="subscribeModal">
    <div class="modal-card">
      <div class="modal-header">
        <div class="modal-title">Confirm Registration</div>
        <button class="modal-close" id="subscribeClose">×</button>
      </div>
      <div class="modal-body">
        <p>Do you want to register for this event?</p>
      </div>
      <div class="modal-actions">
        <button class="btn btn-outline" id="subscribeCancel">Cancel</button>
        <button class="btn btn-primary" id="subscribeConfirm">Confirm</button>
      </div>
    </div>
  </div>
  <div id="toastContainer" class="toast-container"></div>
  <script>
    (function(){
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const modal = document.getElementById('subscribeModal');
      const btnCancel = document.getElementById('subscribeCancel');
      const btnConfirm = document.getElementById('subscribeConfirm');
      const btnClose = document.getElementById('subscribeClose');
      const regBtn = document.getElementById('detailRegister');
      function open(){ modal.style.display='flex'; }
      function close(){ modal.style.display='none'; }
      if (regBtn) regBtn.addEventListener('click', open);
      btnCancel.addEventListener('click', close);
      btnClose.addEventListener('click', close);
      modal.addEventListener('click', (e)=>{ if(e.target===modal) close(); });
      btnConfirm.addEventListener('click', async ()=>{
        try {
          const res = await fetch('{{ route('events.subscribe', $event) }}', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf }, body: JSON.stringify({}) });
          const data = await res.json();
          if (data && data.ok) {
            if (regBtn) { regBtn.textContent = 'Registered'; regBtn.disabled = true; }
            showToast('Registered successfully');
          }
        } catch(err) {}
        close();
      });

      function showToast(msg, type='success'){
        const box = document.createElement('div');
        box.className = 'toast ' + (type==='error'?'toast-error':'toast-success');
        const iconSvg = type==='error'
          ? '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12" y2="16"/></svg>'
          : '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>';
        box.innerHTML = '<div class="toast-icon">'+ iconSvg +'</div>'+
                        '<div class="toast-content">'+
                          '<div class="toast-title">'+ (type==='error'?'Error':'Success') +'</div>'+
                          '<div class="toast-message">'+ (msg||'') +'</div>'+
                        '</div>'+
                        '<div class="toast-progress"></div>';
        const container = document.getElementById('toastContainer') || (function(){ const c = document.createElement('div'); c.id='toastContainer'; c.className='toast-container'; document.body.appendChild(c); return c; })();
        container.appendChild(box);
        const prog = box.querySelector('.toast-progress');
        let start = null;
        const duration = 2500;
        function step(ts){
          if(!start) start = ts;
          const p = Math.min((ts-start)/duration, 1);
          prog.style.width = (100*(1-p))+'%';
          if(p<1) requestAnimationFrame(step);
        }
        requestAnimationFrame(step);
        setTimeout(()=>{ box.classList.add('hide'); setTimeout(()=> box.remove(), 300); }, duration);
      }
    })();
  </script>
@endsection
