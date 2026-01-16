@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/marketplace.css') }}">
    <style>
      .status-pill {
        display: inline-flex;
        align-items: center;
        padding: 3px 8px;
        border-radius: 999px;
        font-size: 14px;
      }
      .status-pill.status-pending {
        background: #fffbeb;
        color: #92400e;
      }
      .status-pill.status-approved {
        background: #dcfce7;
        color: #16a34a;
      }
      .status-pill.status-rejected {
        background: #fee2e2;
        color: #b91c1c;
      }
    </style>
@endsection

@section('page_title')
My Biddings
@endsection

@section('content')
  <div class="mp-page">
    <div class="mp-tabs">
      <a href="{{ route('marketplace') }}" class="tab">Paid Products</a>
      <a href="{{ route('marketplace') }}" class="tab">Donation Hub</a>
      <button class="tab active" type="button">My Biddings</button>
    </div>

    @if (session('status'))
      <div style="margin-top:16px; margin-bottom: 16px; padding: 10px 12px; border-radius: 8px; background:#ecfdf5; color:#166534;">
        {{ session('status') }}
      </div>
    @endif

    <div class="mp-toolbar" style="margin-bottom:24px; margin-top:12px;">
      <div>
        <h2 style="margin:0; font-size:18px; font-weight:600;">My Biddings</h2>
        <p style="margin:4px 0 0; font-size:15px; color:#6b7280;">Track the status of your equipment requests.</p>
      </div>
      <a href="{{ route('marketplace') }}" class="btn btn-outline btn-primary">Back to Marketplace</a>
    </div>

    @if($biddings->isEmpty())
      <div class="empty-state">You have not submitted any biddings yet.</div>
    @else
      <div style="background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:12px 16px;">
        <div style="display:grid; grid-template-columns:2fr 1.5fr 1fr 1fr; gap:8px; padding:8px 0; font-size:14px; font-weight:600; color:#6b7280; border-bottom:1px solid #e5e7eb;">
          <div>Equipment</div>
          <div>Request ID</div>
          <div>Status</div>
          <div>Date</div>
        </div>
        @foreach($biddings as $bid)
          @php
            $product = $bid->product;
            $status = strtolower($bid->status ?? 'pending');
            $statusLabel = ucfirst($status);
          @endphp
          <div style="display:grid; grid-template-columns:2fr 1.5fr 1fr 1fr; gap:8px; padding:10px 0; font-size:15px; align-items:center; border-bottom:1px solid #f3f4f6;">
            <div>
              <div style="font-weight:500; color:#111827;">{{ $product->name ?? 'Equipment Request' }}</div>
              <div style="margin-top:4px;">
                <a href="{{ route('marketplace.my_biddings.show', $bid) }}" style="font-size:14px; color:#2563eb; text-decoration:none;">
                  View details
                </a>
              </div>
            </div>
            <div>
              <span style="font-family:mono; font-size:14px;">{{ $bid->request_code }}</span>
            </div>
            <div>
              <span class="status-pill status-{{ $status === 'approved' ? 'approved' : ($status === 'rejected' ? 'rejected' : 'pending') }}">
                {{ $statusLabel }}
              </span>
            </div>
            <div>
              <span style="font-size:14px; color:#4b5563;">{{ $bid->created_at->format('d M Y') }}</span>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
