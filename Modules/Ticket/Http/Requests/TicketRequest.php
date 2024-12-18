<?php

namespace Modules\Ticket\Http\Requests;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class TicketRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    use HttpResponse;
    public function rules(): array
    {
        $inUpdate = ! preg_match('/.*tickets$/', $this->url());
        $value = $inUpdate ? 'sometimes' : 'required';
        $ignore = $inUpdate ? ',' . $this->route('id') : '';
        return [
            'qr_code'   =>  $value . '|string|unique:tickets,qr_code'.$ignore,
            'points'    =>  $value . '|integer|min:1',
            'status'    =>  $value . '|in:valid,invalid',
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
