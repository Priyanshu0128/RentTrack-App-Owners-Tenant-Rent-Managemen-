<?php
session_start();

if(isset($_COOKIE['jwt'])) {
    setcookie('jwt', '', time() - 3600, '/'); 

    echo "<script>window.location='index.php'</script>";
}

?>


