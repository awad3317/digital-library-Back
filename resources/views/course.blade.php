<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito';
        }
    </style>
</head>
<body>

<div>

<form action="{{url('store')}}" method="POST" enctype="multipart/form-data">
        <!-- Add CSRF Token -->
        @csrf
        {{-- @method('put') --}}
    <div class="form-group">
        <label>Name</label>
        <input type="text" class="form-control" name="course_name" >
        {{-- <label>published_date</label> --}}
        {{-- <input type="text" class="form-control" name="published_date" > --}}
        <h4>description</h4>
        <textarea rows="5" cols="50" name="course_description" ></textarea> <br><br><br>
         {{-- <br><br><br> --}}
         <select name="accepted" id="accepted">
             <option value="1">true</option>
             <option value="0">false</option>
         </select>
    </div>
    <br><br><br>
    <p>Upload the image of pro</p>
    <div class="form-group">
        <input type="file" name="image" >
    </div>
    <br><br>
    <input  id="file_path" name="file_path[]" type="file" multiple  >
    <label>Upload the pro</label>
    <br><br>
     {{--<label>video Name</label> --}}
    {{--<input type="text" class="form-control" name="video_name">--}}
     <br><br><br> 
    <label>category id</label>
     <br><br><br> 
    <input type="text" class="form-control" name="category_id" >
    <button type="submit">Submit</button>
</form>

</div>
</body>
</html>
