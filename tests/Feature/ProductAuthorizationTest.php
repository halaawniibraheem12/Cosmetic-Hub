<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    private function seedBasics(): array
    {
        $category = Category::create(['name' => 'Makeup']);

        $supplier = Supplier::create([
            'name'  => 'Supplier A',
            'email' => 'supplierA@example.com',
        ]);

        return [$category, $supplier];
    }

    private function validProductPayload(Category $category, Supplier $supplier, array $overrides = []): array
    {
        $base = [
            'name' => 'Test Product',
            'price' => 99.99,
            'category_id' => $category->id,
            'suppliers' => [
                $supplier->id => [
                    'selected' => '1',
                    'cost_price' => 10.50,
                    'lead_time_days' => 7,
                ],
            ],
        ];

        return array_merge($base, $overrides);
    }

    private function createProductFor(User $owner, Category $category, Supplier $supplier): Product
    {
        $product = Product::create([
            'name' => 'Owned Product',
            'price' => 50.00,
            'category_id' => $category->id,
            'user_id' => $owner->id,
        ]);

        $product->suppliers()->sync([
            $supplier->id => [
                'cost_price' => 12.25,
                'lead_time_days' => 5,
            ],
        ]);

        return $product;
    }

    public function test_guest_users_cannot_access_protected_product_routes(): void
    {
        [$category, $supplier] = $this->seedBasics();

        $owner = User::factory()->create();
        $product = $this->createProductFor($owner, $category, $supplier);

        $this->get(route('products.create'))->assertRedirect(route('login'));
        $this->post(route('products.store'), $this->validProductPayload($category, $supplier))
            ->assertRedirect(route('login'));

        $this->get(route('products.edit', $product))->assertRedirect(route('login'));

        $this->put(route('products.update', $product), $this->validProductPayload($category, $supplier, [
            'name' => 'Updated Name',
        ]))->assertRedirect(route('login'));

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('login'));
    }

    public function test_logged_in_user_cannot_update_or_delete_products_they_do_not_own(): void
    {
        [$category, $supplier] = $this->seedBasics();

        $owner = User::factory()->create();
        $otherUser = User::factory()->create();

        $product = $this->createProductFor($owner, $category, $supplier);

        $this->actingAs($otherUser);

        $this->get(route('products.edit', $product))->assertForbidden();

        $this->put(route('products.update', $product), $this->validProductPayload($category, $supplier, [
            'name' => 'Hacked Name',
        ]))->assertForbidden();

        $this->delete(route('products.destroy', $product))->assertForbidden();
    }

    public function test_logged_in_owner_can_update_their_own_product(): void
    {
        [$category, $supplier] = $this->seedBasics();

        $owner = User::factory()->create();
        $product = $this->createProductFor($owner, $category, $supplier);

        $this->actingAs($owner);

        $this->get(route('products.edit', $product))->assertOk();

        $this->put(route('products.update', $product), $this->validProductPayload($category, $supplier, [
            'name' => 'Owner Updated Name',
            'price' => 120.00,
        ]))->assertRedirect(route('products.index'));

        $product->refresh();

        $this->assertEquals('Owner Updated Name', $product->name);
        $this->assertEquals(120.00, (float) $product->price);
    }

    public function test_logged_in_owner_can_delete_their_own_product(): void
    {
        [$category, $supplier] = $this->seedBasics();

        $owner = User::factory()->create();
        $product = $this->createProductFor($owner, $category, $supplier);

        $this->actingAs($owner);

        $this->delete(route('products.destroy', $product))
            ->assertRedirect(route('products.index'));

        $this->assertDatabaseMissing('products', [
            'id' => $product->id,
        ]);
    }
}