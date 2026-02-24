<?php

namespace App\Http\Requests;

use App\User;

class StoreSignLocationRequest extends Request
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'longitude' => 'required|numeric|min:-180|max:180',
            'latitude'  => 'required|numeric|min:-90|max:90',
            'user_id'   => 'required',
        ];
        /** @var User $user */
        $user = auth()->user();
        if ($user->type) {
            $validUserIds = User::where('id', $user->id)->orWhere('county_id', $user->id)->pluck('id')->toArray();
            $rules['user_id'] = 'required|in:' . implode(',', $validUserIds);
        }

        return $rules;
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
