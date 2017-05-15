<?php
include 'db.php';
$count=0;
$num_rec_per_page = 1;
if(isset($_GET['page']) && $_GET['page']>1 )
{
    $count= ($num_rec_per_page * $_GET['page'])-($num_rec_per_page-1);
    
}
else{
    $count=1;
}

if (isset($_GET["page"])) {
  $page= $_GET['page'];
    
} else {
    $page = 1;
}

$start_from = ($page - 1) * $num_rec_per_page;

$sql = "SELECT * FROM registration LIMIT $start_from, $num_rec_per_page";
$rs_result = mysql_query($sql); //run the query
?> 
<html>

    <head>
        <title>Data Listing</title>

        <link rel="stylesheet" type="text/css" href="css/main.css">
        <script src="jscript/jquery.min.js"></script>
        <script src="jscript/jquery.colorbox.js"></script>
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

        <h2>Your Details are as follows:</h2>

        <div>

            <table border="1">
                <thead>
                <th>S. No.</th>
                <th class="show_data ">pic</th>
                <th class="show_data left_align">Username</th>
                <th class="show_data left_align">Name</th>

                <th class="show_data left_align">Email</th>
                <th class="show_data"  >Gender</th>
                <th class="show_data left_align">Address</th>
                <th class="show_data " >Location</th>
                <th class="show_data" >DOB</th>
                <th class="show_data left_align" >Hobbies</th>
                <th class="show_data"> Action</th>

                </thead>
                <?php
                
                while ($row = mysql_fetch_assoc($rs_result)) {
                    ?>  <tr>
                        <td align="center"> <?php echo $count;
                $count++
                ?>
                        </td>
                        <td  align="center"> 
                            <?php // print_r($row);?>
                            <a class="listingprofile" href="download_file.php?image=<?php echo $row['image'] ?>" ><img src="<?php echo $row['image'] ?>" alt="<?php echo ucfirst($row['firstname']) ?>" width="50" height="50" ></a>
                            <br><span> <a style="text-decoration: none" href="download_file.php?image=<?php echo $row['image'] ?>"><img src="images/file_download.png" alt="edit" width="20" height="20" ></a></span>
                        </td>
                        <td> <?php echo $row["username"] ?></td>
                        <td> <?php echo ucfirst($row['firstname']) . " " . ucfirst($row['lastname']); ?></td>
                        <td> <?php echo $row['email'] ?></td> 
                        <td align="center"> <?php echo ucfirst($row['gender']) ?></td>
                        <td> <?php echo ucfirst($row['address']) ?></td>
                        <td align="center"> <?php echo $row['location'] ?></td>
                        <td align="center"> <?php echo date("d M Y", strtotime($row['dob'])); ?></td>
                        <td > <?php echo $row['hobbies'] ?></td>
                        <td> <span> <a style="text-decoration: none" href="update_data.php?id=<?php echo $row['id']; ?>  ">&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/edit.gif" alt="edit" width="20" height="20" ></a></span>
                            &nbsp;&nbsp;&nbsp; <span> <a style="text-decoration: none" onclick="return confirm(' are you sure you want to delete?')" href="delete_data.php?id=<?php echo $row['id']; ?>&image=<?php echo $row['image'] ?>  "><img src="images/delete.png" alt="delete" width="20" height="20" ></a></span>
                        </td>


                    </tr>
                    <?php
// to show data in sequential manner
//           echo "Id :{$row[0]}  <br> ".
//              "User Name :{$row[1]}  <br> ".
//         "First Name : {$row[3]} <br> ".
//         "Last Name : {$row[4]} <br> ".
//                 "Email : {$row[5]} <br> ".
//                         "Gender : {$row[6]} <br> ".
//                                 "Address : {$row[7]} <br> ".
//                                         "City : {$row[8]} <br> ".
//                                                 "DOB : {$row[9]} <br> ".
//                                                         "Hobbies : {$row[10]} <br> ".
//                                                                
//         "--------------------------------<br>";
                }
                ?>

            </table>

<?php
$rs_result = array();

$sql = "SELECT * FROM `registration` ";
$rs_result = mysql_query($sql);




$total_records = mysql_num_rows($rs_result);  //count number of records

$total_pages = ceil($total_records / $num_rec_per_page); ?>
<div style="margin-left: 1100px">
<?php
echo "<a  href='pagination.php?page=1'>" . 'First' . "</a> "; // Goto 1st page  

for ($i = 1; $i <= $total_pages; $i++) {
    echo "<a  href='pagination.php?page=" . $i . "'>" . $i . "</a> ";
    
};

echo "<a href='pagination.php?page=$total_pages '>" . 'Last' . "</a> "; // Goto last page
?>
            
 </div>

</html>