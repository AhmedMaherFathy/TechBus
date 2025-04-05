<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class DriverForgetPasswordRequest extends FormRequest
{
    use HttpResponse;
    
    public function rules(): array
    {
        return [
            'email' => 'required|email|exists:drivers,email',
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
