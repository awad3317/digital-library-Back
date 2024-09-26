<?php
use Illuminate\Validation\Rules\File;

function save_files_programs($request,$name)
{
    if(is_null($request)){
        return null;
    }
    $extions=$request->getclientoriginalextension();
    if(Str::contains('jpeg bmp png jpg', $extions)){
        $folder='\\image';
    }
    else{
        $folder='\\Program';
    }
    $File_name=$name.'_'.time().'.'.$extions;
        $File_path = $request->move('Programs'.$folder,$File_name);
        return $File_path;


}

function validtaor_program()
{
    return[
        'name' =>[
            'required',
            'max:30',
            'string',
            'min:2',
            'unique:Programs,name'
        ] ,
        'image'=>[
            'required',
            File::image()->types(['jpeg','bmp','png','jpg'])
            ->max(2048),
        ],
        'file_path'=>[
            'required',
            File::types(['zip','rar'])
        ],
        'category_id'=>[
            'required',
        ],
        'description'=>[
            'nullable',
            'max:1000'
        ],

    ];
}
