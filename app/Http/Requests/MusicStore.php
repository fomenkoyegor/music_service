<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MusicStore extends FormRequest
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
        return [
            'audio'=>'required',
            'title'=>'required | min:2',
            'artist'=>'required | min:2',
            'album'=>'required | min:2',
            'genre'=>'required',
            'year'=>'required | min:4',

        ];
    }
}
