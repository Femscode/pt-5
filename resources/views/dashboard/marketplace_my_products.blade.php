@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/marketplace.css') }}">
@endsection

@section('page_title')
My Products
@endsection

@section('content')
  <div class="mp-page">
    <div class="mp-tabs">
      <a href="{{ route('marketplace') }}" class="tab">Paid Products</a>
      <a href="{{ route('marketplace') }}" class="tab">Donation Hub</a>
      <button class="tab active" type="button">My Products</button>
    </div>

    @if (session('status'))
      <div style="margin-bottom: 16px; padding: 10px 12px; border-radius: 8px; background:#ecfdf5; color:#166534;">
        {{ session('status') }}
      </div>
    @endif

    <div class="mp-toolbar" style="margin-bottom:24px;">
      <div>
        <h2 style="margin:0; font-size:18px; font-weight:600;">Manage your products</h2>
        <p style="margin:4px 0 0; font-size:13px; color:#6b7280;">Create, update, or remove items you listed on the marketplace.</p>
      </div>
      <div style="display:flex; gap:8px;">
        <a href="{{ route('marketplace') }}" class="btn btn-outline btn-primary">Back to Marketplace</a>
        <a href="{{ route('marketplace.products.create') }}" class="btn btn-primary">Add Product</a>
      </div>
    </div>

    @if($products->isEmpty())
      <div class="empty-state">You have not added any products yet.</div>
    @else
      <div class="mp-grid">
        @foreach($products as $p)
          @php
            $img = ($p->images ?? collect())->sortBy('sort_order')->first();
            $thumb = $img->image_url ?? ((is_array($p->photos) && count($p->photos) > 0) ? $p->photos[0] : null);
            $price = $p->price ? (float) $p->price : 0;
            $loc = $p->equipment_location ?? '';
          @endphp
          <div class="mp-card">
            <div class="card-media-wrap">
              @if($thumb)
                <img class="card-media" src="https://admin.mybridgeinternational.org/mbi-admin-files/public/{{ $thumb }}" alt="{{ $p->name }}">
              @else
                <div class="card-media placeholder"></div>
              @endif
              @if(!empty($p->product_type))
                <span class="type-badge {{ strtolower($p->product_type) === 'donation' ? 'donation' : 'sale' }}">{{ strtolower($p->product_type) === 'donation' ? 'donation' : 'sale' }}</span>
              @endif
            </div>
            <div class="card-body">
              <div class="title">{{ $p->name }}</div>
              <div class="price">
                @if(strtolower($p->product_type) === 'donation')
                  Free
                @else
                  £{{ number_format($price, 2) }}
                @endif
              </div>
              <ul class="specs">
                @if(!empty($p->manufacturer))
                  <li>{{ $p->manufacturer }}</li>
                @endif
                @if(!empty($p->model_number))
                  <li>{{ $p->model_number }}</li>
                @endif
                @if(!empty($p->category))
                  <li>{{ $p->category }}</li>
                @endif
                @if(!empty($loc))
                  <li>{{ $loc }}</li>
                @endif
              </ul>
            </div>
            <div class="card-actions">
              <a href="{{ route('marketplace.products.edit', $p) }}" class="btn btn-outline btn-primary">Edit</a>
              <form method="POST" action="{{ route('marketplace.products.destroy', $p) }}" onsubmit="return confirm('Delete this product?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn">Delete</button>
              </form>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>
@endsection
