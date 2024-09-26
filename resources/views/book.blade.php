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
        <label>book Name</label>
        <input type="text" class="form-control" name="name" >
       <label>Publisher_id</label>
        <input type="text" name="Publisher_id" id="">
        <label>accepted</label>
        <input type="text" name="accepted" id="">
        <label>category_id</label>
        <input type="text" name="category_id" id="">
        <label>edition</label>
        <input type="text" name="edition" id="">
        <label>file_path</label>
        <input type="file" name="file_path" id="">
        <label>image</label>
        <input type="file" name="image" id="">
        <label>book_audio</label>
        <input type="file" name="book_audio" id="">
        <h4>description</h4>
        <textarea rows="5" cols="50" name="description" ></textarea> <br><br><br>
         <br><br><br>
         <br><br>
    <button type="submit">Submit</button>
</form>

</div>
</body>
</html>
