<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateReviewRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'fio'           => 'required|string|max:155',
            'description'   => 'required|string|min:15',
            'rating'        => 'required|numeric|min:1|max:5',
            'created_at'    => 'nullable|date',
            'verified_type' => 'nullable|in:yandex_id,gosuslugi,in_person,vk_id',
        ];
    }
}
