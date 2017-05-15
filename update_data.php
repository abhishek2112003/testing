<?php

include "db.php";
if (!isset($_SESSION['role']) && !isset($_COOKIE['role'])) 
{
    header('location:login.php');
}
if (isset($_SESSION['role']) && $_SESSION['role'] == "Admin" || isset($_COOKIE['role']) && $_COOKIE['role'] == "Admin"  )
    {
    $id = $_GET['id'];
    }
else 
    {
    if(isset($_SESSION['role']))
    {
      $id = $_SESSION['id'];  
    } 
    else{
       $id = $_COOKIE['id'];  
    }
    }
 

if (isset($_SESSION['role']))
    {
    
    $firstname_header = $_SESSION['firstname'];
    $lastname_header = $_SESSION['lastname'];
    } 
else 
    {
   
    $firstname_header = $_COOKIE['firstname'];
    $lastname_header = $_COOKIE['lastname'];
    }

$logout = "logout";

function RandomString()
{

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
     $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
}

// counter to check all validatios are working or not
$counter = 0;
$error_flag=0;
//  variables to store data from database to form elements
$username = $password = $firstname = $lastname = $email = $gender = $hobbies = $date = $address = $location = $filepath = "";

// Variables to store data to database from form elements
$username_update = $password_update = $firstname_update = $lastname_update = $email_update = $gender_update = $hobbies_update = $date_update = $address_update = $location_update = "";

//  variables to show error message for particular field
$username_error = $password_error = $firstname_error = $lastname_error = $file_error = $email_error = $gender_error = $hobbies_error = $date_error = $location_error = $address_error = "";

// Query to select data from registration table of paerticular given id
$data = mysql_query("SELECT  * FROM `registration` WHERE `id`='$id' ") or die(mysql_error());

$info = mysql_fetch_assoc($data);
if(empty($info['username']))
{
    header('location:select_data.php');
}
$username = ($info['username']);
$password = $info['password'];
$firstname = ucfirst($info['firstname']);
$lastname = ucfirst($info['lastname']);
$email = $info['email'];
$gender = $info['gender'];
$address = $info['address'];
$location = $info['location'];
$date = $info['dob'];
$hobbies = explode(",", $info['hobbies']);
$image = $info['image'];


// condition to check gender chech box will be checked or not
$checked_gender = '';
if ((!isset($_POST['gender']) && $gender == "male") || (isset($_POST['gender']) && $_POST['gender'] == 'male'))
    {
    $checked_gender = 'male';
    }
else
    {
    $checked_gender = 'female';
    }


// condition to check City Field will be checked or not
$selected_city = '';

if ((!isset($_POST['location']) && $location == "Jaipur") || (isset($_POST['location']) && $_POST['location'] == 'Jaipur')) {
    $selected_city = 'Jaipur';
} else if ((!isset($_POST['location']) && $location == "Jodhpur") || (isset($_POST['location']) && $_POST['location'] == 'Jodhpur')) {
    $selected_city = 'Jodhpur';
} else if ((!isset($_POST['location']) && $location == "Bikaner") || (isset($_POST['location']) && $_POST['location'] == 'Bikaner')) {
    $selected_city = 'Bikaner';
}

// condition to check Hobbies hobbies will be selected or not
$checked_hobbies_java = '';
$checked_hobbies_android = '';

if ((!isset($_POST['hobbies']) && in_array('Java', $hobbies)) || (isset($_POST['hobbies']) && in_array('Java', $_POST['hobbies']))) {
    $checked_hobbies_java = 'Java';
}

if ((!isset($_POST['hobbies']) && in_array('Android', $hobbies)) || (isset($_POST['hobbies']) && in_array('Android', $_POST['hobbies']))) {
    $checked_hobbies_android = 'Android';
}


