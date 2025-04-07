<?php

namespace Modules\Place\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class RouteRequest extends FormRequest
{
    use HttpResponse;
    
    public function rules(): array
    {
        $inUpdate = $this->isMethod('put') ? 'sometimes' : 'required';

        return [
            'name' => "$inUpdate|string|max:255",
            'number' => "$inUpdate|string|unique:routes,number,".$this->route('route'),
            'stations' => 'sometimes|array',
            'stations.*.station_id' => 'required|exists:stations,id',
            'stations.*.order' => 'required|integer',
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
