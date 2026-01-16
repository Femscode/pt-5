@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/marketplace.css') }}">
@endsection

@section('page_title')
@php
  $mode = $mode ?? 'create';
  $isEdit = $mode === 'edit';
  $product = $product ?? null;
  $defaultType = $product->product_type ?? 'sale';
@endphp
{{ $isEdit ? 'Edit Product' : 'Add Product' }}
@endsection

@section('content')
  <div class="mp-page">
    <div class="mp-tabs">
      <a href="{{ route('marketplace') }}" class="tab">Paid Products</a>
      <a href="{{ route('marketplace') }}" class="tab">Donation Hub</a>
      <a href="{{ route('marketplace.my_products') }}" class="tab">My Products</a>
    </div>

    @if ($errors->any())
      <div style="margin-top:16px; margin-bottom: 16px; padding: 10px 12px; border-radius: 8px; background:#fef2f2; color:#991b1b;">
        <ul style="margin:0; padding-left:18px;">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <div class="mp-toolbar" style="margin-bottom:24px; margin-top:12px;">
      <div>
        <h2 style="margin:0; font-size:18px; font-weight:600;">{{ $isEdit ? 'Edit Product' : 'Add New Product' }}</h2>
        <p style="margin:4px 0 0; font-size:15px; color:#6b7280;">Provide equipment details, logistics, and donor preferences.</p>
      </div>
      <a href="{{ route('marketplace.my_products') }}" class="btn btn-outline btn-primary">Cancel</a>
    </div>

    @php
      $action = $isEdit ? route('marketplace.products.update', $product) : route('marketplace.products.store');
      $method = $isEdit ? 'PUT' : 'POST';
      $conditionValue = old('condition', $product->condition ?? '');
      $knownIssuesChecked = old('known_issues', $product && $product->known_issues ? '1' : '') === '1';
    @endphp

    <div style="background:#fff; border:1px solid var(--border); border-radius:16px; padding:16px; margin-top:16px;">
      <form method="POST" action="{{ $action }}" enctype="multipart/form-data" id="productForm">
        @csrf
        @if($isEdit)
          @method('PUT')
        @endif

        <input type="hidden" name="product_type" value="{{ old('product_type', $defaultType) }}">

        <div style="display:flex; flex-direction:column; gap:10px;">
          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Price (£)</label>
            <input type="number" step="0.01" min="0" name="price" value="{{ old('price', $product->price ?? '') }}" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Category</label>
            <input type="text" name="category" value="{{ old('category', $product->category ?? '') }}" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Manufacturer</label>
            <input type="text" name="manufacturer" value="{{ old('manufacturer', $product->manufacturer ?? '') }}" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Model number</label>
            <input type="text" name="model_number" value="{{ old('model_number', $product->model_number ?? '') }}" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Condition</label>
            <select name="condition" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
              <option value="">Select condition</option>
              <option value="new" {{ $conditionValue === 'new' ? 'selected' : '' }}>New</option>
              <option value="used-good" {{ $conditionValue === 'used-good' ? 'selected' : '' }}>Used - Good</option>
              <option value="used-fair" {{ $conditionValue === 'used-fair' ? 'selected' : '' }}>Used - Fair</option>
              <option value="refurbished" {{ $conditionValue === 'refurbished' ? 'selected' : '' }}>Refurbished</option>
            </select>
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Equipment Location</label>
            <input type="text" name="equipment_location" value="{{ old('equipment_location', $product->equipment_location ?? '') }}" placeholder="City, Country" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div style="display:flex; align-items:center; gap:8px;">
            <input type="checkbox" id="known_issues" name="known_issues" value="1" {{ $knownIssuesChecked ? 'checked' : '' }} style="width:auto;">
            <label for="known_issues" style="font-size:15px; color:#4b5563;">This equipment has known issues</label>
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Known Issues Details</label>
            <textarea name="known_issues_details" rows="3" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">{{ old('known_issues_details', $product->known_issues_details ?? '') }}</textarea>
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Product URL</label>
            <input type="url" name="url" value="{{ old('url', $product->url ?? '') }}" placeholder="https://example.com/product-page" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:8px 10px; font-size:15px;">
          </div>

          <div>
            <label style="display:block; font-size:14px; color:#4b5563; margin-bottom:4px;">Upload Photos</label>
            <input type="file" name="photos[]" multiple accept="image/*" style="width:100%; border:1px solid var(--border); border-radius:10px; padding:6px 8px; font-size:15px;">
          </div>

          <div style="display:flex; gap:8px; margin-top:16px; justify-content:flex-end;">
            <button type="submit" class="btn btn-primary" id="ap-submit">{{ $isEdit ? 'Save Changes' : 'Submit Product' }}</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script>
    (function(){
      var form = document.getElementById('productForm');
      var submitBtn = document.getElementById('ap-submit');

      if(form && submitBtn){
        form.addEventListener('submit', function(){
          submitBtn.disabled = true;
          submitBtn.setAttribute('aria-busy', 'true');
        });
      }
    })();
  </script>
@endsection
