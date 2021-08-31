<?php
    session_start();
    include 'conn.php';

    if ($_SESSION['message']){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
    
    if ($_SESSION['authenticated']){
        header('Location:index.php');
    }
    
    
    if(isset($_POST['submit'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        $q = "select * from user where username='$username' and password='$password';";
        $query = mysqli_query($con,$q) or trigger_error("Query Failed".mysqli_error($con),E_USER_ERROR);
        $result=mysqli_fetch_assoc($query);
        
        if($result['password']==$password and $result['username'] == $username){
            session_start();
            $_SESSION['authenticated'] = "True";
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['message']= "<div class='alert alert-primary'>Login Successful!</div>";
            header('Location:index.php');
        }
        else{
            $_SESSION['message']= "<div class='alert alert-danger'>Incorrect details!</div>";
            header('location:login.php');
        }
    }

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-U1DAWAznBHeqEIlVSCgzq+c9gqGAJn5c/t99JyeKa9xxaYpSvHU5awsuZVVFIhvj" crossorigin="anonymous">
    </script>
    <title>Login</title>
    <style>
    form {
        max-width: 450px;
        width: 100%;
    }
    </style>
</head>

<body class="container">
    <div class="d-flex justify-content-center">
        <form method="POST" class="form-group p-4">
            <h1 class="my-4 text-center">Login</h1>
            <label>
                Email
            </label>
            <input class="form-control" type="email" required name="username" pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$"
                oninvalid="setCustomValidity('Please enter correct email id.')" />
            <label>
                Password
            </label>
            <input class="form-control" type="password" required name="password"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$"
                oninvalid="setCustomValidity('Password should contain minimun 7 characters, one uppercase letter, one lowercase letter and one special character.')" />

            <button class="btn btn-success mt-4 mb-2" type="submit" name="submit">Login</button>
            <br>
            <a href="create.php"> Create new user?</a>
        </form>

    </div>
</body>

</html>