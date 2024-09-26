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

<form action="{{url('store')}}" method="post" enctype="multipart/form-data">
        <!-- Add CSRF Token -->
        @csrf
    <div class="form-group">
        <label>name</label>
        <input type="text" class="form-control" name="name" >
        <label>nmuber</label>
        <input type="text" class="form-control" name="number" >
        <h4>description</h4>
        <textarea rows="5" cols="50" name="lectures_description" ></textarea> <br><br><br>
        {{-- <h4>detils description</h4>
        <textarea rows="5" cols="50" name="description" ></textarea> <br><br><br>
        <br><br><br> --}}
    </div>
    <br><br><br>
    </div>
    <br><br>
    <label for="">Upload the file</label>
    <input id='file_path' name="file_path" type="file" multiple >
    <label for="">image</label>
    <input id='file_path' name="image" type="file" multiple >
    <br><br>


    <label>category_id</label>
    {{-- <br><br><br> --}}
    <input type="text" class="form-control" name="category_id" >
    <label>version</label>
    {{-- <br><br><br> --}}
    <input type="text" class="form-control" name="version" >
    <label>accepted</label>
    {{-- <br><br><br> --}}
    <input type="text" class="form-control" name="accepted" >
    <button type="submit">Submit</button>
</form>

</div>
</body>
</html>
