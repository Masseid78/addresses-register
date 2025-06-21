<?php

namespace Tests\Unit\Requests;

use Tests\TestCase;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Support\Facades\Validator;

class StoreUserRequestTest extends TestCase
{
    public function test_validation_passes_with_valid_data()
    {
        $data = [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'zipcode' => '12345-678',
            'number' => '123',
        ];

        $request = new StoreUserRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertFalse($validator->fails());
    }

    public function test_validation_fails_with_invalid_data()
    {
        $data = [
            'name' => '',
            'email' => 'not-an-email',
            'zipcode' => '123',
            'number' => '123',
        ];

        $request = new StoreUserRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $errors = $validator->errors();
        $this->assertTrue($errors->has('name'));
        $this->assertTrue($errors->has('email'));
        $this->assertTrue($errors->has('zipcode'));
    }
} 