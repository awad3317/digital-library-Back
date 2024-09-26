<?php
use Illuminate\Validation\Rules\File;

function validtaor_author_book(){
    return[
        'book_id'=>[
            'required',
        ],
        'author_id'=>[
            'required'
        ],

    ];
}
