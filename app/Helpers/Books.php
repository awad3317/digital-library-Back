<?php
use App\Models\lecture_details;
use App\Models\course;
use Illuminate\Support\Str;
use App\Models\video;
use Illuminate\Support\Facades\Crypt;


function save_files_books($request,$name){
    if(is_null($request)){
        return null;
    }
    $extions=$request->getclientoriginalextension();
    if(Str::contains('jpeg bmp png jpg', $extions)){
        $folder='\\image';
    }
    elseif(Str::contains('pdf', $extions)){
        $folder='\\Book';
    }
    else {
        $folder='\\audio';
    }
        $File_name=$name.'_'.time().'.'.$extions;
        $File_path = $request->move('Books'.$folder,$File_name);
        return $File_path;
}


function save_files_Project($request,$name){
    if(is_null($request)){
        return null;
    }
    $extions=$request->getclientoriginalextension();
    if(Str::contains('jpeg bmp png jpg', $extions)){
        $folder='\\image';
    }
    elseif(Str::contains('pdf', $extions)){
        $folder='\\Project';
    }
    $File_name=$name.'_'.time().'.'.$extions;
    $File_path = $request->move('Projects'.$folder,$File_name);
    return $File_path;
}

function save_lecutre($request,$id)
{
    foreach($request->file_path as $lecture_details) {
        $id = Crypt::decrypt($id);
        $extions = $lecture_details->getclientoriginalextension();
        $name = $lecture_details->getClientOriginalName();
        $detailsname = uniqid(' ',true).'-Lecture_details-.'.$extions;
        $File_path = $lecture_details->move('Lecture_details'.$detailsname);
        $lecture_details=lecture_details::create([
            'file_path'=>$File_path,
            'description'=>$request->description,
            'lecture_id'=>$id,
        ]);
    }
    return $lecture_details;


}

function save_video($request,$id)
{
    $id = Crypt::decrypt($id);
    $counter=0;
    foreach($request->file_path as $video) {
        $extions = $video->getclientoriginalextension();
        $name = $video->getClientOriginalName();
        $videoname = uniqid(' ',true).'-video-.'.$extions;
        $counter = $counter + $video->getSize();
        $size_video = formatBytes($video->getSize());
        $File_path = $video->move('videos',$videoname);
        $data_video = video::create([
        'file_path' => $File_path,
        'name' => $name,
        'size_video' => $size_video,
        'course_id' => $id,
        ]);
        }
        $size_course = formatBytes($counter);
        $size=course::find($id);
        $size->update(['size_course' => $size->size_course = $size_course]);
        return $data_video;
}
function save_files_Advertisement($request,$name){
    if(is_null($request)){
        return null;
    }
    $extions=$request->getclientoriginalextension();
    $folder='\\image';
    $File_name=$name.'_'.time().'.'.$extions;
    $File_path = $request->move('Ads'.$folder,$File_name);
    return $File_path;
}


function unformatBytes($size)
{
    $unit = ['B', 'KB', 'MB', 'GB', 'TB', 'PB'];
    return intval(substr($size, 0, -2)) * pow(1024, array_search(strtoupper(substr($size, -2)), $unit));
}
