<?php
 include 'db.php';

 
 
 
  if (isset($_POST['submit'])) {
    $filename = $_FILES['file']['name'];
    $filetype = $_FILES['file']['type'];
    
    if ($filetype == 'image/jpeg' or $filetype == 'image/png' or $filetype == 'image/gif') {

        if(!move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $filename))
        {
            echo 'File not uploded.'; exit;
        }
       
        $filepath = "uploads/" . $filename;

        $query = "INSERT into `images` (file_name, file_path) VALUES('$filename','$filepath')";
        
        if(!mysql_query($query))
        {
            $err   = mysql_error(); 
            print_r($err); exit;
        }


        header('location:fileuploading.php');
    }
}
?>
 <html>
    <!--  Page for sign up   -->
    <head>
        <title>File Upload</title>
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
                <span><a id="signup" href="Signup.html">Signup</a></span>
                <span><a id="details" href="Details.html">Details</a></span>
                <span> <a id="listing" href="listing.html">Listing</a></span>
                <span> <a href="jquery.html">Jquery Form</a></span>
                <span> <a href="formdemo.php">Php Demo</a></span>
                <span> <a href="database_handler.php">Database Handler</a></span>


            </div>
        </div> 
        <div style="width: 100%">
            <img class="td6" src="images/13.jpg" alt="Home on rent" />
        </div>

        <h2>File Upload</h2>

        <form action="fileuploading.php" method="post" enctype="multipart/form-data">
            Select file to upload:

            <input type="file" name="file" id="fileToUpload">

            <input type="submit" value="Upload" name="submit">
            
            <table  border="1">
                <thead>
                <th >S. No.</th>
                <th width="400" >Image Name</th>
                <th width="600">Image</th>
               </thead>
           <?php
               
//count is for serial number with auto incriment         
                $count = 1;
                $data = mysql_query("SELECT  * FROM `images` ") or die(mysql_error());


                while ($row = mysql_fetch_assoc($data)) {
                    ?> <tr>
                        <td align="center"> <?php echo $count;
                        $count++ ?>
                        </td >
                        <td align="center"> <?php echo ucfirst($row['file_name'])?></td>
                        
                        <td align="center"> <img src="<?php echo $row['file_name']?>" alt="<?php echo ucfirst($row['file_name'])?>" width="50" height="50" ></td> 
                    </tr>
                <?php } ?>
           
     
            </table>

      </form>

    </body>
</html> 