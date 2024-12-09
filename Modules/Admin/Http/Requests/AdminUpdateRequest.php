<?php

namespace Modules\Admin\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class AdminUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;
    public function rules(): array
    {
        // info($this->route('admin')); die;

        return [
            'first_name' => 'string|sometimes|max:255',
            'last_name' => 'string|sometimes|max:255',
            'email' => 'email|sometimes|unique:admins,email,'.$this->route('admin'),
            'phone' => 'string|sometimes|unique:admins,phone,'.$this->route('admin'),
            'password' => 'string|nullable|min:8',
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
