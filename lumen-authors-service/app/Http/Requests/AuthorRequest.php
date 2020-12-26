<?php


namespace App\Http\Requests;


class AuthorRequest extends \Illuminate\Http\Request
{
    public function rules()
    {
        return [
            'name'    => 'required|max:32',
            'gender'  => 'required|max:8|in:male,female',
            'country' => 'required|max:16',
        ];
    }
}
