<?php
    require ('connect.php');
    if(isset($_FILES['image'])){
        $errors= array();
        $file_name = $_FILES['image']['name'];
        $file_size =$_FILES['image']['size'];
        $file_tmp =$_FILES['image']['tmp_name'];
        $file_type=$_FILES['image']['type'];
        $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
        $file_ext2=explode('.',$_FILES['image']['name']);
        $file_realname = $file_ext2['0'];
        $expensions= array("jpeg","jpg","png");
        if(in_array($file_ext,$expensions)=== false){
            $errors[]="extension not allowed, please choose a JPEG or PNG file.";
        }
        
        if($file_size > 2097152){
            $errors[]='File size must be excately 2 MB';
        }
        
        if(empty($errors)==true){
            $location = $_POST['location'];
            $check = move_uploaded_file($file_tmp,"$location/".$file_name);
            
            if(isset($check)){
                $insert = "INSERT INTO upload(name,size,type,location)Values ('$file_realname','$file_size','$file_ext','$location')";
                $query = mysqli_query($connection,$insert);
                if($insert){
                    echo "Success";
                }
                else{
                    echo 'insert fail';
                }                
            }
            else{
                return ['status'=>'upload false'];
            }
        }else{
            print_r($errors);
        }
    }
?>
<html>
    <body>
        <a href="view.php"><p>xem các ảnh hiển thị</p></a>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name = 'location' value = 'images'>
            <input type="file" name="image" />
            <input type="submit"/>
        </form>
      
    </body>
</html>