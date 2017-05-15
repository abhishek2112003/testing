<?php
ob_start();
include 'db.php';
include 'zip_file_creation.php';
if (isset($_SESSION['role']) || isset($_COOKIE['role'])) {



    $id = $role = $firstname_header = $lastname_header = '';

    $count = 1;
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
        $id = $_SESSION['id'];
        $firstname_header = $_SESSION['firstname'];
        $lastname_header = $_SESSION['lastname'];
    } else {
        $role = $_COOKIE['role'];
        $id = $_COOKIE['id'];
        $firstname_header = $_COOKIE['firstname'];
        $lastname_header = $_COOKIE['lastname'];
    }
} else {

    header('location:login.php');
}
$logout = "logout";

$num_rec_per_page = 5;
if (isset($_GET['page']) && $_GET['page'] > 1) {
    $count = ($num_rec_per_page * $_GET['page']) - ($num_rec_per_page - 1);
} else {
    $count = 1;
}

if (isset($_GET["page"])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$start_from = ($page - 1) * $num_rec_per_page;
$image_array = array();
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
                <span> <a href="logout.php"><?php echo $logout ?></a></span>


            </div>


        </div> 
        <div style="width: 100%"> 
            <img class="td6" src="images/13.jpg" alt="Home on rent" />
        </div> 

        <h2>Welcome <?php echo " $firstname_header " . " " . "$lastname_header"; ?></h2>
        <h4><span> <a href="pdf_creation.php">Get PDF</a></span></h4>

        <table border="1">
            <thead>
            <th>S. No.</th>
            <th class="show_data ">Profile Picture</th>
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
if ($role == "Admin") {



    $rs_result = mysql_query("SELECT  * FROM `registration` LIMIT $start_from, $num_rec_per_page ") or die(mysql_error());
} else {


    $rs_result = mysql_query("SELECT  * FROM `registration` where `id`= '$id' LIMIT 1") or die(mysql_error());
}


while ($row = mysql_fetch_assoc($rs_result)) {
    $image_array[] = $row['image'];
    ?> 
            <tr>
                <td align="center"> <?php echo $count;
        $count++
    ?>
                </td>
                <td  align="center"> 
            <?php // print_r($row);?>
                    <a class="listingprofile" href="<?php echo $row['image'] ?>" ><img src="<?php echo $row['image'] ?>" alt="<?php echo ucfirst($row['firstname']) ?>" width="50" height="50" ></a>
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
                    &nbsp;&nbsp;&nbsp; 
                    <?php if ($id != $row['id']) { ?>
                        <span> <a style="text-decoration: none" onclick="return confirm(' are you sure you want to delete?')" href="delete_data.php?id=<?php echo $row['id']; ?>&image=<?php echo $row['image'] ?>  "><img src="images/delete.png" alt="delete" width="20" height="20" ></a></span>
                    <?php } ?>
                </td>


            </tr>
    <?php
}
?>

    </table>

</div>
<script>
//script for use of colorbox

    $(document).ready(function () {

        $(".listingprofile").colorbox();

    });

</script>
<!-- Zip file download link        -->
<span> <a href="?zip=1" ><img src="images/icon_zip_50.png" alt="edit" width="30" height="30" >Zip file download</a></span>         
        <?php
        if ($_SESSION['role'] == 'Admin') {
            $rs_result = array();

            $sql = "SELECT * FROM `registration` ";
            $rs_result = mysql_query($sql);




            $total_records = mysql_num_rows($rs_result);  //count number of records

            $total_pages = ceil($total_records / $num_rec_per_page);
            ?>

    <div style="margin-left: 1100px">
    <?php
    echo "<a  href='select_data.php?page=1'>" . 'First' . "</a> "; // Goto 1st page  

    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<a  href='select_data.php?page=" . $i . "'>" . $i . "</a> ";
    };

    echo "<a href='select_data.php?page=$total_pages '>" . 'Last' . "</a> "; // Goto last page
}
// zip file create function call

if (isset($_GET['zip'])) {
    $zip = time() . 'zip';
    create_zip($image_array, 'my-archive.zip');
}
ob_end_flush();
?>


</table>

</fieldset>
</body>
</html> 
