<?php

namespace Modules\Balance\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

class BalanceRequest extends FormRequest
{
    use HttpResponse;
    public function rules(): array
    {
        return [
            'points' => 'required|numeric|gt:0'
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
        return $this->throwValidationException($validator);
    }
}
