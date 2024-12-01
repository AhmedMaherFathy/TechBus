<?php

namespace Modules\Auth\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class forgetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;

    public function rules(): array
    {
        return [
            'email' => 'required|email',
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
