<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;

class UsersController extends Controller
{
    public function __construct(private UserRepositoryInterface $userRepository)
    {
    }

    public function store() {
        $this->userRepository->create([
            'full_name' => 'Meysam Masoumi',
            'email' => 'meysam.masoomy@gmail.com',
            'mobile' => '09031119856',
            'password' => '123456',
        ]);
    return response()->json([
            'success' => true,
            'message' => 'کاربر با موفقیت ایجاد شد',
            'data' => [
                'full_name' => 'Meysam Masoumi',
                'email' => 'meysam.masoomy@gmail.com',
                'mobile' => '09031119856',
                'password' => '123456',
            ],
        ]
    )->setStatusCode(201);
}

}
