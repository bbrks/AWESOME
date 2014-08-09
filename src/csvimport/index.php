<p><a href="uploadcsv.php">Here, stupid</a></p>
<br>
<?php
if (!function_exists('mysqli_init') && !extension_loaded('mysqli')) {
    echo 'We don\'t have mysqli!!!';
} else {
    echo 'Phew we have mysqli!';
}
?>
