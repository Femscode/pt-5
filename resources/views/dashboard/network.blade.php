@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/network.css') }}">
@endsection

@section('page_title')
Network
@endsection

@section('content')
  <div class="network-page">
    <div class="network-toolbar">
      <div class="network-search">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <circle cx="9" cy="9" r="7"/>
          <path d="M14 14l5 5"/>
        </svg>
        <input id="networkSearch" type="text" placeholder="Search by Name, Role, Location, Specialty" />
      </div>
      <button class="filter-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <path d="M3 6h18M6 12h12M10 18h4" />
        </svg>
        Filter
      </button>
      <div class="filter-popover" id="filterPopover">
        <div class="filter-row">
          <label for="filterStatus">Status</label>
          <select id="filterStatus">
            <option value="">All</option>
            <option value="connected">Connected</option>
            <option value="pending-recv">Pending Received</option>
            <option value="pending-sent">Pending Sent</option>
            <option value="none">Not Connected</option>
          </select>
        </div>
        <div class="filter-row">
          <label for="filterSpeciality">Specialization</label>
          <input type="text" id="filterSpeciality" placeholder="e.g., Cardiology" />
        </div>
        <div class="filter-row">
          <label for="filterInstitution">Institution</label>
          <input type="text" id="filterInstitution" placeholder="e.g., General Hospital" />
        </div>
        <div class="filter-actions">
          <button type="button" class="btn btn-outline btn-sm" id="filterClear">Clear</button>
        </div>
      </div>
    </div>

    <div class="stats-wrap">
      <div class="network-stats">
        <div class="stat-card">
          <div class="stat-value">{{ $counts['connections'] ?? 0 }}</div>
          <div class="stat-label" ><a class="accent-orange" href="#">Connections</a></div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ $counts['pending_invites'] ?? 0 }}</div>
          <div class="stat-label"><a href="#" class="accent-blue">Pending Invites</a></div>
        </div>
        <div class="stat-card">
          <div class="stat-value">{{ $counts['sent_requests'] ?? 0 }}</div>
          <div class="stat-label"><a href="#" class="accent-green">Sent Requests</a></div>
        </div>
      </div>
    </div>

    <div class="network-subtitle">Discover and connect with medical professionals in your field</div>

    <div class="network-tabs tabs-bar">
      <button class="tab active">Discover</button>
      <button class="tab">Received Invitations</button>
      <button class="tab">Sent Invitations</button>
      <button class="tab">My Connections</button>
    </div>
    @php
      $pendingSentIds = isset($sent) ? $sent->pluck('receiver_id')->all() : [];
      $pendingRecvIds = isset($received) ? $received->pluck('sender_id')->all() : [];
      $connectedIds = isset($accepted) ? $accepted->map(function($c) use($user){ return $c->sender_id == $user->id ? $c->receiver_id : $c->sender_id; })->all() : [];
    @endphp

    @php $totalRequests = (($received ?? collect())->count()) + (($sent ?? collect())->count()); @endphp
    @if($totalRequests > 0)
      <div class="requests-board">
        <div class="requests-title">Requests</div>
        <div class="requests-list">
          @foreach(($received ?? collect()) as $r)
            @php $initial = strtoupper(substr($r->sender->full_name ?? 'U',0,1)); @endphp
            <div class="request-item incoming">
              <div style="display:flex; align-items:center; gap:10px;">
                <div class="activity-avatar">{{ $initial }}</div>
                <div class="req-info">
                  <div class="req-name">{{ $r->sender->full_name }}</div>
                  <div class="req-meta">Incoming request</div>
                </div>
              </div>
              <div class="req-actions">
                <button class="btn btn-primary btn-sm btn-accept" data-id="{{ $r->id }}" data-user="{{ $r->sender_id }}" data-uuid="{{ $r->sender->uuid }}">Accept</button>
                <button class="btn btn-outline btn-sm btn-decline" data-id="{{ $r->id }}">Decline</button>
              </div>
            </div>
          @endforeach
          @foreach(($sent ?? collect()) as $s)
            @php $initialS = strtoupper(substr($s->receiver->full_name ?? 'U',0,1)); @endphp
            <div class="request-item sent">
              <div style="display:flex; align-items:center; gap:10px;">
                <div class="activity-avatar">{{ $initialS }}</div>
                <div class="req-info">
                  <div class="req-name">{{ $s->receiver->full_name }}</div>
                  <div class="req-meta status-pending">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="vertical-align:middle; margin-right:6px;">
                      <circle cx="12" cy="12" r="10"/>
                      <path d="M12 6v6h4.5"/>
                    </svg>
                    Pending
                  </div>
                </div>
              </div>
              <div class="req-actions">
                <button class="btn btn-outline btn-sm btn-cancel" data-id="{{ $s->id }}" data-user="{{ $s->receiver_id }}">Cancel</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    <div class="tab-panels">
      <div class="tab-panel active" data-tab="Discover">
        <div class="network-grid">
          @foreach(($allUsers ?? collect()) as $u)
            @php
              $initials = strtoupper(substr($u->full_name,0,1));
              $isConnected = in_array($u->id, $connectedIds);
              $disabled = in_array($u->id, $pendingSentIds);
              $btnText = $isConnected ? 'Message' : (in_array($u->id, $pendingRecvIds) ? 'Respond' : ($disabled ? 'Pending' : 'Connect'));
              $btnClass = $isConnected ? 'btn-message' : 'btn-primary';
            @endphp
            @php $imgUrl = $u->image ?: null; $status = $isConnected ? 'connected' : (in_array($u->id, $pendingRecvIds) ? 'pending-recv' : ($disabled ? 'pending-sent' : 'none')); @endphp
            <div class="profile-card" data-name="{{ $u->full_name }}" data-role="{{ $u->role }}" data-speciality="{{ $u->specialisation }}" data-institution="{{ $u->institution }}" data-status="{{ $status }}" data-id="{{ $u->id }}" data-uuid="{{ $u->uuid }}">
              <div class="card-header">
                @if($imgUrl)
                  <img class="avatar-img" src="{{ $imgUrl }}" alt="{{ $u->full_name }}">
                @else
                  <div class="avatar">{{ $initials }}</div>
                @endif
                <div class="info">
                  <div class="name">{{ $u->full_name }}</div>
                  <div class="specialties">{{ $u->specialisation ?? '—' }}</div>
                  <div class="meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="loc">
                      <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10Z"/>
                      <circle cx="12" cy="11" r="3"/>
                    </svg>
                    {{ $u->institution ?? 'Unknown institution' }}
                  </div>
                </div>
              </div>
              <div class="card-actions">
                <button class="btn btn-outline btn-full btn-view" data-name="{{ $u->full_name }}" data-speciality="{{ $u->specialisation }}" data-institution="{{ $u->institution }}" data-email="{{ $u->email }}" data-uuid="{{ $u->uuid }}" data-id="{{ $u->id }}" data-status="{{ $status }}" data-image="{{ $imgUrl }}">View Profile</button>
                <button class="btn {{ $btnClass }} btn-full btn-connect" data-id="{{ $u->id }}" data-uuid="{{ $u->uuid }}" @if($disabled) disabled @endif>{{ $btnText }}</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="tab-panel" data-tab="Received Invitations">
        <div class="network-grid">
          @foreach(($received ?? collect()) as $r)
            @php $imgUrl = $r->sender->image ?: null; @endphp
            <div class="profile-card" data-name="{{ $r->sender->full_name }}" data-role="{{ $r->sender->role }}" data-speciality="{{ $r->sender->specialisation }}" data-institution="{{ $r->sender->institution }}" data-status="pending-recv" data-id="{{ $r->sender_id }}" data-uuid="{{ $r->sender->uuid }}">
              <div class="card-header">
                @if($imgUrl)
                  <img class="avatar-img" src="{{ $imgUrl }}" alt="{{ $r->sender->full_name }}">
                @else
                  <div class="avatar">{{ strtoupper(substr($r->sender->full_name,0,1)) }}</div>
                @endif
                <div class="info">
                  <div class="name">{{ $r->sender->full_name }}</div>
                  <div class="specialties">{{ $r->sender->specialisation ?? '—' }}</div>
                  <div class="meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="loc">
                      <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10Z"/>
                      <circle cx="12" cy="11" r="3"/>
                    </svg>
                    {{ $r->sender->institution ?? 'Unknown institution' }}
                  </div>
                </div>
              </div>
              <div class="card-actions">
                <button class="btn btn-primary btn-full btn-accept" data-id="{{ $r->id }}">Accept</button>
                <button class="btn btn-outline btn-full btn-decline" data-id="{{ $r->id }}">Decline</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="tab-panel" data-tab="Sent Invitations">
        <div class="network-grid">
          @foreach(($sent ?? collect()) as $s)
            @php $imgUrl = $s->receiver->image ?: null; @endphp
            <div class="profile-card" data-name="{{ $s->receiver->full_name }}" data-role="{{ $s->receiver->role }}" data-speciality="{{ $s->receiver->specialisation }}" data-institution="{{ $s->receiver->institution }}" data-status="pending-sent" data-id="{{ $s->receiver_id }}" data-uuid="{{ $s->receiver->uuid }}">
              <div class="card-header">
                @if($imgUrl)
                  <img class="avatar-img" src="{{ $imgUrl }}" alt="{{ $s->receiver->full_name }}">
                @else
                  <div class="avatar">{{ strtoupper(substr($s->receiver->full_name,0,1)) }}</div>
                @endif
                <div class="info">
                  <div class="name">{{ $s->receiver->full_name }}</div>
                  <div class="specialties">{{ $s->receiver->specialisation ?? '—' }}</div>
                  <div class="meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="loc">
                      <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10Z"/>
                      <circle cx="12" cy="11" r="3"/>
                    </svg>
                    {{ $s->receiver->institution ?? 'Unknown institution' }}
                  </div>
                </div>
              </div>
              <div class="card-actions">
                <button class="btn btn-outline btn-full btn-cancel" data-id="{{ $s->id }}">Cancel Request</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="tab-panel" data-tab="My Connections">
        <div class="network-grid">
          @foreach(($accepted ?? collect()) as $c)
            @php $other = $c->sender_id === $user->id ? $c->receiver : $c->sender; @endphp
            @php $imgUrl = $other->image ?: null; @endphp
            <div class="profile-card" data-name="{{ $other->full_name }}" data-role="{{ $other->role }}" data-speciality="{{ $other->specialisation }}" data-institution="{{ $other->institution }}" data-status="connected" data-id="{{ $other->id }}" data-uuid="{{ $other->uuid }}">
              <div class="card-header">
                @if($imgUrl)
                  <img class="avatar-img" src="{{ $imgUrl }}" alt="{{ $other->full_name }}">
                @else
                  <div class="avatar">{{ strtoupper(substr($other->full_name,0,1)) }}</div>
                @endif
                <div class="info">
                  <div class="name">{{ $other->full_name }}</div>
                  <div class="specialties">{{ $other->specialisation ?? '—' }}</div>
                  <div class="meta">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="loc">
                      <path d="M12 21s-7-4.35-7-10a7 7 0 1 1 14 0c0 5.65-7 10-7 10Z"/>
                      <circle cx="12" cy="11" r="3"/>
                    </svg>
                    {{ $other->institution ?? 'Unknown institution' }}
                  </div>
                </div>
              </div>
              <div class="card-actions">
                <button class="btn btn-outline btn-full btn-view" data-name="{{ $other->full_name }}" data-speciality="{{ $other->specialisation }}" data-institution="{{ $other->institution }}" data-email="{{ $other->email }}" data-uuid="{{ $other->uuid }}" data-id="{{ $other->id }}" data-status="connected" data-image="{{ $imgUrl }}">View Profile</button>
                <button class="btn btn-message btn-full btn-message-start" data-uuid="{{ $other->uuid }}">Message</button>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div id="modalOverlay" class="modal-overlay" style="display:none">
      <div class="modal-card">
        <div class="modal-header">
          <div class="modal-title"></div>
          <button class="modal-close">×</button>
        </div>
        <div class="modal-body"></div>
      </div>
    </div>

    <div id="toastContainer" class="toast-container"></div>

    <script>
      (function(){
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
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
          const container = document.getElementById('toastContainer');
          container.appendChild(box);
          const prog = box.querySelector('.toast-progress');
          let start = null; const duration = 2500;
          function step(ts){ if(!start) start = ts; const p = Math.min((ts-start)/duration, 1); prog.style.width = (100*(1-p))+'%'; if(p<1) requestAnimationFrame(step); }
          requestAnimationFrame(step);
          setTimeout(()=>{ box.classList.add('hide'); setTimeout(()=> box.remove(), 300); }, duration);
        }
        async function post(url, data){
          const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(data) });
          return res.json();
        }

        document.querySelectorAll('.tabs-bar .tab').forEach(btn=>{
          btn.addEventListener('click', ()=>{
            document.querySelectorAll('.tabs-bar .tab').forEach(b=>b.classList.remove('active'));
            btn.classList.add('active');
            const label = btn.textContent.trim();
            document.querySelectorAll('.tab-panel').forEach(p=>{
              p.classList.toggle('active', p.getAttribute('data-tab')===label);
            });
          });
        });

        document.querySelectorAll('.btn-connect').forEach(btn=>{
          btn.addEventListener('click', async ()=>{
            if (btn.classList.contains('btn-message')) {
              const participantUuid = btn.getAttribute('data-uuid');
              if (!participantUuid) return;
              btn.disabled = true;
              const res = await post('{{ route('conversations.direct') }}', { participantUuid });
              btn.disabled = false;
              if (res && res.conversationUuid) {
                window.location.href = '{{ route('message') }}' + '?conversationUuid=' + encodeURIComponent(res.conversationUuid);
              } else {
                showToast('Unable to start conversation', 'error');
              }
              return;
            }
            const id = btn.getAttribute('data-id');
            btn.disabled = true;
            const data = await post('{{ route('connections.send') }}', { receiver_id: id });
            showToast(data.message || 'Request sent');
            btn.textContent = 'Pending';
          });
        });
        document.querySelectorAll('.btn-accept').forEach(btn=>{
          btn.addEventListener('click', async ()=>{
            const id = btn.getAttribute('data-id');
            const userId = btn.getAttribute('data-user');
            const userUuid = btn.getAttribute('data-uuid');
            const data = await post('{{ route('connections.accept') }}', { connection_id: id });
            showToast(data.message || 'Accepted');
            const item = btn.closest('.request-item'); if (item) item.remove();
            if (userId) {
              const connectBtn = document.querySelector('.btn-connect[data-id="'+userId+'"]');
              if (connectBtn) { connectBtn.disabled = false; connectBtn.textContent = 'Message'; connectBtn.classList.remove('btn-primary'); connectBtn.classList.add('btn-message'); if (userUuid) connectBtn.setAttribute('data-uuid', userUuid); }
            }
          });
        });
        document.querySelectorAll('.btn-decline').forEach(btn=>{
          btn.addEventListener('click', async ()=>{
            const id = btn.getAttribute('data-id');
            const data = await post('{{ route('connections.reject') }}', { connection_id: id });
            showToast(data.message || 'Declined');
            const item = btn.closest('.request-item'); if (item) item.remove();
          });
        });
        document.querySelectorAll('.btn-cancel').forEach(btn=>{
          btn.addEventListener('click', async ()=>{
            const id = btn.getAttribute('data-id');
            const data = await post('{{ route('connections.cancel') }}', { connection_id: id });
            showToast(data.message || 'Cancelled');
            const item = btn.closest('.request-item'); if (item) item.remove();
          });
        });

        const overlay = document.getElementById('modalOverlay');
        const title = overlay.querySelector('.modal-title');
        const body = overlay.querySelector('.modal-body');
        const closeBtn = overlay.querySelector('.modal-close');
        function openModal(name, speciality, institution, email, uuid, status, id, imageUrl){
          const initials = (name||' ').trim().substring(0,1).toUpperCase();
          title.textContent = '';
          body.innerHTML = ''+
            '<div class="modal-profile">'+
              (imageUrl ? '<img class="modal-avatar-img" src="'+ imageUrl +'" alt="'+ (name||'') +'">' : '<div class="modal-avatar">'+ initials +'</div>')+
              '<div class="modal-summary">'+
                '<div class="modal-name">'+ (name||'—') +'</div>'+
                '<div class="modal-sub">'+ (speciality||'—') +'</div>'+
                '<div class="modal-meta">'+ (institution||'—') +'</div>'+
                '<div class="modal-actions">'+
                  (status==='connected' ? '<button class="btn btn-message btn-message-start" data-uuid="'+ (uuid||'') +'">Message</button>' : '<button class="btn btn-primary btn-connect-start" data-id="'+ (id||'') +'" data-uuid="'+ (uuid||'') +'">Connect</button>')+
                  '<button class="btn btn-outline modal-return">Close</button>'+
                '</div>'+
              '</div>'+
            '</div>'+
            '<div class="modal-section">'+
              '<div class="modal-section-title">About '+ (name||'—') +'</div>'+
              '<div class="modal-line">No additional information provided.</div>'+
            '</div>'+
            '<div class="modal-section">'+
              '<div class="modal-section-title">Areas of Expertise</div>'+
              '<ul class="modal-list">'+
                (String(speciality||'').split(',').filter(Boolean).map(s=>'<li>'+s.trim()+'</li>').join('') || '<li>—</li>')+
              '</ul>'+
            '</div>';
          overlay.style.display = 'flex';
          const ret = overlay.querySelector('.modal-return');
          if (ret) ret.addEventListener('click', closeModal);
        }
        function closeModal(){ overlay.style.display = 'none'; }
        closeBtn.addEventListener('click', closeModal);
        overlay.addEventListener('click', (e)=>{ if(e.target===overlay) closeModal(); });
        document.querySelectorAll('.btn-view').forEach(btn=>{
          btn.addEventListener('click', ()=>{
            openModal(btn.dataset.name, btn.dataset.speciality, btn.dataset.institution, btn.dataset.email, btn.dataset.uuid, btn.dataset.status, btn.dataset.id, btn.dataset.image);
          });
        });

        document.addEventListener('click', async (e)=>{
          const el = e.target.closest('.btn-message-start');
          if (!el) return;
          const participantUuid = el.getAttribute('data-uuid');
          if (!participantUuid) return;
          el.disabled = true;
          const res = await post('{{ route('conversations.direct') }}', { participantUuid });
          el.disabled = false;
          if (res && res.conversationUuid) {
            window.location.href = '{{ route('message') }}' + '?conversationUuid=' + encodeURIComponent(res.conversationUuid);
          } else {
            showToast('Unable to start conversation', 'error');
          }
        });
        const filterBtn = document.querySelector('.filter-btn');
        const filterPopover = document.getElementById('filterPopover');
        const searchInput = document.getElementById('networkSearch');
        const filterStatus = document.getElementById('filterStatus');
        const filterSpeciality = document.getElementById('filterSpeciality');
        const filterInstitution = document.getElementById('filterInstitution');

        function applyFilters(){
          const panel = document.querySelector('.tab-panel.active');
          if(!panel) return;
          const q = (searchInput ? searchInput.value : '').trim().toLowerCase();
          const stat = filterStatus ? filterStatus.value : '';
          const specQ = (filterSpeciality ? filterSpeciality.value : '').trim().toLowerCase();
          const instQ = (filterInstitution ? filterInstitution.value : '').trim().toLowerCase();
          panel.querySelectorAll('.profile-card').forEach(card => {
            const name = (card.dataset.name||'').toLowerCase();
            const role = (card.dataset.role||'').toLowerCase();
            const spec = (card.dataset.speciality||'').toLowerCase();
            const inst = (card.dataset.institution||'').toLowerCase();
            const cardStat = (card.dataset.status||'');
            const matchesSearch = !q || [name, role, spec, inst].some(v => v.includes(q));
            const matchesStatus = !stat || cardStat === stat;
            const matchesSpec = !specQ || spec.includes(specQ);
            const matchesInst = !instQ || inst.includes(instQ);
            card.style.display = (matchesSearch && matchesStatus && matchesSpec && matchesInst) ? '' : 'none';
          });
        }

        if (filterBtn && filterPopover) {
          filterBtn.addEventListener('click', (e) => { filterPopover.classList.toggle('open'); e.stopPropagation(); });
          document.addEventListener('click', (e) => { if (!e.target.closest('#filterPopover') && !e.target.closest('.filter-btn')) { filterPopover.classList.remove('open'); } });
        }
        if (searchInput) searchInput.addEventListener('input', applyFilters);
        [filterStatus, filterSpeciality, filterInstitution].forEach(el => { if(el) el.addEventListener('input', applyFilters); });
        const clearBtn = document.getElementById('filterClear');
        if (clearBtn) clearBtn.addEventListener('click', () => { if(filterStatus) filterStatus.value=''; if(filterSpeciality) filterSpeciality.value=''; if(filterInstitution) filterInstitution.value=''; applyFilters(); });

        document.addEventListener('click', async (e)=>{
          const el = e.target.closest('.btn-connect-start');
          if (!el) return;
          const id = el.getAttribute('data-id');
          el.disabled = true;
          const data = await post('{{ route('connections.send') }}', { receiver_id: id });
          el.disabled = false;
          showToast(data.message || 'Request sent');
          closeModal();
          const connectBtn = document.querySelector('.btn-connect[data-id="'+id+'"]');
          if (connectBtn) { connectBtn.textContent = 'Pending'; connectBtn.disabled = true; }
        });

        applyFilters();
      })();
    </script>
  </div>
@endsection