// To update the given datainto database
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $filename = $_FILES['file']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filetype = $_FILES['file']['type'];
    $filename = time() . RandomString() . "." . $ext;
    if($_FILES['file']['name'] !='')
    {
        $info= getimagesize($_FILES['file']['tmp_name']);
    }
    if (($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') && $_FILES['file']['size'] < 500000 && $info[0]<500 && $info[1]<500){

        if (move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $filename)) {
            unlink($image);
        }
        $filepath = "uploads/" . $filename;
    } elseif (empty($_FILES['file']['name'])) {
         
            $filepath = $image;
        
    }
     else 
    {
      $file_error = "You are not following one or more rules given below.";
      $error_flag=1;
    }




    if (empty($_POST["username"])) {
        $username_error = "User Name is required";
        $error_flag=1;
    } 
    if (empty($_POST["firstname"])) {
        $firstname_error = "First Name is required";
        $error_flag=1;
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["firstname"])) {
        $firstname_error = "Only letters and white space allowed";
        $error_flag=1;
    } 
    if (empty($_POST["lastname"])) {
        $lastname_error = "Last Name is required";
        $error_flag=1;
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["lastname"])) {
        $lastname_error = "Only letters and white space allowed";
        $error_flag=1;
    } 

    if (empty($_POST["email"])) {
        $email_error = "Email is required";
        $error_flag=1;
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        $error_flag=1;
    } 
    if (empty($_POST["gender"])) {
        $gender_error = "Gender is required";
        $error_flag=1;
    } 

    if (empty($_POST["hobbies"])) {
        $hobbies_error = "Please select atleast one hobby";
        $error_flag=1;
    } 

    if (empty($_POST["date"])) {
        $date_error = "DOB is required";
        $error_flag=1;
    } 
    
    $address_trim = trim($_POST["address"]);
    if (empty($address_trim)) {
        $address = "";
        $address_error = "Address is required";
        $error_flag=1;
    } 

    if ($_POST["location"] == '') {
        $location_error = "State is required";
        $error_flag=1;
    } 

//    Function to remove extra spaces, Slashes
    function test_input($data) 
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

//  condition to check all fields are filled with valid data or not, If yes then assign values to variables which are used to show input field
    if ($error_flag==0)
    {
       
        $username_update = test_input(trim($_POST["username"]));

        $firstname_update = test_input(trim($_POST["firstname"]));
        $lastname_update = test_input(trim($_POST["lastname"]));
        $email_update = test_input(trim($_POST["email"]));
        $gender_update = test_input($_POST["gender"]);
        $hobbies_update = implode(",", $_POST["hobbies"]);
        $date_update = test_input(date('Y-m-d',  strtotime($_POST["date"])));
        $address_update = test_input(trim($_POST["address"]));
        $location_update = test_input($_POST["location"]);

        if (empty($_POST["password"])) 
            {
            $password_update = $password;
            } 
        else
            {
            $password_update = test_input(md5($_POST["password"]));
            }
            
            
            mysql_query("UPDATE `registration` SET `image`= '$filepath', `username`= '$username_update'"
                        . " , `password` = '$password_update' , `firstname`= '$firstname_update', "
                        . "`lastname`= '$lastname_update' , `email`= '$email_update' ,"
                        . " `gender` = '$gender_update', `address`= '$address_update', "
                        . "`location`= '$location_update',`dob`='$date_update', "
                        . "`hobbies`='$hobbies_update' WHERE `id` = '$id' ")or die(mysql_error());



        header('Location: select_data.php');
    } 
    

    
}
?>



