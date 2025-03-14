<?php

namespace Modules\Place\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class ZoneRequest extends FormRequest
{
    use HttpResponse;
    
    public function rules(): array
    {
        return [
            'name' => 'required|unique:zones,name',
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
