<?php
// app/Http/Requests/Auth/UpdateProfileRequest.php
namespace App\Http\Requests\Auth;


use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function rules()
    {
        return [
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'avatar' => 'sometimes|image|max:5120', // 5MB max
            'password' => 'sometimes|string|min:6|confirmed',
        ];
    }
}
