<?php
include 'db.php';
$logout= $message= '';

$flag= isset($_GET['flag'])? $_GET['flag']:'';
$error =$error_username =$error_password = $username = $password = '';
$error_flag = 0;
if($flag==1)
{
  $message= "You have successfully registered, Please login to continue.";  
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    
    
    
    if (empty($_POST['username']))
    {
       
       $error_username = "Username is required";
       $error_flag=1;
    }
    if(empty($_POST['password']))
    {  $error_flag=1;
       $error_password = "Password is required.";
    }
    
    if($error_flag==0)
    {
        $username = $_POST['username'];
        $password = md5($_POST['password']);
   
        $info = mysql_query("select * from `registration` where `username`='$username' LIMIT 1")or die(mysql_error());
     
        $row = mysql_fetch_assoc($info);
   
    
        if($password == $row['password'])
        {
        
        $_SESSION['firstname'] = $row["firstname"];
        $_SESSION['lastname']= $row["lastname"];
        $_SESSION['id'] = $row["id"];
        $_SESSION['role'] = $row["role"];
        
        if(isset($_POST['remember_me']))
        {
        setcookie("id",$row["id"] , time()+60*60*24*1, "/");
        setcookie("role", $row["role"], time()+60*60*24*1, "/");
        setcookie("firstname", $row["firstname"], time()+60*60*24*1, "/");
        setcookie("lastname", $row["lastname"], time()+60*60*24*1, "/");
        
        }

        header('location:select_data.php');
        }
        
        else
        {
        $error= "Username or Password is incorrect.";
        }
    
    }
    
}
?>




<html>
    <!--  Page for sign up   -->
    <head>
        <title>Index</title>
        <link rel="stylesheet" type="text/css" href="css/main.css">
        <script src="jscript/jquery.min.js"></script>
    </head>
    <body>
        <!--Header Part -->
        <div class="header">
            <!--logo part            -->
            <div class="logo">
                <a href="index.html"><img src="images/download.jpg" alt="Home" width="50" height="50" ></a>
            </div>
            <!-- Menu Bar            -->
            <div class="menu">

                <span> <a id="index" href="index.html">Home</a></span>
                <span> <a href="insert_data.php">Sign Up</a></span>
              
                
            </div>
            

            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" enctype="multipart/form-data">      
        </div> 
        <div style="width: 100%">
            <img class="td6" src="images/13.jpg" alt="Home on rent" />
        </div>

        <h2>Login Page</h2>
        <h4><?php echo $message ?></h4>
        <div class="login_table">
            <table>
                <tr>
                    <td><b>Username</b><span class="required">*</span><br></td>
                    <td>
                        <input type="Text" name="username"  value="<?php echo isset($_POST['username']) ? $_POST['username'] : '' ?>"> <br>
                         <p class="error"><?php echo $error_username ?></p>

                    </td>

                </tr>

                <tr>
                    <td><b>Password</b><span class="required">*</span><br>
                        
                    </td>
                    <td>
                        <input type="password" name="password" > <br>
                        <p class="error"><?php echo $error_password ?></p>
                        
                    </td>
                <tr>
                    <td></td>
                    <td>
                        <p class="error"> <?php  echo $error ?></p>
                    </td>
                </tr>


                </tr>
                <tr>
                    <td  colspan="2">
                        <input style="margin: 13px 0px 0px 102px;" type="checkbox" name="remember_me" value="remember_me" <?php echo (isset($_POST['remember_me'])) ? "checked='checked'" : '' ?>>Remember me
                    </td>
                    



                </tr>
                <tr>
                    <td  colspan="2">
                        <input style="margin: 8px 0px 0px 104px;" type="submit" name="submit" value="Login">   
                    </td>

                </tr>
            </table>
            

        </div>





    </body>
</html> 