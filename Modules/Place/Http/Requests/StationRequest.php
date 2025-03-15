<?php

namespace Modules\Place\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;


class StationRequest extends FormRequest
{
    use HttpResponse;

    public function rules(): array
    {
        $inUpdate = $this->isMethod('put') ? 'sometimes' : 'required';
        return [
            'name' => "$inUpdate|string|unique:stations,name,".$this->route('station'),
            'lat' => "$inUpdate|numeric",
            'long' => "$inUpdate|numeric",
            'zone_id' => "$inUpdate|numeric|exists:zones,id"
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function failedValidation(Validator $validator)
    {
        $this->throwValidationException($validator);
    }
}
