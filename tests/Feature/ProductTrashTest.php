<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProductTrashTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(bool $isAdmin = false, string $email = null): User
    {
        return User::create([
            'name' => $isAdmin ? 'Admin User' : 'Normal User',
            'email' => $email ?? ($isAdmin ? 'admin@test.com' : 'user@test.com'),
            'password' => Hash::make('password123'),
            'is_admin' => $isAdmin,
        ]);
    }

    private function makeCategory(User $owner): Category
    {
        return Category::create([
            'name' => 'Skincare',
            'user_id' => $owner->id,
        ]);
    }

    private function makeProduct(User $owner, Category $category): Product
    {
        return Product::create([
            'name' => 'Argan Oil Hair Serum',
            'price' => 25.50,
            'category_id' => $category->id,
            'user_id' => $owner->id,
            'image_path' => null,
        ]);
    }

    public function test_soft_delete_hides_product_from_index_and_shows_it_in_trash_and_restore_works(): void
    {
        $owner = $this->makeUser(false, 'owner@test.com');
        $category = $this->makeCategory($owner);
        $product = $this->makeProduct($owner, $category);

        $this->actingAs($owner);

        $deleteResponse = $this->delete(route('products.destroy', $product));
        $deleteResponse->assertRedirect(route('products.index'));

        $this->assertSoftDeleted('products', ['id' => $product->id]);

        $indexResponse = $this->get(route('products.index'));
        $indexResponse->assertOk();
        $indexResponse->assertDontSee($product->name);

        $trashResponse = $this->get(route('products.trash'));
        $trashResponse->assertOk();
        $trashResponse->assertSee($product->name);

        $restoreResponse = $this->post(route('products.restore', $product->id));
        $restoreResponse->assertRedirect(route('products.trash'));

        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'deleted_at' => null,
        ]);

        $indexAfterRestore = $this->get(route('products.index'));
        $indexAfterRestore->assertOk();
        $indexAfterRestore->assertSee($product->name);
    }

    public function test_non_owner_cannot_restore_or_force_delete(): void
    {
        $owner = $this->makeUser(false, 'owner@test.com');
        $other = $this->makeUser(false, 'other@test.com');

        $category = $this->makeCategory($owner);
        $product = $this->makeProduct($owner, $category);

        $product->delete();

        $this->actingAs($other);

        $restoreResponse = $this->post(route('products.restore', $product->id));
        $restoreResponse->assertForbidden();

        $forceResponse = $this->delete(route('products.forceDelete', $product->id));
        $forceResponse->assertForbidden();
    }
}