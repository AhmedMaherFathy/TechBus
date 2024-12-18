<?php

namespace Modules\Bus\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class BusRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;

    public function rules(): array
    {
        $inUpdate = ! preg_match('/.*buses$/', $this->url());
        $value = $inUpdate ? 'sometimes' : 'required';
        $ignore = $inUpdate ? ','.$this->route('id') : '';
        return [
            'plate_number' => $value.'|string|unique:buses,plate_number'.$ignore,
            'status' => 'sometimes|string|in:active,off',
            'license' => 'nullable|string',
            'route_id' => 'nullable|string|exists:routes,custom_id',
            'ticket_id' => 'nullable|string|exists:tickets,custom_id',
            'driver_id' => 'nullable|string|exists:drivers,custom_id|unique:buses,driver_id'.$ignore
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
