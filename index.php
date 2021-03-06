<?php
    include 'conn.php';
    session_start();

    // To display message from user

    if ($_SESSION['message']){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }

    // To verify if user is authenticated
    if (!$_SESSION['authenticated']){
        header('Location:login.php');
    }
    // alter table row mysql
    // To logout user
    if (isset($_POST['logout'])){
        unset($_SESSION['authenticated']);
        unset($_SESSION['username']);
        unset($_SESSION['password']);
        header('Location:login.php');
    }


    // To update user details
    $username = $_SESSION['username'];
    $password = $_SESSION['password'];
    
    // if both details are different

    if(isset($_POST['update'])){
        $temp_username = $_POST["username"];
        $temp_password = $_POST["password"];
        
        // when id and password both are changed
        if ($username != $temp_username and $password != $temp_password){
            $q = "select * from user where username='$temp_username';";
            $query = mysqli_query($con,$q) or trigger_error("Query Failed".mysqli_error($con),E_USER_ERROR);
            $result=mysqli_fetch_assoc($query);
            if (!$result['username']){
                $q="update user set username='$temp_username' , password='$temp_password' where username = '$username' and password='$password';";
            $query = mysqli_query($con,$q) or trigger_error("Error".mysqli_error($con));
                if ($query)
                    {
                    unset($_SESSION['username']);
                    unset($_SESSION['password']);
                    $_SESSION['username'] = $temp_username;
                    $_SESSION['password'] = $temp_password;
                    header('Location:index.php');
                    $_SESSION['message'] = "<div class='alert alert-success'> Email and Password changed!</div>";    

                    }
            }
            echo "<div class='alert alert-danger'>An account has been made using this email, Try a different one</div>";
        }
        else{            
        
        // when id is updated
        if ($username != $temp_username and $password == $temp_password){
            $q = "select * from user where username='$temp_username';";
            $query = mysqli_query($con,$q) or trigger_error("Query Failed".mysqli_error($con),E_USER_ERROR);
            $result=mysqli_fetch_assoc($query);
            if (!$result['username']){
                $q="update user set username='$temp_username' where username = '$username'";
                    $query = mysqli_query($con,$q) or trigger_error("Error".mysqli_error($con));
                    if ($query)
                        {
                            unset($_SESSION['username']);
                            $_SESSION['username'] = $temp_username;
                            $_SESSION['message'] = "<div class='alert alert-success'>Email changed!</div>";    

                            header('Location:index.php');    
                        }
                }
                else{
                    echo "<div class='alert alert-danger'>An account has been made using this email, Try a different one</div>";
                }
            }
        else {
            // if password is changed
            if ($username == $temp_username and $password != $temp_password){
                $q="update user set password = '$temp_password' where username = '$username' and password='$password';";
                $query = mysqli_query($con,$q) or trigger_error("Error".mysqli_error($con));
                if ($query)
                    {
                        unset($_SESSION['password']);
                        $_SESSION['password'] = $temp_password;
                        $_SESSION['message'] = "<div class='alert alert-success'>Password changed!</div>";    
                        header('Location:index.php');
                        
                    }
                }
            }
        }
        
    }
    // To upload image
    if (isset($_POST['upload'])){
        if (!empty($_FILES['img']))
        {
            $target_dir = 'uploads/';
            $target_file = $target_dir . basename($_FILES["img"]["name"]);
            $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
            // if file is png, jpeg or jpg
            if ($imageFileType == 'png' or $imageFileType == 'jpeg' or $imageFileType == 'jpg'){
                if(move_uploaded_file($_FILES['img']['tmp_name'],$target_file)){
                    $q = "update user set img = '$target_file' where username = '$username' and password = '$password';";
                    $query = mysqli_query($con,$q) or trigger_error(mysqli_error($con),E_USER_ERROR);
                    if($query){
                        $_SESSION['message'] = "<div class='alert alert-success'>Image Uploaded</div>";
                    }
                    else{
                        $_SESSION['message'] = "<div class='alert alert-success'>Image can not be saved in database.</div>";
                    }
                }
                else{
                    $_SESSION['message'] = "<div class='alert alert-danger'>Cannot be uploaded</div>";
                }
            }
            else{
                $_SESSION['message'] = "<div class='alert alert-'>Please choose an image with proper extensions!</div>";    
            }
            
            header("location:index.php");
        }
        else{
            $_SESSION['message'] = "<div class='alert alert-success'>Please upload an image upload!</div>";
            header("location:index.php");    

        }
        
    }
    // Get user details
    $q="select * from user where username='$username' and password='$password';";
    $query = mysqli_query($con,$q) or trigger_error(mysqli_error($con));
    $result = mysqli_fetch_assoc($query);
    $img=$result['img'];
    if ($result['address']){
        $address = explode(", ",$result['address']);
    
        $country=$address[2];
        $state=$address[1];
        $city=$address[0];
    }
    


    

    // To delete user from database permanently
    if (isset($_POST['delete'])){
        $q="delete from user where username='$username' and password='$password';";
        echo $q;
        $query = mysqli_query($con,$q) or trigger_error("Cannot be deleted ".mysqli_error($con),E_USER_NOTICE);
        if ($query){ 
            unset($_SESSION['authenticated']);
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            header('Location:login.php');
        }
    }

    // To save address
    if (isset($_POST['address'])){
        $country = $_POST['country'];
        $state = $_POST['state'];
        $city = $_POST['city'];
        $address = "$city, $state, $country";
        $q="update user set address='$address' where username='$username' and password='$password';";
        $query=mysqli_query($con,$q) or trigger_error($con);
        if ($query){
            $_SESSION['message'] = "Address updated";
        }
        else{
            $_SESSION['message'] = "$q";
        }
        header('Location:index.php');
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
    <script src="https://code.jquery.com/jquery-2.2.4.min.js"
        integrity="sha256-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44=" crossorigin="anonymous"></script>
    <script async
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDrLy7nCsS320eaconYpHIB1TvGTfu_qyc&callback=initMap">
    </script>

    <title>CRUD : Home</title>
    <style>
    #profile {
        max-width: 300px;
        max-height: 500px;
        width: auto;
        height: auto;
    }
    </style>
    <style>
    #map {
        height: 500px;
        width: 90vw;
    }

    html,
    body {
        height: 100%;
        margin: 0;
        padding: 0;
    }
    </style>


