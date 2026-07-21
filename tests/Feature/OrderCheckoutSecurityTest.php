<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Customer;
use App\Models\Product;
use App\Models\PaymentGateway;

use App\Models\Category;
use App\Models\Brand;

class OrderCheckoutSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        PaymentGateway::create([
            'gateway_name' => 'cashfree',
            'display_name' => 'Cashfree',
            'api_key' => 'test',
            'secret_key' => 'test',
            'environment' => 'sandbox',
            'is_enabled' => true,
        ]);
        
        Category::firstOrCreate(['id' => 1], ['name' => 'Test Category', 'slug' => 'test-category']);
        Brand::firstOrCreate(['id' => 1], ['name' => 'Test Brand', 'slug' => 'test-brand']);
    }

    public function test_tampered_cart_price_is_rejected_at_checkout()
    {
        $customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'phone' => '9876543210',
            'password' => bcrypt('password'),
        ]);

        $category = Category::firstOrCreate(['slug' => 'test-category'], ['name' => 'Test Category']);
        $brand = Brand::firstOrCreate(['slug' => 'test-brand'], ['name' => 'Test Brand']);

        $product = Product::create([
            'name' => 'Test Product',
            'base_price' => 1000,
            'manage_stock' => true,
            'stock_quantity' => 10,
            'is_active' => true,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $tamperedCart = [
            $product->id . ':0' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => 10, 
                'quantity' => 1,
            ]
        ];

        session()->put('cart', $tamperedCart);
        session()->put('checkout_data', [
            'name' => 'John Doe',
            'phone' => '9876543210',
            'email' => 'john@example.com',
            'address' => '123 Street',
            'city' => 'Mumbai',
            'state' => 'Maharashtra',
            'pincode' => '400001',
        ]);

        $response = $this->actingAs($customer, 'customer')
            ->postJson(route('order.cashfree'), [
                'name' => 'John Doe',
                'phone' => '9876543210',
                'email' => 'john@example.com',
                'address' => '123 Street',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
            ]);

        $response->assertStatus(422);
    }

    public function test_stock_reservation_prevents_overselling()
    {
        $customer = Customer::create([
            'name' => 'John Doe',
            'email' => 'john2@example.com',
            'phone' => '9876543211',
            'password' => bcrypt('password'),
        ]);

        $category = Category::firstOrCreate(['slug' => 'test-category'], ['name' => 'Test Category']);
        $brand = Brand::firstOrCreate(['slug' => 'test-brand'], ['name' => 'Test Brand']);

        $product = Product::create([
            'name' => 'Test Product',
            'base_price' => 1000,
            'manage_stock' => true,
            'stock_quantity' => 1,
            'is_active' => true,
            'category_id' => $category->id,
            'brand_id' => $brand->id,
        ]);

        $cart = [
            $product->id . ':0' => [
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => 1000,
                'quantity' => 2, 
            ]
        ];

        session()->put('cart', $cart);

        $response = $this->actingAs($customer, 'customer')
            ->postJson(route('order.cashfree'), [
                'name' => 'John Doe',
                'phone' => '9876543210',
                'address' => '123 Street',
                'city' => 'Mumbai',
                'state' => 'Maharashtra',
                'pincode' => '400001',
            ]);

        $response->assertStatus(422);
        $response->assertJsonFragment([
            'error' => "Sorry! '{$product->name}' does not have enough stock."
        ]);
    }
}
