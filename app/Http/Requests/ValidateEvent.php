<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ValidateEvent extends FormRequest
{
    protected $validationErrors = null;

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string',
            'date' => 'required|after:yesterday',
            'location' => 'required|string',
            'image' => 'mimes:jpg,jpeg,png,bmp,tiff',
            'type' => 'required|string',
            'description' => 'required|string',
            'checkbox' => 'sometimes',
            'users' => 'sometimes|array',
        ];
    }
}
