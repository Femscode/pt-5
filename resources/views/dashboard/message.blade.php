@extends('dashboard.master')
@section('header')
  <link rel="stylesheet" href="{{ url('assets/css/dashboard/message.css') }}">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pusher/8.4.0/pusher.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/laravel-echo/1.15.0/echo.iife.min.js"></script>
@endsection

@section('page_title')
Messages
@endsection

@section('content')
<div class="messages-layout">
  <aside class="inbox">
    <div class="inbox-header">
      <div class="title">Messages <span class="count">{{ ($conversations ?? collect())->count() }}</span></div>
      <div class="search">
        <svg viewBox="0 0 20 20" class="icon"><circle cx="9" cy="9" r="7"/><path d="M14 14l5 5"/></svg>
        <input type="text" id="inboxSearch" placeholder="Search..." />
      </div>
      <div class="tabs">
        <button class="tab active" data-tab="direct">Direct Messages</button>
        <button class="tab" data-tab="group">Group Messages</button>
      </div>
    </div>
    <div class="inbox-list" id="inboxList">
      @foreach(($conversations ?? collect()) as $conv)
        @php
          $last = $conv->messages->last();
          $name = $conv->type === 'direct' ? optional($conv->participants->firstWhere('id','!=',$user->id))->full_name : ($conv->name ?? 'Group');
          $avatar = strtoupper(substr($name ?? 'N/A',0,1));
        @endphp
        <button class="inbox-item" data-uuid="{{ $conv->uuid }}" data-id="{{ $conv->id }}" data-type="{{ $conv->type }}">
          <div class="avatar">{{ $avatar }}</div>
          <div class="info">
            <div class="name">{{ $name }}</div>
            <div class="preview">{{ $last->content ?? 'Enter your message description here...' }}</div>
          </div>
          <div class="time">{{ $last ? $last->created_at->format('H:i') : '' }}</div>
        </button>
      @endforeach
    </div>
  </aside>

  <section class="thread">
    <div class="thread-header">
      <div class="thread-title" id="threadTitle">Select a conversation</div>
      <div class="thread-actions">
        <button class="icon-btn">⋯</button>
      </div>
    </div>
    <div class="thread-body" id="threadBody"></div>
    <div class="composer">
      <input type="text" id="composerInput" placeholder="Type a message" />
      <button id="composerSend" class="send-btn">Send</button>
    </div>
  </section>
</div>

