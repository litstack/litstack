<?php

namespace Ignite\Chart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class ChartRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(Request $request)
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'key'  => 'required',
            'type' => 'required',
        ];
    }
}
