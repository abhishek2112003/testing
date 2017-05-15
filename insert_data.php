<?php
include 'db.php';

// variable to check form is fully validated or not
$counter = 0;
$error_flag = 0;
 $randstring = $info= '';
// Variables to get value of form elements
$username = $password = $firstname = $lastname = $email = $gender = $hobbies = $date = $address = $city = "";
//  variables to show error message for particular field
$username_error = $password_error = $firstname_error = $file_error = $lastname_error = $email_error = $gender_error = $hobbies_error = $date_error = $city_error = $address_error = "";
// Validation Check on form elements
if ($_SERVER["REQUEST_METHOD"] == "POST")
{


// Validation check for form elements
    if (empty($_POST["username"])) {
        $username_error = "User Name is required.";
        $error_flag = 1;
    }
    if (empty($_POST["password"])) {
        $password_error = "Password is required.";
        $error_flag = 1;
    }
    if (empty($_POST["firstname"])) {
        $firstname_error = "First Name is required.";
        $error_flag = 1;
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["firstname"])) {
        $firstname_error = "Only letters and white space allowed";
        $error_flag = 1;
    }
    if (empty($_POST["lastname"])) {
        $lastname_error = "Last Name is required.";
        $error_flag = 1;
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $_POST["lastname"])) {
        $lastname_error = "Only letters and white space allowed";
        $error_flag = 1;
    }

    if (empty($_POST["email"])) {
        $email_error = "Email is required.";
        $error_flag = 1;
    } elseif (!filter_var($_POST["email"], FILTER_VALIDATE_EMAIL)) {
        $email_error = "Invalid email format";
        $error_flag = 1;
    }
    if (empty($_POST["gender"])) {
        $gender_error = "Gender is required.";
        $error_flag = 1;
    }

    if (empty($_POST["hobbies"])) {
        $hobbies_error = "Please select atleast one hobby.";
        $error_flag = 1;
    }

    if (empty($_POST["date"])) {
        $date_error = "Date of birth is required.";
        $error_flag = 1;
    }
          $address_trim = trim($_POST["address"]);
    if (empty($address_trim)) {
        $address = "";
        $address_error = "Address is required.";
        $error_flag = 1;
    }
    if ($_POST["city"] == '') {
        $city_error = "City is required.";
        $error_flag = 1;
    }
    // Random character generator
    function RandomString()
    {

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randstring = '';
    for ($i = 0; $i < 10; $i++) {
    $randstring = $characters[rand(0, strlen($characters))];
    }
    return $randstring;
    }  
    // File uploading code
    $filename = $_FILES['file']['name'];
    $ext = pathinfo($filename, PATHINFO_EXTENSION);
    $filetype = $_FILES['file']['type'];
    $filename = time() . RandomString() . "." . $ext;
    if($_FILES['file']['name'] !='')
    {
        $info= getimagesize($_FILES['file']['tmp_name']);
    }
   
         
            if (($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') && $_FILES['file']['size'] < 500000 && $info[0]<500 && $info[1]<500)
            {
                
              if($error_flag==0)
                {
                    if (!move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $filename))
                        {
                            $file_error = "File not found.";
                            $error_flag=1;  
                        }
                } 
                $filepath = "uploads/" . $filename;
            }
        else
            {
            if ($_FILES['file']['name'] == '')
            {
                $file_error = "Profile picture is required.";
                $error_flag = 1;
            }
            else
            {
                $file_error = "You are not following one or more rules given below.";
                $error_flag=1;
            }
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
    if ($error_flag == 0)
    {
        $username = test_input(trim($_POST["username"]));
        $password = test_input(md5($_POST["password"]));
        $firstname = test_input(trim($_POST["firstname"]));
        $lastname = test_input(trim($_POST["lastname"]));
        $email = test_input(trim($_POST["email"]));
        $gender = test_input($_POST["gender"]);
        $hobbies = implode(",", $_POST["hobbies"]);
        $date = test_input(date('Y-m-d',  strtotime($_POST["date"])));
       
        $address = test_input(trim($_POST["address"]));
        $city = test_input($_POST["city"]);
       // Query to check, Username alreasy exist or not.
        $info = mysql_query("SELECT * from `registration` WHERE `username`= '$username' LIMIT 1");
        $i = 0;
            while ($row = mysql_fetch_assoc($info))
            {
              
                if ($username == $row['username'])
                    {
                    $i = 1;
                    }
            }

        if ($i == 1)
        {
            $username_error = "User already exist, select another name..!!";
        } 
        else 
        {
            mysql_query("INSERT INTO registration (image,username, password, firstname,lastname,email,gender,address,location,dob,hobbies)"
                            . "VALUES ('$filepath','$username', '$password', '$firstname','$lastname','$email','$gender','$address','$city','$date','$hobbies')")or die(mysql_error());


            header('Location: login.php?flag=1');
        }
    }
}

?>

<html>

    <head>
        <title>Registration</title>
<!--css file import -->
        <link rel="stylesheet" type="text/css" href="css/main.css">
<!--Script import for date picker( Third party API) -->
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
                <span> <a id="login" href="login.php">Login</a></span>

            </div>
        </div> 
        <div style="width: 100%"> 
            <img class="td6" src="images/13.jpg" alt="Home on rent" />
        </div> 

        <h2>Enter the following details:</h2>

        <div class="div4">
            <!--form with element fields for signup           -->
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data"> 
                <fieldset class="fieldset1">
                    <legend>Signup</legend>

                    <table>
                        <tr>
                            <td class="td3"><b>User Name</b> <span class="required">*</span> </td>
                            <td class="td2">
                                <input class="td2" id="username" type="text" name="username" value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>" />
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

                                <input class="td2 " id="firstname" type="text" name="firstname" value="<?php echo isset($_POST['firstname']) ? $_POST['firstname'] : '' ?>"/>
                                <br> <span class="error" id="firstname_error"><?php echo $firstname_error ?></span>
                            </td>
                            <td class="td5"><b>Last Name</b> <span class="required">*</span> </td>
                            <td>
                                <input class="td2" id="lastname" type="text" name="lastname" value="<?php echo isset($_POST['lastname']) ? $_POST['lastname'] : '' ?>"/>
                                <br>  <span class="error" id="lastname_error"><?php echo $lastname_error ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td5"><b> Email</b> <span class="required">*</span></td>
                            <td>
                                <input class="td2" type="text" id="email" name="email" value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
                                <br>  <span class="error" id="email_error"> <?php echo $email_error ?></span>
                            </td>
                            <td class="td5"><b>Gender</b> <span class="required">*</span> </td>
                            <td class="td5" >
                                <input  id="radio1" type="radio" name="gender" value="male" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'male') ? "checked='checked'" : '' ?>  >Male
                                <input  id="radio2" type="radio" name="gender" value="female" <?php echo (isset($_POST['gender']) && $_POST['gender'] == 'female') ? "checked='checked'" : '' ?>>Female
                                <br> <span class="error" id="gender_error"> <?php echo $gender_error ?></span>
                            </td>



                        </tr>
                        <tr>
                            <td class="td5"><b> Address</b> <span class="required">*</span></td>
                            <td>
                                <textarea id="address" rows="4" cols="25" name="address" >
                                    <?php echo isset($_POST['address']) ? $_POST['address'] : '' ?>
                                </textarea>
                                <br> <span class="error" id="address_error"> <?php echo $address_error ?></span>
                            </td>
                            <td class="td5"><b> Location</b> <span class="required">*</span></td>
                            <td>
                                <select id="city" onchange="Dropdown()"  name="city">
                                    <option value="">City</option> 
                                    <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Jaipur') ? "selected='selected'" : '' ?> value="Jaipur">Jaipur</option> 
                                    <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Jodhpur') ? "selected='selected'" : '' ?> value="Jodhpur">Jodhpur</option> 
                                    <option <?php echo (isset($_POST['city']) && $_POST['city'] == 'Bikaner') ? "selected='selected'" : '' ?> value="Bikaner">Bikaner</option> 

                                </select>
                                <br> <span class="error" id="city_error"><?php echo $city_error ?></span>
                            </td>
                        </tr>
                        <tr>
                            <td class="td5"><b> DOB</b> <span class="required">* </span></td>
                            <td>
                                <div >
                                    <input id="date" type="text" class="datepicker" name="date" style="width: 197px"  value="<?php echo isset($_POST['date']) ? $_POST['date'] : '' ?>">
                                </div>
                                
                                <span class="error" id="date_error"> <?php echo $date_error ?></span>

                            </td>
                            <td class="td5">
                                <b>  Hobbies </b><span class="required">* </span>
                            </td>
                            <td class="td5">
                                <input  type="checkbox" id="java" name="hobbies[]" value="Java"  <?php echo (isset($_POST['hobbies']) && in_array('Java', $_POST['hobbies'])) ? "checked='checked'" : '' ?> />Java
                                <input type="checkbox" id="android" name="hobbies[]" value="Android" <?php echo (isset($_POST['hobbies']) && in_array('Android', $_POST['hobbies'])) ? "checked='checked'" : '' ?> />Android
                                <br>  <span class="error" id="hobbies_error"> <?php echo $hobbies_error ?></span>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td class="td5" >
                                <b>Profile Picture</b><span class="required">* </span>
                            </td>
                            <td colspan="3">
                                <input type="file" name="file" id="fileToUpload">
                                <br>  <span class="error" id="file_error"> <?php echo $file_error ?></span>
                               
                            </td>
                        </tr>
                        <td colspan="2">
                             <br> <p class="file_rules">1.Image type should be jpeg/jpg/png/gif <br>2.Image size must be less then 5MB <br>3.image hight and width must be less then 500px </p>
                        </td>

                        <td colspan="2">

                            <!--Submit Button for signup form-->

                            <input id="submit" type="submit" value="submit" class="b1"/>


                        </td>

                        </tr>

                    </table>

                </fieldset>
            </form>        
        </div>


<!--jquery for date picker -->
        <script>
            $(function(){
               $('.datepicker').datepicker(); 
            });
        </script>


    </body>
</html> 