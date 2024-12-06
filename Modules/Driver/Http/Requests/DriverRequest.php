<?php

namespace Modules\Driver\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class DriverRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;
    public function rules(): array
    {
        return [
        'full_name'        => 'required|string|max:255',
        'email'            => 'required|email|max:255|unique:drivers',
        'phone'            => 'required|string|max:20|unique:drivers',
        'national_id'      => 'required|string|max:20|unique:drivers',
        'Driver_license'   => 'required|string|max:20|unique:drivers',
        'photo'            => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
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
