<?php

namespace Tests\API\V1\Categories;

use Tests\TestCase;

class CategoriesTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate:refresh');
    }
    public function test_ensure_we_can_create_a_new_category()
    {
        $newCategory = [
            'name' => 'category 1',
            'slug' => 'category-1',
        ];
        $response = $this->call('post', 'api/v1/categories', $newCategory);
        $this->assertEquals(201, $response->getStatusCode());
        $this->seeInDatabase('categories', $newCategory);
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'name',
                'slug',
            ],
        ]);
    }
}
