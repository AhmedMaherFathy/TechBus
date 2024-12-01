<?php

namespace Modules\Admin\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class AdminRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;

    public function rules(): array
    {
        return [
            'first_name' => 'string|required|max:255',
            'last_name' => 'string|required|max:255',
            'email' => 'email|unique:admins,email|required',
            'phone' => 'string|unique:admins,phone|required',
            'password' => 'string|required|min:8',
            'password_confirmation' => 'string|required_with:password|min:8',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
