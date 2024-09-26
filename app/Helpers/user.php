<?php
function user()
{
    return [
        'name' => [
            'required',
            'max:120',
            'string',
            'min:2',
            ],

        // 'email'=>[
        //     'required',
        //     'email:rfc,dns',
        //     'unique:users,email'
        // ],
        
        'password'=>[
            'required',
            'min:8'
            // 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'

        ],
        're-password'=>[
            'required_with:password|same:password|min:8'
        ],
        'username'=>[
            'required',
            'min:6',
            'max:25',
            'unique:users,username'
        ]
        ]; 

}