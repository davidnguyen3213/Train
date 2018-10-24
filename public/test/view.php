<?php
    session_start();
    require_once('connect.php');
    include_once('header.php');
    $check = "SELECT * FROM upload";
    $query = mysqli_query($connection,$check);
    ?>
    <body>
    <div class="container">
    <h2>Bordered Table</h2>
    <p>The .table-bordered class adds borders on all sides of the table and the cells:</p>            
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>Name</th>
            <th>Size</th>
            <th>Type</th>
            <th>Location</th>
            <th>View</th>
        </tr>
        </thead>
        <tbody>
        <tr>
        <?php
        
        while($array = mysqli_fetch_assoc($query)){

            // echo '<pre>';
            // print_r($array['name']);
            // echo '</pre>';
            ?>

            <td><?php echo $array['name']; ?></td>
            <td><?php echo $array['size'];?></td>
            <td><?php echo $array['type'];?></td>
            <td><?php echo $array['location'];?></td>
            <td> <a href ="show.php?id =<?php echo $array['id'];?>&&location=<?php echo $array['location'];?>&&name=<?php echo $array['name'];?>&&type=<?php echo $array['type'];?>">views</a></td>
        </tr>
        <?php        
            }
        ?>
        </tbody>
    </table>
    </div>
</body>
        


