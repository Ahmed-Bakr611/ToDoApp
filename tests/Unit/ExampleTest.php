<?php

test('that true is true', function () {
    expect(true)->toBeTrue();
});

// <?php

// namespace Tests\Feature\Http;

// use App\Enums\PermissionEnum;
// use App\Models\Category;
// use Illuminate\Foundation\Testing\RefreshDatabase;
// use PHPUnit\Framework\Attributes\Test;
// use Tests\SetupsCatalogSystem;
// use Tests\TestCase;

// class CategoryControllerTest extends TestCase
// {
//     use RefreshDatabase, SetupsCatalogSystem;

//     protected function setUp(): void
//     {
//         parent::setUp();
//         $this->setupCatalogSystem();
//     }

//     #[Test]
//     public function it_can_admin_access_categories_index()
//     {
//         $this->actingAs($this->superAdminUser)
//             ->get(route('categories.index'))
//             ->assertOk()
//             ->assertViewIs('pages.apps.category.list');
//     }

//     #[Test]
//     public function test_non_authorized_user_cant_view_index_page()
//     {
//         $user = $this->createStaffWithoutPermission();
//         $this->actingAs($user)
//             ->get(route('categories.index'))
//             ->assertForbidden();
//     }

//     #[Test]
//     public function it_returns_datatable_data()
//     {
//         $user = $this->createStaffWithPermission([PermissionEnum::CATEGORY_LIST->value]);
//         Category::factory()->count(5)->create();

//         $this->actingAs($user)
//             ->getJson(
//                 route('categories.index'),
//                 ['HTTP_X-Requested-With' => 'XMLHttpRequest']
//             )->assertOk()
//             ->assertJsonStructure([
//                 'draw',
//                 'recordsTotal',
//                 'recordsFiltered',
//                 'data',
//             ]);
//     }

//     #[Test]
//     public function it_returns_selected_category_data()
//     {
//         $user = $this->createStaffWithPermission([PermissionEnum::CATEGORY_LIST->value]);
//         $categories = Category::factory()->count(10)->create();
//         $selectedCategory = $categories->random();

//         $this->actingAs($user)
//             ->getJson(
//                 route('categories.index', [
//                     'filters' => ['id' => [$selectedCategory->id]],
//                 ]),
//                 ['HTTP_X-Requested-With' => 'XMLHttpRequest']
//             )->assertOk()
//             ->assertJsonStructure([
//                 'draw',
//                 'recordsTotal',
//                 'recordsFiltered',
//                 'data',
//             ])
//             ->assertJsonCount(1, 'data')
//             ->assertJsonFragment(['id' => $selectedCategory->id]);
//     }
// }