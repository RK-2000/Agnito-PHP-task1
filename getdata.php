
<?php
include 'conn.php';
if (isset($_GET['country'])){
    $country = $_GET['country'];
    $r = mysqli_query($con," select id from countries where name = '$country';");
    $id = mysqli_fetch_row($r)[0];
    $res = mysqli_query($con,"select * from states where country_id = '$id';");

    while($row = mysqli_fetch_array($res))
                        {
                            echo "<option value=".$row['name'].">".$row['name']."</option>";
                        }

}
if (isset($_GET['state'])){
    $state = $_GET['state'];
    $r = mysqli_query($con," select id  from states where name = '$state';");
    $id = mysqli_fetch_row($r)[0];
    $res = mysqli_query($con,"select * from cities where state_id = '$id';");
    while($row = mysqli_fetch_array($res))
                        {
                            echo "<option value=".$row['name'].">".$row['name']."</option>";
                        }
    
}

?>