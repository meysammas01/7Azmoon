<?php

namespace Tests\API\V1\Users;

use Tests\TestCase;

class UsersTest extends TestCase
{
    public function test_should_create_a_new_user() {
       $response = $this->call('POST', 'api/v1/users', [
            'full_name' => 'Meysam Maoumi',
            'email' => 'meysam.masoomy@gmail.com',
            'mobile' => '09031119856',
            'password' => '123456'
        ]);
        $this->assertEquals(201, $response->status());
        $this->seeJsonStructure([
            'success',
            'message',
            'data' => [
                'full_name',
                'email',
                'mobile',
                'password',
            ],
        ]);
    }
    public function test_it_must_throw_a_exception_if_we_dont_send_parameters()
    {
        $response = $this->call('POST', 'api/v1/users', []);
        $this->assertEquals(422, $response->status());
    }
}