<script>
(function(){
  if (window.Pusher) { window.Pusher.logToConsole = true; }
  const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  let activeConv = null;
  let echo = null;
  let pollTimer = null;
  const subscribedConversations = new Set();
  function isConnected(){
    return !!(echo && echo.connector && echo.connector.pusher && echo.connector.pusher.connection && echo.connector.pusher.connection.state === 'connected');
  }
  function initEcho(){
    echo = null;
  }
  function subscribeConversation(uuid){
    return;
  }

  function ensureRealtime(){
    if (activeConv && !pollTimer) {
      pollTimer = setInterval(async ()=>{
        try {
          const res = await fetch('{{ route('conversations.messages') }}?conversationUuid='+encodeURIComponent(activeConv.uuid));
          const data = await res.json();
          (data.messages||[]).forEach((m)=>{
            if (!document.querySelector('[data-msg="'+(m.uuid||'')+'"]')) {
              appendMessage(m);
            }
          });
        } catch (e) {
          console.error('Polling error', e);
        }
      }, 3000);
    }
  }

  let inboxPollTimer = null;
  function startInboxPolling(){
    if (!inboxPollTimer) {
      inboxPollTimer = setInterval(async ()=>{
        try {
          const res = await fetch('{{ route('conversations.inbox') }}');
          const data = await res.json();
          (data.items||[]).forEach((item)=>{
            const btn = document.querySelector('.inbox-item[data-uuid="'+item.conversationUuid+'"]');
            if (btn) {
              const preview = btn.querySelector('.preview');
              if (preview && item.lastMessage && item.lastMessage.content) preview.textContent = item.lastMessage.content;
            }
          });
        } catch (e) {}
      }, 4000);
    }
  }

  function appendMessage(msg){
    const me = {{ (int)$user->id }};
    const wrap = document.createElement('div');
    wrap.className = 'bubble '+(msg.senderId===me?'bubble-me':'bubble-other');
    wrap.setAttribute('data-msg', msg.uuid || '');
    wrap.innerHTML = '<div class="bubble-content">'+(msg.content||'')+'</div>'+
                     '<div class="bubble-time">'+(new Date(msg.createdAt).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}))+'</div>';
    document.getElementById('threadBody').appendChild(wrap);
    document.getElementById('threadBody').scrollTop = document.getElementById('threadBody').scrollHeight;
  }

  async function loadConversation(uuid){
    const res = await fetch('{{ route('conversations.messages') }}?conversationUuid='+encodeURIComponent(uuid));
    const data = await res.json();
    activeConv = data.conversation;
    document.getElementById('threadTitle').textContent = activeConv.type==='direct' && data.conversation.participants ? (data.conversation.participants.find(p=>p.id!=={{ (int)$user->id }}).full_name) : (activeConv.name||'Conversation');
    const body = document.getElementById('threadBody');
    body.innerHTML='';
    (data.messages||[]).forEach(appendMessage);
    subscribeConversation(activeConv.uuid);
    ensureRealtime();
  }

  document.querySelectorAll('.inbox-item').forEach(btn=>{
    btn.addEventListener('click', ()=>{ loadConversation(btn.dataset.uuid); document.querySelectorAll('.inbox-item').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); });
  });

  document.getElementById('composerSend').addEventListener('click', async ()=>{
    const input = document.getElementById('composerInput');
    const text = input.value.trim();
    if (!text || !activeConv) return;
    const sendBtn = document.getElementById('composerSend');
    sendBtn.disabled = true;
    const tempId = 'temp-'+Date.now();
    const optimistic = {
      id: null,
      uuid: tempId,
      conversationId: activeConv.id,
      senderId: {{ (int)$user->id }},
      messageType: 'text',
      content: text,
      createdAt: new Date().toISOString()
    };
    appendMessage(optimistic);
    input.value = '';

    const payload = { content: text, messageType:'text' };
    if (activeConv && activeConv.id) { payload.conversationId = activeConv.id; }
    else if (activeConv && activeConv.uuid) { payload.conversationUuid = activeConv.uuid; }
    try {
      const res = await fetch('{{ route('messages.store') }}', { method:'POST', headers:{ 'Content-Type':'application/json','X-CSRF-TOKEN': csrf }, body: JSON.stringify(payload) });
      if (res.ok) {
        const msg = await res.json();
        const el = document.querySelector('[data-msg="'+tempId+'"]');
        if (el) { el.setAttribute('data-msg', msg.uuid || tempId); }
      } else {
        const el = document.querySelector('[data-msg="'+tempId+'"]');
        if (el) { el.classList.add('bubble-error'); }
      }
    } catch (e) {
      const el = document.querySelector('[data-msg="'+tempId+'"]');
      if (el) { el.classList.add('bubble-error'); }
    }
    sendBtn.disabled = false;
  });

  document.getElementById('composerInput').addEventListener('keydown', (e)=>{
    if (e.key === 'Enter') {
      e.preventDefault();
      document.getElementById('composerSend').click();
    }
  });

  initEcho();
  // Proactively subscribe to all conversations in inbox so previews update without clicks
  document.querySelectorAll('.inbox-item').forEach(()=>{});
  ensureRealtime();
  startInboxPolling();
  const initialUuid = new URLSearchParams(window.location.search).get('conversationUuid');
  if (initialUuid) {
    activeConv = { id: null, uuid: initialUuid, type: 'direct' };
    loadConversation(initialUuid);
    const btn = document.querySelector('.inbox-item[data-uuid="'+initialUuid+'"]');
    if (btn) { document.querySelectorAll('.inbox-item').forEach(b=>b.classList.remove('active')); btn.classList.add('active'); }
  }
})();
</script>
@endsection
