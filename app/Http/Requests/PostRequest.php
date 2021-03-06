<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\District;
use App\Models\Ward;

class PostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
        switch ($this->method()) {
            case 'GET':
                return [
                ];
            case 'PUT':
            case 'PATCH':
                return [
                ];
            case 'POST':
                return [
                  'title' => 'required|min:4|max:256',
                  'price' => 'required|integer',
                  'type' => 'required|in:"sell", "buy',
                  'category' => 'required|exists:categories,slug',
                  'province_id' => 'required|exists:provinces,id',
                  'district_id' => 'required|exists:districts,id',
                  'ward_id' => 'required|exists:wards,id',
                  'address' => 'required|min:4|max:256',
                  'images' => 'required',
                  'images.*' => 'required|image|max:5120',
                  'description' => 'max:1028',
                ];
            default:
                return [];
        }
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'images.*.image' => 'Invalid images',
        ];
    }
}
