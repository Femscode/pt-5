@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/index.css') }}">
@endsection

@section('content')
    <section class="welcome-banner">
        <div class="welcome-text">
            <h2>Welcome Back here, {{ $user->full_name ?? 'User' }} 👋</h2>
            <p>Here’s what’s happening today.</p>
        </div>
    </section>

    <section class="stats-grid">
        <div class="stat-card">
            <div class="frame">
                <div class="text-wrapper">{{ $messagesCount ?? 0 }}</div>
                <div class="div">Messages</div>
            </div>
            <div class="icon-message-outline-wrapper">
                <svg class="icon-message-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="frame">
                <div class="text-wrapper">{{ $connectionsCount ?? 0 }}</div>
                <div class="div">Network</div>
            </div>
            <div class="icon-network-outline-wrapper">
                <svg class="icon-network-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/>
                    <path d="M13.73 21a2 2 0 0 1-3.46 0"/>
                </svg>
            </div>
        </div>

        <div class="stat-card">
            <div class="frame">
                <div class="text-wrapper">{{ $eventsCount ?? 0 }}</div>
                <div class="div">Event</div>
            </div>
            <div class="icon-calendar-outline-wrapper">
                <svg class="icon-calendar-outline" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
                    <line x1="16" y1="2" x2="16" y2="6"/>
                    <line x1="8" y1="2" x2="8" y2="6"/>
                    <line x1="3" y1="10" x2="21" y2="10"/>
                </svg>
            </div>
        </div>
    </section>

    <section class="dashboard-grid">
        <div class="card activities">
            @php $totalRequests = (($received ?? collect())->count()) + (($sent ?? collect())->count()); @endphp
            <div class="card-header">
                <div style="display:flex; align-items:center; justify-content:space-between;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div class="activity-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="icon"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                        </div>
                        <div>Recent Activities</div>
                    </div>
                    <div class="empty-text">{{ $totalRequests }} pending</div>
                </div>
            </div>

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
            @else
                <div class="requests-board">
                    <div class="requests-list" style="display:flex; align-items:center; justify-content:center; padding:24px;">
                        <div style="display:flex; flex-direction:column; align-items:center; gap:8px;">
                            <svg width="28" height="28" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="9" cy="9" r="7"/>
                                <path d="M14 14l5 5"/>
                            </svg>
                            <div class="empty-text">No recent activity</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="card calendar-card">
            <div class="card-header">Upcoming Events</div>
            <div class="calendar" id="dashboardCalendar">
                <div class="calendar-header">
                    <div class="month" id="calendarMonth"></div>
                    <div class="weekdays">
                        <span>m</span><span>t</span><span>w</span><span>t</span><span>f</span><span>s</span><span>s</span>
                    </div>
                </div>
                <div class="calendar-controls">
                    <button id="prevMonth" aria-label="Previous month">‹</button>
                    <button id="nextMonth" aria-label="Next month">›</button>
                </div>
                <div class="days" id="calendarDays"></div>
            </div>
            <div class="event-card">
                <div id="eventList"></div>
                <div class="event-footer"></div>
            </div>
        </div>
    </section>
    <script>
    (function() {
      const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];
      @php
        $calendarEvents = [];
        foreach (($events ?? []) as $e) {
            if (!empty($e->start_date)) {
                $calendarEvents[] = [
                    'date' => \Carbon\Carbon::parse($e->start_date)->format('Y-m-d'),
                    'title' => $e->title,
                    'cta' => !empty($e->event_link) ? 'Join' : 'View',
                    'link' => $e->event_link,
                ];
            }
        }
      @endphp
      const events = @json($calendarEvents);
      const now = new Date();
      let currentYear = now.getFullYear();
      let currentMonth = now.getMonth();

      const calendarMonth = document.getElementById('calendarMonth');
      const calendarDays = document.getElementById('calendarDays');
      const prevBtn = document.getElementById('prevMonth');
      const nextBtn = document.getElementById('nextMonth');
      const eventList = document.getElementById('eventList');

      function formatDate(y, m, d) {
        return y + '-' + String(m + 1).padStart(2, '0') + '-' + String(d).padStart(2, '0');
      }

      function renderEventsFor(dateStr) {
        const dayEvents = events.filter(e => e.date === dateStr);
        eventList.innerHTML = '';
        if (dayEvents.length === 0) {
          return;
        }
        dayEvents.forEach(e => {
          const wrap = document.createElement('div');
          wrap.className = 'unread-notification';
          const badge = document.createElement('div');
          badge.className = 'calendar-day-small';
          const badgeFrame = document.createElement('div');
          badgeFrame.className = 'frame';
          const typo = document.createElement('div');
          typo.className = 'typography';
          const t = document.createElement('div');
          t.className = 'text-wrapper';
          t.textContent = String(parseInt(dateStr.slice(-2), 10)).padStart(2, '0');
          typo.appendChild(t);
          badgeFrame.appendChild(typo);
          badge.appendChild(badgeFrame);
          const content = document.createElement('div');
          content.className = 'content';
          const p = document.createElement('p');
          p.className = 'annual-medical';
          const s1 = document.createElement('span');
          s1.className = 'span';
          s1.textContent = e.title;
          const s2 = document.createElement('span');
          s2.className = 'text-wrapper-2';
          const d = new Date(e.date);
          s2.textContent = monthNames[d.getMonth()].slice(0,3) + ' ' + String(d.getDate()).padStart(2,'0') + ', ' + d.getFullYear();
          p.appendChild(s1);
          p.appendChild(document.createElement('br'));
          p.appendChild(s2);
          content.appendChild(p);
          const cta = document.createElement('div');
          cta.className = 'event-cta';
          const ctaText = document.createElement('div');
          ctaText.className = 'text-wrapper-3';
          ctaText.textContent = e.cta || 'View';
          cta.appendChild(ctaText);
          wrap.appendChild(badge);
          wrap.appendChild(content);
          wrap.appendChild(cta);
          eventList.appendChild(wrap);
        });
      }

      function renderMonthlyEvents() {
        eventList.innerHTML = '';
        const monthEvents = events.filter(e => {
          const d = new Date(e.date);
          return d.getFullYear() === currentYear && d.getMonth() === currentMonth;
        });
        monthEvents.forEach(e => {
          const wrap = document.createElement('div');
          wrap.className = 'unread-notification';
          const badge = document.createElement('div');
          badge.className = 'calendar-day-small';
          const badgeFrame = document.createElement('div');
          badgeFrame.className = 'frame';
          const typo = document.createElement('div');
          typo.className = 'typography';
          const t = document.createElement('div');
          t.className = 'text-wrapper';
          const d = new Date(e.date);
          t.textContent = String(d.getDate()).padStart(2, '0');
          typo.appendChild(t);
          badgeFrame.appendChild(typo);
          badge.appendChild(badgeFrame);
          const content = document.createElement('div');
          content.className = 'content';
          const p = document.createElement('p');
          p.className = 'annual-medical';
          const s1 = document.createElement('span');
          s1.className = 'span';
          s1.textContent = e.title;
          const s2 = document.createElement('span');
          s2.className = 'text-wrapper-2';
          s2.textContent = monthNames[d.getMonth()].slice(0,3) + ' ' + String(d.getDate()).padStart(2,'0') + ', ' + d.getFullYear();
          p.appendChild(s1);
          p.appendChild(document.createElement('br'));
          p.appendChild(s2);
          content.appendChild(p);
          const cta = document.createElement('div');
          cta.className = 'event-cta';
          const ctaText = document.createElement('div');
          ctaText.className = 'text-wrapper-3';
          ctaText.textContent = e.cta || 'View';
          cta.appendChild(ctaText);
          wrap.appendChild(badge);
          wrap.appendChild(content);
          wrap.appendChild(cta);
          eventList.appendChild(wrap);
        });
      }

      function renderCalendar() {
        calendarMonth.textContent = monthNames[currentMonth] + ' ' + currentYear;
        calendarDays.innerHTML = '';
        const first = new Date(currentYear, currentMonth, 1);
        const startOffset = (first.getDay() + 6) % 7;
        const total = new Date(currentYear, currentMonth + 1, 0).getDate();
        for (let i = 0; i < startOffset; i++) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          calendarDays.appendChild(empty);
        }
        for (let day = 1; day <= total; day++) {
          const btn = document.createElement('button');
          btn.textContent = String(day).padStart(2, '0');
          const dateStr = formatDate(currentYear, currentMonth, day);
          const has = events.some(e => e.date === dateStr);
          if (has) btn.classList.add('has-event');
          btn.addEventListener('click', function() {
            Array.from(calendarDays.querySelectorAll('button.selected')).forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            renderEventsFor(dateStr);
          });
          calendarDays.appendChild(btn);
        }
        const today = new Date();
        if (today.getFullYear() === currentYear && today.getMonth() === currentMonth) {
          const todayBtn = Array.from(calendarDays.querySelectorAll('button')).find(b => parseInt(b.textContent,10) === today.getDate());
          if (todayBtn) { todayBtn.classList.add('selected'); }
        }
      }

      prevBtn.addEventListener('click', function() {
        if (currentMonth === 0) { currentMonth = 11; currentYear--; } else { currentMonth--; }
        renderCalendar();
        renderMonthlyEvents();
      });
      nextBtn.addEventListener('click', function() {
        if (currentMonth === 11) { currentMonth = 0; currentYear++; } else { currentMonth++; }
        renderCalendar();
        renderMonthlyEvents();
      });

      renderCalendar();
      renderMonthlyEvents();
    })();
    </script>
    <script>
      (function(){
        const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        async function post(url, data){
          const res = await fetch(url, { method: 'POST', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify(data) });
          return res.json();
        }
        document.querySelectorAll('.btn-accept').forEach(function(btn){
          btn.addEventListener('click', async function(){
            const id = btn.getAttribute('data-id');
            await post('{{ route('connections.accept') }}', { connection_id: id });
            const item = btn.closest('.request-item'); if (item) item.remove();
          });
        });
        document.querySelectorAll('.btn-decline').forEach(function(btn){
          btn.addEventListener('click', async function(){
            const id = btn.getAttribute('data-id');
            await post('{{ route('connections.reject') }}', { connection_id: id });
            const item = btn.closest('.request-item'); if (item) item.remove();
          });
        });
        document.querySelectorAll('.btn-cancel').forEach(function(btn){
          btn.addEventListener('click', async function(){
            const id = btn.getAttribute('data-id');
            await post('{{ route('connections.cancel') }}', { connection_id: id });
            const item = btn.closest('.request-item'); if (item) item.remove();
          });
        });
      })();
    </script>
@endsection
