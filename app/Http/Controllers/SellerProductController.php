<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductBidding;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SellerProductController extends Controller
{
    protected function ensureSeller()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        $role = $user->category ?? '';
        if ($role !== 'seller') {
            abort(403);
        }
        return $user;
    }

    protected function assertOwner(Product $product)
    {
        $user = Auth::user();
        if (!$user || (int) $product->created_by !== (int) $user->id) {
            abort(403);
        }
        return $product;
    }

    public function create()
    {
        $user = $this->ensureSeller();
        $product = new Product();
        $product->product_type = 'product';

        return view('dashboard.marketplace_product_form', [
            'user' => $user,
            'product' => $product,
            'mode' => 'create',
        ]);
    }

    public function index()
    {
        $user = $this->ensureSeller();

        $products = Product::with('images')
            ->where('created_by', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.marketplace_my_products', [
            'user' => $user,
            'products' => $products,
        ]);
    }

    public function edit(Product $product)
    {
        $user = $this->ensureSeller();
        $product = $this->assertOwner($product);

        return view('dashboard.marketplace_product_form', [
            'user' => $user,
            'product' => $product,
            'mode' => 'edit',
        ]);
    }

    public function store(Request $request)
    {
        $user = $this->ensureSeller();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model_number' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'age_of_equipment' => ['nullable', 'string', 'max:255'],
            'last_serviced_date' => ['nullable', 'date'],
            'known_issues' => ['nullable', 'boolean'],
            'known_issues_details' => ['nullable', 'string'],
            'accessories' => ['nullable', 'string'],
            'pickup_available_date' => ['nullable', 'date'],
            'equipment_location' => ['nullable', 'string', 'max:255'],
            'shipping_cost_contribution' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'donor_type' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($data['product_type'] === 'sale' && (!isset($data['price']) || $data['price'] === null)) {
            return back()->withErrors(['price' => 'Price is required for paid products'])->withInput();
        }

        $product = new Product();
        $product->name = $data['name'];
        $product->product_type = $data['product_type'];
        $product->price = $data['price'] ?? 0;
        $product->category = $data['category'] ?? null;
        $product->manufacturer = $data['manufacturer'] ?? null;
        $product->model_number = $data['model_number'] ?? null;
        $product->condition = $data['condition'] ?? null;
        $product->age_of_equipment = $data['age_of_equipment'] ?? null;
        $product->last_serviced_date = $data['last_serviced_date'] ?? null;
        $product->known_issues = isset($data['known_issues']) ? (bool)$data['known_issues'] : false;
        $product->known_issues_details = $data['known_issues_details'] ?? null;
        $product->accessories = $data['accessories'] ?? null;
        $product->pickup_available_date = $data['pickup_available_date'] ?? null;
        $product->equipment_location = $data['equipment_location'] ?? null;
        $product->shipping_cost_contribution = $data['shipping_cost_contribution'] ?? null;
        $product->address = $data['address'] ?? null;
        $product->donor_type = $data['donor_type'] ?? null;
        $product->organization_name = $data['organization_name'] ?? null;
        $product->contact_person = $data['contact_person'] ?? null;
        $product->contact_email = $data['contact_email'] ?? null;
        $product->phone_number = $data['phone_number'] ?? null;
        $product->url = $data['url'] ?? null;
        $product->created_by = $user->id;
        $product->save();
        if ($request->hasFile('photos')) {
            $dir = public_path('product_images');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            foreach ($request->file('photos') as $idx => $file) {
                $name = time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $name);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => '/product_images/' . $name,
                    'sort_order' => $idx,
                ]);
            }
        }


        return redirect()->route('marketplace.my_products')->with('status', 'Product created successfully');
    }

    public function update(Request $request, Product $product)
    {
        $user = $this->ensureSeller();
        $product = $this->assertOwner($product);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'product_type' => ['required', 'string'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'category' => ['nullable', 'string', 'max:255'],
            'manufacturer' => ['nullable', 'string', 'max:255'],
            'model_number' => ['nullable', 'string', 'max:255'],
            'condition' => ['nullable', 'string', 'max:255'],
            'age_of_equipment' => ['nullable', 'string', 'max:255'],
            'last_serviced_date' => ['nullable', 'date'],
            'known_issues' => ['nullable', 'boolean'],
            'known_issues_details' => ['nullable', 'string'],
            'accessories' => ['nullable', 'string'],
            'pickup_available_date' => ['nullable', 'date'],
            'equipment_location' => ['nullable', 'string', 'max:255'],
            'shipping_cost_contribution' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'donor_type' => ['nullable', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'contact_person' => ['nullable', 'string', 'max:255'],
            'contact_email' => ['nullable', 'email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url', 'max:2048'],
            'photos.*' => ['nullable', 'image', 'max:4096'],
        ]);

        if ($data['product_type'] === 'sale' && (!isset($data['price']) || $data['price'] === null)) {
            return back()->withErrors(['price' => 'Price is required for paid products'])->withInput();
        }

        $product->name = $data['name'];
        $product->product_type = $data['product_type'];
        $product->price = $data['price'] ?? 0;
        $product->category = $data['category'] ?? null;
        $product->manufacturer = $data['manufacturer'] ?? null;
        $product->model_number = $data['model_number'] ?? null;
        $product->condition = $data['condition'] ?? null;
        $product->age_of_equipment = $data['age_of_equipment'] ?? null;
        $product->last_serviced_date = $data['last_serviced_date'] ?? null;
        $product->known_issues = isset($data['known_issues']) ? (bool)$data['known_issues'] : false;
        $product->known_issues_details = $data['known_issues_details'] ?? null;
        $product->accessories = $data['accessories'] ?? null;
        $product->pickup_available_date = $data['pickup_available_date'] ?? null;
        $product->equipment_location = $data['equipment_location'] ?? null;
        $product->shipping_cost_contribution = $data['shipping_cost_contribution'] ?? null;
        $product->address = $data['address'] ?? null;
        $product->donor_type = $data['donor_type'] ?? null;
        $product->organization_name = $data['organization_name'] ?? null;
        $product->contact_person = $data['contact_person'] ?? null;
        $product->contact_email = $data['contact_email'] ?? null;
        $product->phone_number = $data['phone_number'] ?? null;
        $product->url = $data['url'] ?? null;

        if ($request->hasFile('photos')) {
            $dir = public_path('product_images');
            if (!is_dir($dir)) {
                @mkdir($dir, 0755, true);
            }
            $existingCount = $product->images()->count();
            foreach ($request->file('photos') as $idx => $file) {
                $name = time() . '_' . \Illuminate\Support\Str::random(8) . '.' . $file->getClientOriginalExtension();
                $file->move($dir, $name);
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => '/product_images/' . $name,
                    'sort_order' => $existingCount + $idx,
                ]);
            }
        }
        $product->save();

        return redirect()->route('marketplace.my_products')->with('status', 'Product updated successfully');
    }

    public function destroy(Product $product)
    {
        $user = $this->ensureSeller();
        $product = $this->assertOwner($product);

        $product->delete();

        return redirect()->route('marketplace.my_products')->with('status', 'Product deleted successfully');
    }

    public function showBidForm(Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if (strtolower($product->product_type ?? '') !== 'donation') {
            abort(404);
        }

        return view('dashboard.marketplace_bid_form', [
            'user' => $user,
            'product' => $product,
        ]);
    }

    public function submitBid(Request $request, Product $product)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if (strtolower($product->product_type ?? '') !== 'donation') {
            abort(404);
        }

        $data = $request->validate([
            'applicant_type' => ['required', 'string', 'max:255'],
            'organization_name' => ['nullable', 'string', 'max:255'],
            'organization_website' => ['nullable', 'string', 'max:255'],
            'facility_address' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'equipment_name' => ['required', 'string', 'max:255'],
            'urgency' => ['required', 'string', 'max:255'],
            'preferred_manufacturer' => ['nullable', 'string', 'max:255'],
            'quantity' => ['required', 'integer', 'min:1'],
            'can_contribute' => ['required', 'string', 'max:255'],
            'budget' => ['nullable', 'string', 'max:255'],
            'statement_of_need' => ['required', 'string'],
            'intended_use' => ['required', 'string'],
            'agreed' => ['accepted'],
        ]);

        $code = 'REQ-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(3)));

        $bid = new ProductBidding();
        $bid->product_id = $product->id;
        $bid->user_id = $user->id;
        $bid->request_code = $code;
        $bid->applicant_type = $data['applicant_type'];
        $bid->organization_name = $data['organization_name'] ?? null;
        $bid->organization_website = $data['organization_website'] ?? null;
        $bid->facility_address = $data['facility_address'];
        $bid->email = $data['email'];
        $bid->phone = $data['phone'];
        $bid->contact_person = $data['contact_person'];
        $bid->equipment_name = $data['equipment_name'];
        $bid->urgency = $data['urgency'];
        $bid->preferred_manufacturer = $data['preferred_manufacturer'] ?? null;
        $bid->quantity = $data['quantity'];
        $bid->can_contribute = $data['can_contribute'];
        $bid->budget = $data['budget'] ?? null;
        $bid->statement_of_need = $data['statement_of_need'];
        $bid->intended_use = $data['intended_use'];
        $bid->agreed = true;
        $bid->status = 'pending';
        $bid->save();

        return redirect()
            ->route('marketplace.products.bid', $product)
            ->with('bid_success', true)
            ->with('bid_request_code', $code);
    }

    public function myBiddings()
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }

        $biddings = ProductBidding::with('product')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.marketplace_my_biddings', [
            'user' => $user,
            'biddings' => $biddings,
        ]);
    }

    public function showBid(ProductBidding $bid)
    {
        $user = Auth::user();
        if (!$user) {
            abort(403);
        }
        if ((int) $bid->user_id !== (int) $user->id) {
            abort(404);
        }

        $bid->load('product');

        return view('dashboard.marketplace_bidding_show', [
            'user' => $user,
            'bid' => $bid,
        ]);
    }
}
