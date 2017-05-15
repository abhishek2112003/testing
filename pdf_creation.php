<?php


 include('pdf17/fpdf.php');
 include 'db.php';
 $id= '';
if (!isset($_SESSION['role']) )
{
    header('location:login.php');
}
 $pdf=new FPDF();

 //Creating new pdf page
 $pdf->AddPage();


  $pdf->SetRightMargin(50);
  $margin;

  // Insert a logo in the top-left corner at 300 dpi
  //$pdf->Image('images/logo7.png',5,5,-100);
  // Insert a dynamic image from a URL
  $pdf->Ln(20);

  $pdf->Ln();
  //Set the base font & size
  $pdf->SetFont('Arial','B',12); 
  $pdf->Cell(200,5,"User Details ",0,0,'C');
  //Creating new line
  $pdf->Ln();
  $pdf->Ln();
  $pdf->SetFont('Arial','B',8);
  
  $pdf->Cell(14,5," Image",1,0,'C');
  $pdf->Cell(25,5," Username",1,0,'C');
  $pdf->Cell(25,5," Full Name",1,0,'C');
  $pdf->Cell(40,5," Email",1,0,'C');
  $pdf->Cell(20,5," DOB",1,0,'C');
  $pdf->Cell(15,5," Gender",1,0,'C');
  $pdf->Cell(20,5,"Hobbies ",1,0,'C');
  $pdf->Cell(30,5,"Address",1,0,'C');
  $pdf->Cell(10,5,"Role",1,0,'C');
  $pdf->Ln();
      



   // Fetch data from table
     //$c=$_SESSION['user'];
         if($_SESSION['role']=="Admin")
        {
         
        $data = mysql_query("select `image`, `username`, CONCAT(`firstname`,' ', `lastname`) AS `name`,`email`,`gender`,`dob`,`hobbies`,`role`,CONCAT(`address`,',', `location`) AS `address` from registration ") or die(mysql_error());
        }
        elseif($_SESSION['role']=="user")
        {
            $id= $_SESSION['id'];
            $data = mysql_query("select `image`, `username`, CONCAT(`firstname`,' ', `lastname`) AS `name`,`email`,`gender`,`dob`,`hobbies`,`role`,CONCAT(`address`,',', `location`) AS `address` from registration WHERE `id`=$id ") or die(mysql_error());
        }        
        // output data of each row
           while ($row = mysql_fetch_assoc($data)) {
            $pdf->SetFont('Arial','',6);
            $pdf->Cell(14,10, $pdf->Image($row['image'], $pdf->GetX()+2, $pdf->GetY(),10,10), 1, 0, 'C' );
            $pdf->Cell(25,10,$row['username'],1,0,'C'); 
            $pdf->Cell(25,10,$row['name'],1,0,'C');
            $pdf->Cell(40,10,$row['email'],1,0,'C');
            $pdf->Cell(20,10,date("d M Y", strtotime($row['dob'])),1,0,'C');
            $pdf->Cell(15,10,$row['gender'],1,0,'C');
            $pdf->Cell(20,10,$row['hobbies'],1,0,'C');
            $pdf->Cell(30,10,$row['address'],1,0,'C');
            $pdf->Cell(10,10,$row['role'],1,0,'C');
            $pdf->Ln();
            
          
            
    }
    
     $pdf->Output();
