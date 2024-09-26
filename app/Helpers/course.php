<?php

use Illuminate\Validation\Rules\File;

function save_files_course($request)
{
    if (is_null($request)) {
        return null;
    }
    $extions = $request->getclientoriginalextension();
    if (Str::contains('jpeg bmp png jpg', $extions)) {
        $folder = '\\image';
    }
    $File_name = time().'.'.$extions;
    $File_path = $request->move('courses'.$folder, $File_name);

    return $File_path;
}



function validtaor_course()
{
    return [
        'course_name' => [
            'required',
            'max:30',
            'string',
            'min:2',
            ],
        'image' => [
            'required',
            File::image()->types(['jpeg', 'bmp', 'png', 'jpg'])
            ->max(2048),
        ],
        'course_description' => [
            'nullable',
            'max:1000',
        ],

        'file_path.*' => [
            'required',
            File::types(['mp4', 'mov', 'mkv','MP4']),
            'max:307200'
        ],
        'category_id'=>[
            'required',
        ]
    ];
}
