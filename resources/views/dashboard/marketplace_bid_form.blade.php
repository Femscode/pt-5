@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/marketplace.css') }}">
    <style>
      .bid-form-page .bid-form-card {
        padding: 20px 18px;
      }
      .bid-form-page .bid-step {
        gap: 16px;
      }
      .bid-form-page .bid-grid-3 {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 12px;
      }
      .bid-form-page .bid-grid-2 {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
      }
      .bid-form-page .bid-actions-row {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
      }
      @media (max-width: 768px) {
        .bid-form-page .bid-form-card {
          padding: 16px 14px;
        }
        .bid-form-page .bid-grid-3 {
          grid-template-columns: minmax(0, 1fr);
        }
        .bid-form-page .bid-grid-2 {
          grid-template-columns: minmax(0, 1fr);
        }
        .bid-form-page .bid-actions-row {
          flex-direction: column;
          align-items: stretch;
        }
        .bid-form-page .bid-actions-row button {
          width: 100%;
          justify-content: center;
        }
      }
    </style>
@endsection

@section('page_title')
Marketplace
@endsection

@section('content')
  @php
    $user = $user ?? auth()->user();
  @endphp
  <div class="mp-page bid-form-page">
    <div class="mp-tabs">
      <a href="{{ route('marketplace') }}" class="tab" data-tab="paid">All Equipments</a>
      <button class="tab active" data-tab="donation" type="button">Donation Hub</button>
    </div>

    <div class="bid-form-card" style="margin-top:16px; background:#fff; border-radius:16px; border:1px solid #e5e7eb; padding:24px 28px;">
      <div style="margin-bottom:16px;">
        <h2 style="margin:0 0 4px; font-size:20px; font-weight:600;">Request Medical Equipment</h2>
        <p style="margin:0; font-size:13px; color:#6b7280; max-width:720px;">
          Please complete this form in as much detail as possible. This information helps us verify your need and match you with the most suitable available equipment. All information is confidential and will only be used for the purpose of facilitating your request.
        </p>
      </div>

      <div style="display:flex; align-items:center; gap:8px; font-size:12px; color:#6b7280; margin-bottom:20px;">
        <span style="font-weight:600; color:#111827;">Organization &amp; Contact Information</span>
        <span>&raquo;</span>
        <span>Equipment Request Details</span>
        <span>&raquo;</span>
        <span>Impact &amp; Final Details</span>
      </div>

      @if ($errors->any())
        <div style="margin-bottom:16px; padding:10px 12px; border-radius:8px; background:#fef2f2; color:#991b1b;">
          <ul style="margin:0; padding-left:18px;">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('marketplace.products.bid.submit', $product) }}" id="bidForm">
        @csrf
        <div id="bid-step-1">
          <div class="bid-step" style="display:flex; flex-direction:column; gap:16px;">
            <div>
              <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:6px;">Are you an Individual or an Institution</label>
              <select name="applicant_type" style="width:100%; max-width:260px; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
                <option value="">Select</option>
                <option value="individual" {{ old('applicant_type') === 'individual' ? 'selected' : '' }}>Individual</option>
                <option value="institution" {{ old('applicant_type') === 'institution' ? 'selected' : '' }}>Institution</option>
              </select>
            </div>

            <div class="bid-grid-3">
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Organization/Institution Name</label>
                <input type="text" name="organization_name" value="{{ old('organization_name') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Organization Website</label>
                <input type="text" name="organization_website" value="{{ old('organization_website') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Address of Facility</label>
                <input type="text" name="facility_address" value="{{ old('facility_address') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
            </div>

            <div class="bid-grid-3">
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Email Address</label>
                <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Phone Number</label>
                <input type="text" name="phone" value="{{ old('phone') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Contact Person</label>
                <input type="text" name="contact_person" value="{{ old('contact_person', $user->full_name ?? '') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
            </div>

            <div style="margin-top:16px;">
              <button type="button" id="bid-next-1" style="border:none; outline:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:10px 40px; border-radius:999px; background:#2563eb; color:#fff; font-weight:500; font-size:14px;">
                Next
              </button>
            </div>
          </div>
        </div>

        <div id="bid-step-2" style="display:none;">
          <div class="bid-step" style="display:flex; flex-direction:column; gap:16px;">
            <div class="bid-grid-3">
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Name of Equipment</label>
                <input type="text" name="equipment_name" value="{{ old('equipment_name', $product->name ?? '') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Preferred Manufacturer/Model</label>
                <input type="text" name="preferred_manufacturer" value="{{ old('preferred_manufacturer') }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Quantity Needed</label>
                <input type="number" min="1" name="quantity" value="{{ old('quantity', 1) }}" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
              </div>
            </div>

            <div>
              <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Urgency of Need</label>
              <select name="urgency" style="width:100%; max-width:260px; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
                <option value="">Select</option>
                <option value="not-urgent" {{ old('urgency') === 'not-urgent' ? 'selected' : '' }}>Not Urgent</option>
                <option value="urgent" {{ old('urgency') === 'urgent' ? 'selected' : '' }}>Urgent</option>
                <option value="very-urgent" {{ old('urgency') === 'very-urgent' ? 'selected' : '' }}>Very Urgent</option>
              </select>
            </div>

            <div>
              <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:6px;">Is your facility able to pay for any portion of the equipment or shipping costs?</label>
              @php
                $contrib = old('can_contribute');
              @endphp
              <div style="display:flex; flex-direction:column; gap:4px; font-size:13px; color:#374151;">
                <label style="display:flex; gap:6px; align-items:center;">
                  <input type="radio" name="can_contribute" value="yes" {{ $contrib === 'yes' ? 'checked' : '' }}>
                  <span>Yes, we can contribute.</span>
                </label>
                <label style="display:flex; gap:6px; align-items:center;">
                  <input type="radio" name="can_contribute" value="no" {{ $contrib === 'no' ? 'checked' : '' }}>
                  <span>No, we rely fully on donations.</span>
                </label>
              </div>
            </div>

            <div>
              <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">If yes, please specify approximate budget</label>
              <input type="text" name="budget" value="{{ old('budget') }}" style="width:100%; max-width:360px; border-radius:10px; border:1px solid #e5e7eb; padding:8px 10px; font-size:14px;">
            </div>

            <div class="bid-actions-row" style="margin-top:16px; display:flex; gap:12px;">
              <button type="button" id="bid-back-2" style="border:none; outline:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:10px 24px; border-radius:999px; background:#e5e7eb; color:#111827; font-weight:500; font-size:14px;">
                Back
              </button>
              <button type="button" id="bid-next-2" style="border:none; outline:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:10px 40px; border-radius:999px; background:#2563eb; color:#fff; font-weight:500; font-size:14px;">
                Next
              </button>
            </div>
          </div>
        </div>

        <div id="bid-step-3" style="display:none;">
          <div class="bid-step" style="display:flex; flex-direction:column; gap:16px;">
            <div class="bid-grid-2">
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Statement of Need</label>
                <textarea name="statement_of_need" rows="5" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:10px 12px; font-size:14px;">{{ old('statement_of_need') }}</textarea>
              </div>
              <div>
                <label style="display:block; font-size:12px; color:#4b5563; margin-bottom:4px;">Intended Use &amp; Impact</label>
                <textarea name="intended_use" rows="5" style="width:100%; border-radius:10px; border:1px solid #e5e7eb; padding:10px 12px; font-size:14px;">{{ old('intended_use') }}</textarea>
              </div>
            </div>

            <div style="font-size:12px; color:#4b5563; line-height:1.6;">
              <p style="margin-bottom:8px;">
                I certify that the information provided is true and accurate to the best of my knowledge.
              </p>
              <p style="margin-bottom:8px;">
                I understand that submitting this form does not guarantee receipt of equipment and that all requests are subject to review and the availability of donors.
              </p>
              <p style="margin-bottom:8px;">
                I agree to provide a brief report or testimony on the usage and impact of the equipment if my request is fulfilled.
              </p>
              <label style="display:flex; align-items:center; gap:6px; margin-top:6px;">
                <input type="radio" name="agreed" value="1" {{ old('agreed') ? 'checked' : '' }}>
                <span>I Agree</span>
              </label>
            </div>

            <div class="bid-actions-row" style="margin-top:16px; display:flex; gap:12px;">
              <button type="button" id="bid-back-3" style="border:none; outline:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:10px 24px; border-radius:999px; background:#e5e7eb; color:#111827; font-weight:500; font-size:14px;">
                Back
              </button>
              <button type="submit" id="bid-submit" style="border:none; outline:none; cursor:pointer; display:inline-flex; align-items:center; justify-content:center; padding:10px 40px; border-radius:999px; background:#2563eb; color:#fff; font-weight:500; font-size:14px;">
                Submit Request
              </button>
            </div>
          </div>
        </div>
      </form>
    </div>

    @if(session('bid_success') && session('bid_request_code'))
      <div style="position:fixed; inset:0; background:rgba(15,23,42,0.45); display:flex; align-items:center; justify-content:center; z-index:50;">
        <div style="background:#fff; border-radius:24px; padding:28px 32px; max-width:480px; width:100%; box-shadow:0 25px 50px -12px rgba(0,0,0,0.25); text-align:center;">
          <div style="margin-bottom:16px; font-size:32px;">✅</div>
          <h3 style="margin:0 0 8px; font-size:20px; font-weight:600;">Thank You For Your Request!</h3>
          <p style="margin:0 0 12px; font-size:13px; color:#4b5563;">
            Your request ID is:
          </p>
          <div style="display:flex; align-items:center; justify-content:center; gap:8px; margin-bottom:16px;">
            <span style="font-weight:600; letter-spacing:0.05em;">{{ session('bid_request_code') }}</span>
          </div>
          <p style="margin:0 0 20px; font-size:12px; color:#6b7280;">
            Our MBI team will review your application for verification. This may take 7–14 business days.
            If your request is approved, we will actively search for a matching donor and contact you when a potential match is found.
          </p>
          <div style="display:flex; flex-direction:column; gap:8px;">
            <a href="{{ route('marketplace') }}" style="text-decoration:none; display:inline-flex; align-items:center; justify-content:center; padding:10px 24px; border-radius:999px; background:#2563eb; color:#fff; font-weight:500; font-size:14px;">
              Return to Donation Hub
            </a>
          </div>
        </div>
      </div>
    @endif
  </div>

  <script>
    (function(){
      var s1 = document.getElementById('bid-step-1');
      var s2 = document.getElementById('bid-step-2');
      var s3 = document.getElementById('bid-step-3');
      var n1 = document.getElementById('bid-next-1');
      var n2 = document.getElementById('bid-next-2');
      var b2 = document.getElementById('bid-back-2');
      var b3 = document.getElementById('bid-back-3');
      var submit = document.getElementById('bid-submit');
      var form = document.getElementById('bidForm');

      function show(step){
        if(!s1 || !s2 || !s3) return;
        s1.style.display = step === 1 ? 'block' : 'none';
        s2.style.display = step === 2 ? 'block' : 'none';
        s3.style.display = step === 3 ? 'block' : 'none';
      }

      show(1);

      if(n1){
        n1.addEventListener('click', function(){
          show(2);
        });
      }
      if(n2){
        n2.addEventListener('click', function(){
          show(3);
        });
      }
      if(b2){
        b2.addEventListener('click', function(){
          show(1);
        });
      }
      if(b3){
        b3.addEventListener('click', function(){
          show(2);
        });
      }
      if(form && submit){
        form.addEventListener('submit', function(){
          submit.disabled = true;
          submit.setAttribute('aria-busy', 'true');
        });
      }
    })();
  </script>
@endsection
