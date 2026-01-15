@extends('dashboard.master')
@section('header')
    <link rel="stylesheet" href="{{ url('assets/css/dashboard/marketplace.css') }}">
    <style>
      @media (max-width: 768px) {
        .mp-page .mp-tabs {
          overflow-x: auto;
          padding-bottom: 4px;
          gap: 6px;
        }
        .mp-page .mp-tabs .tab {
          flex: 0 0 auto;
          white-space: nowrap;
          padding: 6px 10px;
          font-size: 12px;
        }
        .mp-toolbar {
          flex-direction: column;
          align-items: stretch;
          gap: 10px;
        }
        .mp-toolbar .mp-search {
          width: 100%;
        }
        .mp-toolbar .mp-search input {
          width: 100%;
        }
        .mp-toolbar .filters {
          width: 100%;
          display: flex;
          gap: 8px;
        }
        .mp-toolbar .filters .filter {
          flex: 1;
        }
        .mp-toolbar .btn.btn-primary {
          width: 100%;
          text-align: center;
        }
      }
    </style>
@endsection

@section('page_title')
Marketplace
@endsection

@section('content')
  @php
    $isSeller = $user->category == 'seller';
  @endphp
  <div class="mp-page">
    <div class="mp-tabs">
      <button class="tab active" data-tab="paid">Paid Products</button>
      <button class="tab" data-tab="donation">Donation Hub</button>
      @if($isSeller)
        <button class="tab" data-tab="my-products" data-my-url="{{ route('marketplace.my_products') }}">My Products</button>
      @endif
      <button class="tab" data-tab="my-biddings" data-my-bid-url="{{ route('marketplace.my_biddings') }}">My Biddings</button>
    </div>

    <div class="mp-toolbar">
      <div class="mp-search" id="mpSearch">
        <svg viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2" class="icon">
          <circle cx="9" cy="9" r="7"/>
          <path d="M14 14l5 5"/>
        </svg>
        <input id="searchInput" type="text" placeholder="Search equipments" />
      </div>
      <div class="filters">
        <select id="filterCategory" class="filter">
          <option value="">Category</option>
          <option value="">Any</option>
          @php
            $cats = [];
            foreach (($products ?? []) as $p) { if (!empty($p->category)) { $cats[$p->category] = $p->category; } }
          @endphp
          @foreach($cats as $c)
            <option value="{{ strtolower($c) }}">{{ $c }}</option>
          @endforeach
        </select>
        <select id="filterPrice" class="filter">
          <option value="">Price Range</option>
          <option value="">Any</option>
          <option value="0-1000">£0 - £1,000</option>
          <option value="1000-5000">£1,000 - £5,000</option>
          <option value="5000-10000">£5,000 - £10,000</option>
          <option value="10000-">£10,000+</option>
        </select>
      </div>
      @if($isSeller)
        <a href="{{ route('marketplace.my_products') }}" class="btn btn-primary">Add Product</a>
      @endif
    </div>

    <div class="mp-grid" id="mpGrid">
      @forelse($products as $p)
        @php
          $img = ($p->images ?? collect())->sortBy('sort_order')->first();
          $thumb = $img->image_url ?? ((is_array($p->photos) && count($p->photos) > 0) ? $p->photos[0] : null);
          $price = $p->price ? (float) $p->price : 0;
          $loc = $p->equipment_location ?? '';
        @endphp
        <div class="mp-card" data-title="{{ strtolower($p->name ?? '') }}" data-category="{{ strtolower($p->category ?? '') }}" data-location="{{ strtolower($loc) }}" data-type="{{ strtolower($p->product_type ?? '') }}" data-price="{{ $price }}">
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
            <div class="price">@if(strtolower($p->product_type) === 'donation') Free @else £{{ number_format($price, 2) }} @endif</div>
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
            </ul>
          </div>
          <div class="card-actions">
            @if(strtolower($p->product_type) !== 'donation')
              <button type="button" class="btn btn-primary buy-btn" data-url="{{ $p->url ?? '' }}">Buy Now</button>
            @else
              <button type="button" class="btn btn-primary bid-btn" data-bid-url="{{ route('marketplace.products.bid', $p) }}">Bid</button>
            @endif
             <a class="btn btn-outline btn-primary" href="{{ route('marketplace.product', $p) }}">See more</a>
          </div>
        </div>
      @empty
        <div class="empty-state">No products available</div>
      @endforelse
    </div>
  </div>

  <script>
    (function(){
      const grid = document.getElementById('mpGrid');
      const cards = Array.from(grid.querySelectorAll('.mp-card'));
      const searchInput = document.getElementById('searchInput');
      const filterCategory = document.getElementById('filterCategory');
      const filterPrice = document.getElementById('filterPrice');
      const tabs = Array.from(document.querySelectorAll('.mp-tabs .tab'));
      const myTab = document.querySelector('.mp-tabs .tab[data-tab="my-products"]');
      const myProductsUrl = myTab ? myTab.dataset.myUrl || '' : '';
      const myBidTab = document.querySelector('.mp-tabs .tab[data-tab="my-biddings"]');
      const myBidsUrl = myBidTab ? myBidTab.dataset.myBidUrl || '' : '';
      const buyButtons = Array.from(document.querySelectorAll('.buy-btn'));
      const bidButtons = Array.from(document.querySelectorAll('.bid-btn'));

      function inRange(val, range){
        if (!range) return true;
        const parts = range.split('-');
        const min = parts[0] ? parseFloat(parts[0]) : null;
        const max = parts[1] ? parseFloat(parts[1]) : null;
        if (parts.length === 2 && parts[1] === '') return min !== null ? val >= min : true;
        let ok = true;
        if (min !== null) ok = ok && val >= min;
        if (max !== null) ok = ok && val <= max;
        return ok;
      }

      function applyFilters(){
        const q = (searchInput.value || '').toLowerCase().trim();
        const cat = (filterCategory.value || '').toLowerCase();
        const pr = filterPrice.value || '';
        cards.forEach(card => {
          const title = card.dataset.title || '';
          const category = card.dataset.category || '';
          const location = card.dataset.location || '';
          const price = parseFloat(card.dataset.price || '0');
          let ok = true;
          if (q) ok = ok && (title.includes(q) || category.includes(q) || location.includes(q));
          if (cat) ok = ok && category === cat;
          if (pr) ok = ok && inRange(price, pr);
          card.style.display = ok ? '' : 'none';
        });
      }

      searchInput.addEventListener('input', applyFilters);
      filterCategory.addEventListener('change', applyFilters);
      filterPrice.addEventListener('change', applyFilters);

      tabs.forEach(tab => {
        tab.addEventListener('click', () => {
          if (tab.dataset.tab === 'my-products' && myProductsUrl) {
            window.location.href = myProductsUrl;
            return;
          }
          if (tab.dataset.tab === 'my-biddings' && myBidsUrl) {
            window.location.href = myBidsUrl;
            return;
          }
          tabs.forEach(t=>t.classList.remove('active'));
          tab.classList.add('active');
          const showDonation = tab.dataset.tab === 'donation';
          cards.forEach(card => {
            const type = (card.dataset.type || '').toLowerCase();
            if (showDonation) {
              card.style.display = type === 'donation' ? '' : 'none';
            } else {
              card.style.display = (type !== 'donation') ? '' : 'none';
            }
          });
        });
      });

      buyButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const url = btn.dataset.url || '';
          if (url) {
            window.location.href = url;
          }
        });
      });
      bidButtons.forEach(btn => {
        btn.addEventListener('click', () => {
          const url = btn.dataset.bidUrl || '';
          if (url) {
            window.location.href = url;
          }
        });
      });

      // default to showing paid products
      (function init(){
        cards.forEach(card => {
          const type = (card.dataset.type || '').toLowerCase();
          card.style.display = (type !== 'donation') ? '' : 'none';
        });
      })();
    })();
  </script>
@endsection
