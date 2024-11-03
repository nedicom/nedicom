<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReviewRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [            
            'created_at' => 'date',    
            'mainusl_id' => 'nullable|numeric',
            'usl_id' => 'nullable|numeric',    
            'user_id' => 'numeric',
            'lawyer_id' => 'numeric',
            'rating' => 'numeric',
            'fio' => 'required|min:5',
            'description' => 'required|min:15',
        ];
    }

    public function messages(): array
{
    return [
        'fio.required' => 'Имя для отзыва обязательно',
        'description.required' => 'Описание самого отзыва обязательно',        
        'fio.min' => 'Не короче 5 символов',
        'description.min' => 'Слишком короткий отзыв',
    ];
}
}


