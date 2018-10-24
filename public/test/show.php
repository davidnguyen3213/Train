<?php
    require_once('header.php');
    session_start();
    $name  = $_GET['name'] ;
    $type = $_GET['type'];
    $location = $_GET['location'];
    $src = $location."/".$name.".".$type;
?>
<img src="<?php echo $src; ?>" alt="fail" style ='width:500px; height :800px;'>
<div> <a href="view.php"> quay về views</a> </div>
<?php session_destroy();?>