<html>

    <head>
        <title>Update Data</title>

        <link rel="stylesheet" type="text/css" href="css/main.css">

        <!--Script for jquery         -->
        <script src="jscript/jquery.min.js"></script>

        <link rel="stylesheet" href="css/jquery-ui.css">
        <script src="//code.jquery.com/jquery-1.10.2.js"></script>
        <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
       


    </head>
    <body>

        <!--header part for webpage -->
        <div class="header">

            <!--Logo part of webpage -->
            <div class="logo">
                <a href="index.html"><img src="images/download.jpg" alt="Home" width="50" height="50" ></a>

            </div>

            <!--Menu items of webpage -->
            <div class="menu">
                <span> <a id="index" href="index.html">Home</a></span>
                <span> <a href="logout.php"><?php echo $logout ?></a></span>
                
            </div>
        </div> 
        <div style="width: 100%"> 
            <img class="td6" src="images/13.jpg" alt="Home on rent" />
        </div> 

        <h2>Welcome <?php echo " $firstname_header " . " " . "$lastname_header"; ?></h2>

        <div class="div4">
            <!--form with element fields for signup           -->
            <form method="post" action="" enctype="multipart/form-data"> 
                <fieldset class="fieldset1">
                    <legend>Update</legend>

                    <table>
                        <tr>
                            <td class="td3"><b>User Name</b> <span class="required">*</span> </td>
                            <td class="td2">
                                <input class="td2" id="username" type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : $username ?>" />
                                <br> <span class="error" id="username_error"><?php echo $username_error ?> </span>
                            </td>
                            <td class="td3"><b> Password</b> <span class="required">*</span></td>
                            <td>
                                <input class="td2" id="password" type="password" name="password" value="<?php echo isset($_POST['password']) ? $_POST['password'] : '' ?>"/>
                                <br>  <span class="error" id="password_error"> <?php echo $password_error ?></span>
                            </td>

                        </tr>
                        <tr>
                            <td class="td5"> <b>First Name</b> <span class="required">*</span></td>
                            <td>

                                <input class="td2" id="firstname" type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : $firstname ?>"/>
                                <br> <span class="error" id="firstname_error"><?php echo $firstname_error ?></span>
                            </td>
                            <td class="td5"><b>Last Name</b> <span class="required">*</span> </td>
                            <td>
                                <input class="td2" id="lastname" type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : $lastname ?>"/>
                                <br>  <span class="error" id="lastname_error"><?php echo $lastname_error ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td5"><b> Email</b> <span class="required">*</span></td>
                            <td>
                                <input class="td2" type="text" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : $email ?>">
                                <br>  <span class="error" id="email_error"> <?php echo $email_error ?></span>
                            </td>
                            <td class="td5"><b>Gender</b> <span class="required">*</span> </td>
                            <td class="td5" >
                                <input  id="radio1" type="radio" name="gender" value="male" <?php echo ($checked_gender == 'male') ? "checked='checked'" : '' ?>  >Male
                                <input  id="radio2" type="radio" name="gender" value="female" <?php echo ($checked_gender == 'female') ? "checked='checked'" : '' ?> >Female
                                <br> <span class="error" id="gender_error"> <?php echo $gender_error ?></span>
                            </td>



                        </tr>
                        <tr>
                            <td class="td5"><b> Address</b> <span class="required">*</span></td>
                            <td>
                                <textarea id="address" rows="4" cols="25" name="address" >
                                    <?php echo isset($_POST['address']) ? $_POST['address'] : $address ?>
                                </textarea>
                                <br> <span class="error" id="address_error"><?php echo $address_error ?> </span>
                            </td>
                            <td class="td5"><b> Location</b> <span class="required">*</span></td>
                            <td>
                                <select id="location" onchange="Dropdown()"  name="location">
                                    <option value="">City</option> 
                                    <option <?php echo ($selected_city == 'Jaipur') ? "selected='selected'" : '' ?>value="Jaipur">Jaipur</option> 
                                    <option <?php echo ($selected_city == 'Jodhpur') ? "selected='selected'" : '' ?> value="Jodhpur">Jodhpur</option> 
                                    <option <?php echo ($selected_city == 'Bikaner') ? "selected='selected'" : '' ?> value="Bikaner">Bikaner</option> 

                                </select>
                                <br> <span class="error" id="location_error"><?php echo $location_error ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td5"><b> DOB</b> <span class="required">* </span></td>
                            <td>
                                <div >
                                    <input id="date" type="text" class="datepicker" name="date" style="width: 197px"  value="<?php echo isset($_POST['date']) ? $_POST['date'] :  $date  ?>">
                                </div>
                                
                                <br><span class="error" id="date_error"> <?php echo $date_error ?></span>

                            </td>
                            <td class="td5">
                                <b>  Hobbies </b><span class="required">* </span>
                            </td>
                            <td class="td5">
                                <input  type="checkbox" id="java" name="hobbies[]" value="Java"  <?php echo ($checked_hobbies_java == 'Java') ? "checked='checked'" : '' ?> />Java
                                <input type="checkbox" id="android" name="hobbies[]" value="Android" <?php echo ($checked_hobbies_android == 'Android') ? "checked='checked'" : '' ?> />Android
                                <br>  <span class="error" id="hobbies_error"> <?php echo $hobbies_error ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td5" >
                                <b>Profile Picture</b>
                            </td>
                            <td >
                                <input type="file" name="file">
                                 <br>  <span class="error" id="file_error"> <?php echo $file_error ?></span>
                            </td>
                            <td margin="center" colspan="2">

                                <img src="<?php echo $image ?>" alt="<?php echo ucfirst($firstname); ?>" width="100" height="100" >
                                
                            </td>


                         </tr>
                        <td colspan="2">
                             <br> <p class="file_rules">1.Image type should be jpeg/jpg/png/gif <br>2.Image size must be less then 5MB <br>3.image hight and width must be less then 500px </p>
                        </td>

                        <td colspan="2">

                                <!--Submit Button for update form-->

                                <input id="submit" type="submit" value="Update" class="b1"/>


                            </td>

                        </tr>

                    </table>

                </fieldset>
            </form>        
        </div>

        <script>
            $(function(){
               $('.datepicker').datepicker(); 
            });
        </script>




    </body>
</html> 