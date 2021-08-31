<?php
include 'conn.php';
session_start();
 // To display message

 if ($_SESSION['message']){
    echo $_SESSION['message'];
    unset($_SESSION['message']);
}
if ($_SESSION['authenticated']){
    header('Location:index.php');
}
if(isset($_POST["submit"])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    if ($password == $cpassword){
        $q = "select * from user where username='$username';";
        $query = mysqli_query($con,$q) or trigger_error("Query Failed".mysqli_error($con),E_USER_ERROR);
        $result=mysqli_fetch_assoc($query);
        if (!$result['username']){
            $q = "insert into user(username,password) values('$username','$password')";
            $query = mysqli_query($con,$q) or trigger_error("Query Failed".mysqli_error($con),E_USER_ERROR);
            
            if($query){
                $_SESSION['authenticated'] = "True";
                $_SESSION['username'] = $username;
                $_SESSION['password'] = $password;
                echo "User created";
                header("Location:index.php");
            }
        else{
        $_SESSION['message']="<div class='alert alert-danger'>Something went wrong. Try again</div>";
        header('location:create.php');        
    }header('location:create.php');
        }
    else{
        $_SESSION['message']="<div class='alert alert-danger'>We have an account linked to this email. Try to log in</div>";
        header('location:create.php');    
    }
    }
    else{
        $_SESSION['message']= "<div class='alert alert-danger'>Password and Confirm password doesn't match!</div>";
        header('location:create.php');
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
    <title>CRUD-insert</title>
    <style>
    form {
        max-width: 450px;
        width: 100%;
    }
    </style>

</head>

<body class="container">
    <div class="d-flex justify-content-center">
        <form method="POST" class="form-group p-4" }>
            <h1 class="text-center my-4">
                Create a new account.
            </h1>
            <input autocomplete="false" name="hidden" type="text" style="display:none;">
            <label>Email</label>
            <input class="form-control" type="email" name="username" pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" required
                oninvalid="setCustomValidity('Please enter correct email id.')" />
            <label>Password</label>
            <input class="form-control" type="password" value="" name="password" required
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$"
                oninvalid="setCustomValidity('Password should contain minimun 7 characters, one uppercase letter, one lowercase letter and one special character.')" />
            <label>Confirm your password</label>
            <input class="form-control" type="text" value="" name="cpassword" required
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$"
                oninvalid="setCustomValidity('Password should contain minimun 7 characters, one uppercase letter, one lowercase letter and one special character.')" />
            <button class="btn btn-success mt-4 mb-2" type="submit" name="submit"> Create </button>
            <br>
            <a href="login.php" class="text-center">Already have an account?</a>
        </form>
    </div>

</body>

</html>