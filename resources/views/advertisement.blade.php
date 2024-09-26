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

<form action="{{url('update/1')}}" method="post" enctype="multipart/form-data">
        <!-- Add CSRF Token -->
        @csrf
        @method('put')
    <div class="form-group">
        <label>title</label>
        <input type="text" class="form-control" name="title" >
       <label>description</label>
        <input type="text" name="description" id="">
        <label>file_path</label>
        <input type="file" name="image" id="">
         <br><br><br>
         <br><br>
    <button type="submit">Submit</button>
</form>

</div>
</body>
</html>
