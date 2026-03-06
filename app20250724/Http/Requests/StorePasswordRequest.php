<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rules\Password;

class StorePasswordRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols()],
            'password-confirm' => 'required|same:password',
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }
}
