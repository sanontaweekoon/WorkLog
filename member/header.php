<!DOCTYPE html>
<html>
  <head>
    <?php
    session_start();
    include('../condb.php');
    $personel_id = $_SESSION['personel_id'];
    $username = $_SESSION['username'];
    $personel_level = $_SESSION['personel_level'];
    
    if($personel_level!='member'){
    Header("Location: ../logout.php");
    }

    if (!isset($_SESSION['personel_id'])) {
      header("Location: ../logout.php");
    }

    $sql = "SELECT * FROM personel WHERE personel_id= $personel_id";
    $result = mysqli_query($con, $sql) or die ("Error in query: $sql " . mysqli_error($con));
    $rowsession = mysqli_fetch_array($result);
    extract($rowsession);
    $personel_name = $rowsession['personel_name'];
    
    $query_rsmem = "SELECT * FROM personel 
    WHERE username = '$username'
    "or die("Error:" . mysqli_error($con));
    $rsmem = mysqli_query($con, $query_rsmem) or die ("Error in query: $query_rsmem " . mysqli_error($con));   
    $row_rsmem = mysqli_fetch_assoc($rsmem);
    $totalRows_rsmem = mysqli_num_rows($rsmem);

    // $mid = $row_rsmem['personel_number'];
    // $mm = $row_rsmem['personel_name'];

    ?>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin || Backend</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="../bower_components/bootstrap/dist/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="../bower_components/font-awesome/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="../bower_components/Ionicons/css/ionicons.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="../bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="../dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
    folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="../dist/css/skins/_all-skins.min.css">
    <!-- Google Font -->
    <link rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
      <script defer src="https://use.fontawesome.com/releases/v5.0.8/js/all.js"></script>
    </head>