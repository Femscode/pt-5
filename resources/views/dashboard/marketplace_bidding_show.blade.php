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
      .detail-label {
        font-size: 14px;
        color: #6b7280;
        margin-bottom: 2px;
      }
      .detail-value {
        font-size: 15px;
        color: #111827;
      }
      .bid-detail-layout {
        display: flex;
        flex-direction: column;
        gap: 16px;
      }
      .bid-detail-top {
        background: #fff;
        border-radius: 16px;
        border: 1px solid #e5e7eb;
        padding: 16px 18px;
        display: flex;
        justify-content: space-between;
        gap: 16px;
      }
      .bid-detail-columns-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 16px;
      }
      .bid-detail-inner-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px 24px;
      }
      @media (max-width: 768px) {
        .mp-toolbar {
          flex-direction: column;
          align-items: flex-start;
          gap: 10px;
        }
        .bid-detail-top {
          flex-direction: column;
          align-items: flex-start;
        }
        .bid-detail-columns-2 {
          grid-template-columns: minmax(0, 1fr);
        }
        .bid-detail-inner-grid {
          grid-template-columns: minmax(0, 1fr);
        }
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
      <a href="{{ route('marketplace.my_biddings') }}" class="tab active">My Biddings</a>
    </div>

    <div class="mp-toolbar" style="margin-bottom:24px; margin-top:12px; display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap;">
      <div>
        <h2 style="margin:0; font-size:18px; font-weight:600;">Bidding Details</h2>
        <p style="margin:4px 0 0; font-size:15px; color:#6b7280;">View the full information for this equipment request.</p>
      </div>
      <div style="display:flex; gap:8px;">
        <a href="{{ route('marketplace.my_biddings') }}" class="btn btn-outline btn-primary">Back to My Biddings</a>
        <a href="{{ route('marketplace') }}" class="btn btn-outline btn-primary">Back to Marketplace</a>
      </div>
    </div>

    @php
      $product = $bid->product;
      $status = strtolower($bid->status ?? 'pending');
      $statusClass = $status === 'approved' ? 'status-approved' : ($status === 'rejected' ? 'status-rejected' : 'status-pending');
      $statusLabel = ucfirst($status);
    @endphp

    <div class="bid-detail-layout">
      <div class="bid-detail-top">
        <div>
          <div style="font-size:15px; color:#6b7280; margin-bottom:4px;">Equipment</div>
          <div style="font-size:18px; font-weight:600; color:#111827;">
            {{ $product->name ?? ($bid->equipment_name ?: 'Equipment Request') }}
          </div>
          <div style="margin-top:6px; font-size:14px; color:#6b7280;">
            Request ID:
            <span style="font-family:mono;">{{ $bid->request_code }}</span>
          </div>
          <div style="margin-top:4px; font-size:14px; color:#6b7280;">
            Submitted on {{ $bid->created_at->format('d M Y, H:i') }}
          </div>
        </div>
        <div style="text-align:right; display:flex; flex-direction:column; align-items:flex-end; gap:6px;">
          <span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
          <div style="font-size:14px; color:#6b7280;">
            Latest update: {{ $bid->updated_at->format('d M Y, H:i') }}
          </div>
        </div>
      </div>

      <div class="bid-detail-columns-2">
        <div style="background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:16px 18px;">
          <h3 style="margin:0 0 12px; font-size:15px; font-weight:600;">Organization & Contact Information</h3>
          <div class="bid-detail-inner-grid">
            <div>
              <div class="detail-label">Applicant Type</div>
              <div class="detail-value">{{ $bid->applicant_type ?: 'Not specified' }}</div>
            </div>
            <div>
              <div class="detail-label">Organization / Institution Name</div>
              <div class="detail-value">{{ $bid->organization_name ?: 'Not specified' }}</div>
            </div>
            <div>
              <div class="detail-label">Organization Website</div>
              <div class="detail-value">
                @if($bid->organization_website)
                  <a href="{{ $bid->organization_website }}" target="_blank" style="color:#2563eb; text-decoration:none;">{{ $bid->organization_website }}</a>
                @else
                  Not specified
                @endif
              </div>
            </div>
            <div>
              <div class="detail-label">Facility Address</div>
              <div class="detail-value">{{ $bid->facility_address ?: 'Not specified' }}</div>
            </div>
            <div>
              <div class="detail-label">Email</div>
              <div class="detail-value">{{ $bid->email }}</div>
            </div>
            <div>
              <div class="detail-label">Phone</div>
              <div class="detail-value">{{ $bid->phone }}</div>
            </div>
            <div>
              <div class="detail-label">Contact Person</div>
              <div class="detail-value">{{ $bid->contact_person }}</div>
            </div>
          </div>
        </div>

        <div style="background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:16px 18px;">
          <h3 style="margin:0 0 12px; font-size:15px; font-weight:600;">Equipment Request Details</h3>
          <div class="bid-detail-inner-grid">
            <div>
              <div class="detail-label">Equipment Requested</div>
              <div class="detail-value">{{ $bid->equipment_name }}</div>
            </div>
            <div>
              <div class="detail-label">Preferred Manufacturer</div>
              <div class="detail-value">{{ $bid->preferred_manufacturer ?: 'Any' }}</div>
            </div>
            <div>
              <div class="detail-label">Quantity</div>
              <div class="detail-value">{{ $bid->quantity }}</div>
            </div>
            <div>
              <div class="detail-label">Urgency</div>
              <div class="detail-value">{{ $bid->urgency }}</div>
            </div>
            <div>
              <div class="detail-label">Can Your Organisation Contribute To The Purchase Cost?</div>
              <div class="detail-value">{{ $bid->can_contribute }}</div>
            </div>
            <div>
              <div class="detail-label">Approximate Budget (if any)</div>
              <div class="detail-value">{{ $bid->budget ?: 'Not specified' }}</div>
            </div>
          </div>
        </div>
      </div>

      <div class="bid-detail-columns-2">
        <div style="background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:16px 18px;">
          <h3 style="margin:0 0 10px; font-size:15px; font-weight:600;">Statement Of Need</h3>
          <div class="detail-value" style="white-space:pre-line; font-size:15px; color:#374151;">
            {{ $bid->statement_of_need }}
          </div>
        </div>
        <div style="background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:16px 18px;">
          <h3 style="margin:0 0 10px; font-size:15px; font-weight:600;">Intended Use & Impact</h3>
          <div class="detail-value" style="white-space:pre-line; font-size:15px; color:#374151;">
            {{ $bid->intended_use }}
          </div>
          <div style="margin-top:16px; font-size:14px; color:#6b7280;">
            Agreement:
            <span style="font-weight:500;">
              {{ $bid->agreed ? 'Applicant confirmed that all information provided is accurate.' : 'Agreement not recorded.' }}
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
