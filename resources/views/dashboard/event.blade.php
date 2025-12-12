@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/event.css') }}">
@endsection

@section('page_title')
Events
@endsection

@section('content')
  <div class="events-page">
    <div class="event-tabs">
      <button class="tab active" data-tab="all">All Events</button>
      <button class="tab" data-tab="registered">Registered Events</button>
    </div>

    <div class="events-toolbar">
      <div class="events-search" id="eventsSearch">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <circle cx="9" cy="9" r="7"/>
          <path d="M14 14l5 5"/>
        </svg>
        <input id="searchInput" type="text" placeholder="Search events..." />
      </div>
      <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <path d="M3 6h18M6 12h12M10 18h4" />
        </svg>
        Filter
      </button>
      <div class="filter-popover" id="filterPopover">
        <div class="filters">
          <select id="filterDate" class="filter">
            <option value="">Date</option>
            <option value="">Any</option>
            @php
              $monthLabels = [];
              foreach (($events ?? []) as $ev) {
                if (!empty($ev->start_date)) {
                  $dt = new \DateTime($ev->start_date);
                  $code = $dt->format('Y-m');
                  $monthLabels[$code] = $dt->format('F Y');
                }
              }
            @endphp
            @foreach($monthLabels as $code => $label)
              <option value="{{ $code }}">{{ $label }}</option>
            @endforeach
          </select>
          <select id="filterLocation" class="filter">
            <option value="">Location</option>
            <option value="">Any</option>
            @php
              $locs = [];
              foreach (($events ?? []) as $ev) {
                $loc = trim(($ev->state ? $ev->state.', ' : '').($ev->country ?? ''));
                if ($loc) { $locs[$loc] = $loc; }
              }
            @endphp
            @foreach($locs as $loc)
              <option value="{{ $loc }}">{{ $loc }}</option>
            @endforeach
          </select>
          <select id="filterType" class="filter">
            <option value="">Event Type</option>
            <option value="">Any</option>
            @php
              $cats = [];
              foreach (($events ?? []) as $ev) {
                if (!empty($ev->category)) { $cats[$ev->category] = $ev->category; }
              }
            @endphp
            @foreach($cats as $c)
              <option value="{{ $c }}">{{ $c }}</option>
            @endforeach
          </select>
        </div>
        <div class="filter-actions">
          <button type="button" class="btn btn-outline btn-sm" id="filterClear">Clear</button>
        </div>
      </div>
    </div>

    <div class="events-grid" id="eventsGrid">
      @forelse($events as $e)
        @php
          $monthCode = !empty($e->start_date) ? (new \DateTime($e->start_date))->format('Y-m') : '';
          $locText = trim(($e->state ? $e->state.', ' : '').($e->country ?? ''));
        @endphp
        <div class="event-card" data-title="{{ strtolower($e->title ?? '') }}" data-category="{{ strtolower($e->category ?? '') }}" data-country="{{ strtolower($e->country ?? '') }}" data-state="{{ strtolower($e->state ?? '') }}" data-location="{{ strtolower($locText) }}" data-mode="{{ $e->is_online ? 'online' : 'in-person' }}" data-month="{{ $monthCode }}" data-subscribed="{{ in_array($e->id, ($subscribedIds ?? [])) ? '1' : '0' }}" data-id="{{ $e->id }}">
          <div class="card-media-wrap">
            @if(!empty($e->image_url))
              <img class="card-media" src="https://admin.mybridgeinternational.org/mbi-admin-files/public/{{ $e->image_url }}" alt="{{ $e->title }}">
            @else
              <div class="card-media placeholder"></div>
            @endif
            <span class="mode-badge {{ $e->is_online ? 'online' : 'inperson' }}">{{ $e->is_online ? 'online' : 'in-person' }}</span>
          </div>
          <div class="card-body">
            @if(!empty($e->category))
              <div class="badge">{{ $e->category }}</div>
            @endif
            <div class="title"><a href="{{ route('event.show', $e) }}">{{ $e->title }}</a></div>
            @if(!empty($e->description))
              <div class="desc">{{ \Illuminate\Support\Str::limit($e->description, 120) }}</div>
            @endif
            <ul class="details">
              <li class="detail">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                  <line x1="16" y1="2" x2="16" y2="6" />
                  <line x1="8" y1="2" x2="8" y2="6" />
                  <line x1="3" y1="10" x2="21" y2="10" />
                </svg>
                <span>{{ $e->start_date }}</span>
              </li>
              <li class="detail">
                <svg width="16" height="16" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon" class="h-3 w-3 text-gray-400 flex-shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"></path></svg>
                <span>@if($e->start_time)  {{ $e->start_time }}@endif</span>
              </li>
              @if(!$e->is_online)
              <li class="detail">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                  <path d="M21 10c0 6-9 12-9 12S3 16 3 10a9 9 0 1 1 18 0z" />
                  <circle cx="12" cy="10" r="3" />
                </svg>
                <span>{{ $e->venue ?? '' }}@if($e->state) {{ $e->state }}, @endif{{ $e->country }}</span>
              </li>
              @endif
            </ul>
          </div>
          <div class="card-actions">
            @php $isSub = in_array($e->id, ($subscribedIds ?? [])); @endphp
            <button type="button" class="btn btn-primary register-btn" data-event-id="{{ $e->id }}" {{ $isSub ? 'disabled' : '' }}>{{ $isSub ? 'Registered' : 'Register' }}</button>
          </div>
        </div>
      @empty
        <div class="empty-state">No events available</div>
      @endforelse
    </div>
  </div>
  <div class="modal-overlay" id="subscribeModal">
    <div class="modal-card">
      <div class="modal-header">
        <div class="modal-title">Confirm Registration</div>
        <button class="modal-close" id="subscribeClose">×</button>
      </div>
      <div class="modal-body">
        <p id="subscribeText">Do you want to register for this event?</p>
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
      const grid = document.getElementById('eventsGrid');
      const cards = Array.from(grid.querySelectorAll('.event-card'));
      const searchInput = document.getElementById('searchInput');
      const filterDate = document.getElementById('filterDate');
      const filterLocation = document.getElementById('filterLocation');
      const filterType = document.getElementById('filterType');
      const tabs = Array.from(document.querySelectorAll('.event-tabs .tab'));
      const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
      const modal = document.getElementById('subscribeModal');
      const modalText = document.getElementById('subscribeText');
      const btnCancel = document.getElementById('subscribeCancel');
      const btnConfirm = document.getElementById('subscribeConfirm');
      const btnClose = document.getElementById('subscribeClose');
      let pendingEventId = null;
      

      function applyFilters(){
        const q = (searchInput.value || '').toLowerCase().trim();
        const month = filterDate.value || '';
        const loc = (filterLocation.value || '').toLowerCase();
        const type = (filterType.value || '').toLowerCase();
        cards.forEach(card => {
          const title = card.dataset.title || '';
          const category = card.dataset.category || '';
          const location = card.dataset.location || '';
          const monthCode = card.dataset.month || '';
          let ok = true;
          if (q) ok = ok && (title.includes(q) || category.includes(q) || location.includes(q));
          if (month) ok = ok && monthCode === month;
          if (loc) ok = ok && location === loc;
          if (type) ok = ok && category === type;
          card.style.display = ok ? '' : 'none';
        });
      }

      searchInput.addEventListener('input', applyFilters);
      searchInput.addEventListener('keydown', (e) => { if (e.key === 'Enter') applyFilters(); });
      filterDate.addEventListener('change', applyFilters);
      filterLocation.addEventListener('change', applyFilters);
      filterType.addEventListener('change', applyFilters);
      const filterBtn = document.querySelector('.filter-btn');
      const filterPopover = document.getElementById('filterPopover');
      const filterClear = document.getElementById('filterClear');
      filterBtn.addEventListener('click', ()=>{ filterPopover.classList.toggle('open'); });
      document.addEventListener('click', (e)=>{ const withinBtn = e.target.closest('.filter-btn'); if (!withinBtn && !filterPopover.contains(e.target)) { filterPopover.classList.remove('open'); } });
      if (filterClear) { filterClear.addEventListener('click', ()=>{ filterDate.value=''; filterLocation.value=''; filterType.value=''; applyFilters(); filterPopover.classList.remove('open'); }); }
      
      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          tabs.forEach(t=>t.classList.remove('active'));
          tab.classList.add('active');
          const showRegistered = tab.dataset.tab === 'registered';
          cards.forEach(card => {
            const isSub = card.dataset.subscribed === '1';
            card.style.display = showRegistered ? (isSub ? '' : 'none') : '';
          });
        });
      });

      function openModal(eventId, title){
        pendingEventId = eventId;
        modalText.textContent = 'Register for "'+(title||'this event')+'"?';
        modal.style.display = 'flex';
      }
      function closeModal(){ modal.style.display = 'none'; pendingEventId = null; }
      btnCancel.addEventListener('click', closeModal);
      btnClose.addEventListener('click', closeModal);
      modal.addEventListener('click', (e)=>{ if (e.target === modal) closeModal(); });

      grid.addEventListener('click', (e)=>{
        const btn = e.target.closest('.register-btn');
        if (!btn) return;
        const card = btn.closest('.event-card');
        const eventId = btn.dataset.eventId;
        const title = card.querySelector('.title')?.textContent || '';
        openModal(eventId, title);
      });

      btnConfirm.addEventListener('click', async ()=>{
        if (!pendingEventId) return;
        try {
          const res = await fetch('/events/'+pendingEventId+'/subscribe', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: JSON.stringify({})
          });
          const data = await res.json();
          if (data && data.ok) {
            const card = grid.querySelector('.event-card[data-id="'+pendingEventId+'"]');
            if (card) {
              card.dataset.subscribed = '1';
              const rbtn = card.querySelector('.register-btn');
              if (rbtn) { rbtn.textContent = 'Registered'; rbtn.disabled = true; }
            }
            showToast('Registered successfully');
          }
        } catch(err) {}
        closeModal();
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
