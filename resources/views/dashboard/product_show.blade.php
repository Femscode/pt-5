@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/product_show.css') }}">
@endsection

@section('page_title')
Marketplace
@endsection

@section('content')
  <div class="product-detail">
    <div class="pd-toolbar">
      <div class="pd-search">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <circle cx="9" cy="9" r="7"/>
          <path d="M14 14l5 5"/>
        </svg>
        <input type="text" placeholder="Search here..." />
      </div>
    </div>
    <div class="pd-grid">
      <div class="media-col">
        <div class="main-media">
          @php $first = ($gallery ?? []); $first = count($first) ? $first[0] : null; @endphp
          @if($first)
            <img id="mainImage" src="https://admin.mybridgeinternational.org/mbi-admin-files/public/{{ $first }}" alt="{{ $product->name }}">
          @else
            <div class="media placeholder"></div>
          @endif
        </div>
        @if(($gallery ?? []) && count($gallery))
          <div class="thumbs" id="thumbs">
            @foreach($gallery as $url)
              <img class="thumb" src="https://admin.mybridgeinternational.org/mbi-admin-files/public/{{ $url }}" alt="Thumb">
            @endforeach
          </div>
        @endif
      </div>

      <div class="info-col">
        <h2 class="title">{{ $product->name }}</h2>
        <div class="price">@if(strtolower($product->product_type) === 'donation') Free @else £{{ number_format((float)$product->price, 2) }} @endif</div>
        <div class="meta">
          @if(!empty($product->manufacturer))<div class="meta-item">Manufacturer: <span>{{ $product->manufacturer }}</span></div>@endif
          @if(!empty($product->model_number))<div class="meta-item">Model: <span>{{ $product->model_number }}</span></div>@endif
          @if(!empty($product->condition))<div class="meta-item">Condition: <span>{{ $product->condition }}</span></div>@endif
          @if(!empty($product->age_of_equipment))<div class="meta-item">Age: <span>{{ $product->age_of_equipment }}</span></div>@endif
          @if(!empty($product->equipment_location))<div class="meta-item">Location: <span>{{ $product->equipment_location }}</span></div>@endif
        </div>
        @if(!empty($product->known_issues))
          <div class="alert">Known issues: {{ $product->known_issues_details }}</div>
        @endif
        <div class="actions">
          @if(strtolower($product->product_type) === 'donation')
            <button class="btn btn-primary">Request</button>
          @else
            <button class="btn btn-primary">Buy Now</button>
          @endif
          @php $discountCode = $product->discount_code ?? 'MBI-BOGOF'; @endphp
          <div class="discount-pill" id="discountPill">
            <span class="code">{{ $discountCode }}</span>
            <button class="copy-btn" type="button" title="Copy">
              <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" width="18" height="18">
                <rect x="9" y="9" width="13" height="13" rx="2" ry="2" />
                <path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1" />
              </svg>
            </button>
          </div>
        </div>

        <h3 class="section-title">Specifications</h3>
        <div class="meta">
          @if(!empty($product->manufacturer))<div class="meta-item">Manufacturer: <span>{{ $product->manufacturer }}</span></div>@endif
          @if(!empty($product->model_number))<div class="meta-item">Model: <span>{{ $product->model_number }}</span></div>@endif
          @if(!empty($product->condition))<div class="meta-item">Condition: <span>{{ $product->condition }}</span></div>@endif
          @if(!empty($product->age_of_equipment))<div class="meta-item">Age: <span>{{ $product->age_of_equipment }}</span></div>@endif
          @if(!empty($product->equipment_location))<div class="meta-item">Location: <span>{{ $product->equipment_location }}</span></div>@endif
          <div class="meta-item">Warranty: <span>Years</span></div>
        </div>
      </div>
    </div>
  </div>

  <script>
    (function(){
      const main = document.getElementById('mainImage');
      const thumbs = document.getElementById('thumbs');
      if (main && thumbs) {
        thumbs.addEventListener('click', (e)=>{
          const img = e.target.closest('.thumb');
          if (!img) return;
          main.src = img.src;
        });
      }
      const pill = document.getElementById('discountPill');
      if (pill) {
        pill.querySelector('.copy-btn')?.addEventListener('click', ()=>{
          const code = pill.querySelector('.code')?.textContent || '';
          if (code) navigator.clipboard?.writeText(code);
        });
      }
    })();
  </script>
@endsection
