<?php

$pageName  = "Deleting";
include($_SERVER['DOCUMENT_ROOT'] . "/admin/layout/header.php");

$id = $_GET['id'];

// Use prepared statement to avoid SQL injection
$sql = "DELETE FROM crypto_currency WHERE crypto_currency.id = :id";
$stmt = $conn->prepare($sql);
$stmt->execute([
    'id' => $id
]);

if ($stmt->rowCount() > 0) {
    $msg1 = "
        <div class='alert alert-warning'>
        
        <script type='text/javascript'>
             
                function Redirect() {
                window.location='./payments.php';
                }
                document.write ('');
                setTimeout('Redirect()', 3000);
             
                </script>
                
        <center><img src='../assets/images/loading.gif' width='180px'  /></center>
        
        
        <center>	<strong style='color:black;'>Deleted, Please Wait while we redirect you...
               </strong></center>
          </div>
        ";
} else {
    toast_alert('danger', 'Sorry Something Went Wrong', 'Error');
}

// header('Location:./wire-trans');

?>

<!-- Ofofonobs Developer WhatsAPP +2348114313795 -->

<div class="row">
    <?php if (isset($msg1)) echo $msg1; ?>
</div>
