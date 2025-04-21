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
        $inUpdate = ! preg_match('/.*drivers$/', $this->url());
        $value = $inUpdate ? 'sometimes' : 'required';
        $password = $inUpdate ? 'nullable' : 'required';
        $ignore = $inUpdate ? ','.$this->route('id') : '';
        // info($this->route('id')); die;
        // info($value); die;
        return [
        'full_name'        => $value.'|string|max:255',
        'email'            => $value.'|email|max:255|unique:drivers,email'.$ignore,
        'phone'            => $value.'|string|max:20|unique:drivers,phone'.$ignore,
        'national_id'      => $value.'|string|max:20|unique:drivers,national_id'.$ignore,
        'driver_license'   => $value.'|string|unique:drivers,driver_license'.$ignore,
        'password'         => $password.'|string|min:8',
        'photo'            => 'sometimes|image|mimes:jpeg,png,jpg,svg|max:2048',
        'status'           => 'sometimes',
        'start_time'       => 'sometimes|date_format:H:i:s',
        'end_time'         => 'sometimes|date_format:H:i:s,after:start_time',
        'days'             => 'sometimes|array',
        'days.*'           => 'sometimes|string|distinct|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
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