</head>

<body class="container">
    <div class="row">

        <form method="POST" class="form-group p4">
            <h2 class="my-4 text-center">Basic Details</h2>
            <label>Username</label>
            <input class="form-control" value='<?php echo "$username"; ?>' name="username" type="email"
                pattern="^[^\s@]+@[^\s@]+\.[^\s@]+$" oninvalid="setCustomValidity('Please enter correct email id.')" />
            <label>Password</label>
            <input class="form-control" value='<?php echo "$password"; ?>' name="password" type="text"
                pattern="^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{7,}$"
                oninvalid="setCustomValidity('Password should contain minimun 7 characters, one uppercase letter, one lowercase letter and one special character.')">
            <button class="btn btn-primary mt-4 mb-4" type="submit" name="update"> Update </button>
        </form>
    </div>
    <hr />

    <div class="row d-flex justify-content-center text-center align-item-center">
        <h2 class="my-4">Profile Picture</h2>
        <div class="col-12">
            <img id="profile" src="<?php echo $img; ?>" default="uploads/profile.png" class="text-center mb-4" />
        </div>
        <br>
        <div class="col-12">
            <form method="POST" enctype="multipart/form-data">
                <input type="file" name="img" placeholder="Change profile picture">

                <button type="submit" name="upload" class="btn btn-primary">Upload</button>
            </form>
        </div>
    </div>
    <hr />
    <!-- Address -->
    <div class="row d-flex justify-content-center text-left">
        <h2 class="my-4">Address</h2>
        <?php
        if ($address and $city and $state){
            echo "<div>
            <p>City: ".$city."</p>
            <p>State: ".$state."</p>
            <p>Country: ".$country."</p>
            </div>";
        }
        ?>
        <form method="POST" class="col-12 col-md-4">
            <div>
                Country:
                <select class="mt-2" onchange="findCountry(this.value)" name="country">
                    <option>Country</option>
                    <?php
                    $res = mysqli_query($con,'select * from countries;');
                    while($row = mysqli_fetch_array($res))
                    {
                 ?>
                    <option><?php echo $row['name']; ?></option>
                    <?php
                    }
                    ?>
                </select>
            </div>
            <div>
                State
                <select class="mt-2" id="state" onchange="findState(this.value)" name="state">
                    <option>State</option>
                </select>
            </div>
            <div>
                City
                <select id="city" name="city" class="mt-2">
                    <option>City</option>
                </select>
            </div>
            <button type="submit" name="address" class="btn btn-primary mt-2">Submit</button>
        </form>
    </div>
    <div class="col-12 col-md-8">

        <div id="map">

        </div>
    </div>
    <hr />
    <div class="row text-center mt-4">


        <div class="col-6">
            <form method="POST" name="logout-form">
                <button class="btn btn-warning" name="logout" type="submit">Log Out</button>
            </form>
        </div>
        <div class="col-6">
            <form method="POST" name="delete-for">
                <button class="btn btn-danger" name="delete" type="submit">Delete</button>
            </form>
        </div>
    </div>
    <script>
    function callTwoFunction() {
        getCurrentLocation();
        initMap();
    }

    function findCountry(data) {
        const ajaxreq = new XMLHttpRequest();
        ajaxreq.open('GET', 'http://localhost/test/getdata.php?country=' + data, 'TRUE');
        ajaxreq.send();

        ajaxreq.onreadystatechange = function() {
            if (ajaxreq.readyState == 4 && ajaxreq.status == 200) {
                document.getElementById('state').innerHTML = ajaxreq.responseText;
            }
        }
    }

    function findState(data) {
        const ajaxreq = new XMLHttpRequest();
        ajaxreq.open('GET', 'http://localhost/test/getdata.php?state=' + data, 'TRUE');
        ajaxreq.send();

        ajaxreq.onreadystatechange = function() {
            if (ajaxreq.readyState == 4 && ajaxreq.status == 200) {
                document.getElementById('city').innerHTML = ajaxreq.responseText;
            }
        }
    }
    var x = document.getElementById('current_location');

    function getCurrentLocation() {

        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
        } else {
            alert('Your browser does not support Geolocation');
        }
    }
    var lat = 33.8121;
    var long = -117.9190;

    function showPosition(position) {
        lat = position.coords.latitude;
        long = position.coords.longitude;
    }

    let map, infoWindow;
    var geocoder;

    function initMap() {
        geocoder = new google.maps.Geocoder();

        map = new google.maps.Map(document.getElementById("map"), {

            center: {
                lat: lat,
                lng: long
            },
            zoom: 10,
        });

        infoWindow = new google.maps.InfoWindow();
        const locationButton = document.createElement("button");
        locationButton.textContent = "Pan to Current Location";
        locationButton.classList.add("custom-map-control-button");
        map.controls[google.maps.ControlPosition.TOP_CENTER].push(locationButton);
        locationButton.addEventListener("click", () => {
            // Try HTML5 geolocation.
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const pos = {
                            lat: position.coords.latitude,
                            lng: position.coords.longitude,
                        };
                        infoWindow.setPosition(pos);
                        infoWindow.setContent("Location found.");
                        infoWindow.open(map);
                        map.setCenter(pos);
                    },
                    () => {
                        handleLocationError(true, infoWindow, map.getCenter());
                    }
                );
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false, infoWindow, map.getCenter());
            }
        });
    }
    </script>
</body>

</html>