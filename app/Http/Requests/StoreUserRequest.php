<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email',
            'zipcode'    => 'required|string|size:9',
            'number'     => 'required|string|max:20',
            'complement' => 'nullable|string|max:255',
            'street'     => 'required|string|max:255',
            'district'   => 'required|string|max:255',
            'city'       => 'required|string|max:255',
            'state'      => 'required|string|size:2',
        ];
    }
}
