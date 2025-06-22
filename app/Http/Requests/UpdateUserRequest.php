<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $id = $this->route('user')->id;
        return [
            'name'       => 'sometimes|string|max:255',
            'email'      => ['sometimes','email',Rule::unique('users','email')->ignore($id)],
            'zipcode'    => 'sometimes|string|size:9',
            'number'     => 'nullable|string|max:20',
            'complement' => 'nullable|string|max:255',
            'street'     => 'sometimes|string|max:255',
            'district'   => 'sometimes|string|max:255',
            'city'       => 'sometimes|string|max:255',
            'state'      => 'sometimes|string|size:2',
        ];
    }
}
