<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<h2>Register</h2>
    <form method="POST" action="{{url('store')}}">
        {{ csrf_field() }}
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" class="form-control" id="name" name="name">
        </div>


        <div class="form-group">
            <label for="password">Password:</label>
            <input type="password" class="form-control" id="password" name="password">
            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">

        </div>

        <div class="form-group">
            <label for="username">username:</label>
            <input type="text" class="form-control" id="username" name="username">
        </div>
        <label >user type</label>
        <input type="text" class="form-control" id="user_type_id" name="user_type_id">




        <div class="form-group">
            <button style="cursor:pointer" type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>

</body>
</html>
