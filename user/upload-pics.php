<?php
$pageName  = "Upload Picture";
include($_SERVER['DOCUMENT_ROOT'] . "/user/layout/header.php");

// Define allowed file types
$allowed = array('jpg', 'jpeg', 'png');

// Function to generate a unique file name
function generateUniqueFileName($fileName) {
    return uniqid(time() . '_') . '.' . pathinfo($fileName, PATHINFO_EXTENSION);
}

if (isset($_POST['upload_picture'])) {
    if (isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $name = basename($file['name']); // Ensure no path traversal

        // Get file extension
        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

        // Validate file type
        if (!in_array($fileExt, $allowed)) {
            echo "Invalid file type. Please upload JPG, JPEG, or PNG images.";
            exit;
        }

        // Verify that the uploaded file is actually an image
        $fileInfo = getimagesize($file['tmp_name']);
        if ($fileInfo === false) {
            echo "The uploaded file is not a valid image.";
            exit;
        }

        // Sanitize file name (remove unwanted characters)
        $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", $name);

        // Define destination folder and create unique file name
        $folder = "../assets/user/profile/";
        $uniqueFileName = generateUniqueFileName($sanitizedFileName);
        $destination = $folder . $uniqueFileName;

        // Try to move the uploaded file to the destination folder
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            // Prepare SQL query to update user image
            $sql = "UPDATE users SET acct_image = :image WHERE id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->execute([
                'image' => $uniqueFileName,
                'user_id' => $user_id
            ]);

            // Success message with auto redirection
            $msg1 = "
            <div class='alert alert-warning'>
                <script type='text/javascript'>
                    function Redirect() {
                        window.location='./settings.php';
                    }
                    document.write ('');
                    setTimeout('Redirect()', 5000);
                </script>
                <center><img src='../assets/images/loading.gif' width='180px' /></center>
                <center><strong style='color:black;'>Uploaded Successfully, Please Wait while we redirect you...</strong></center>
            </div>";
        } else {
            echo "There was an error uploading your file.";
        }
    } else {
        echo "No file selected for upload.";
    }
}

?>

<!-- App Header -->
<div class="appHeader">
    <div class="left">
        <a href="javascript:history.go(-1)" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">
        <?= $pageName ?>
    </div>
    <div class="right">
        <a onclick="location.reload();" class="headerButton">
            <ion-icon name="refresh"></ion-icon>
        </a>
    </div>
</div>
<!-- * App Header -->

<br>

<div class="col-12">
    <?php if (isset($msg1)) echo $msg1; ?>
    <br>
    <div class="card mb-5">
        <div class="card-body">
            <form method="POST" id="general-info" enctype="multipart/form-data">
                <div class="form-group basic">
                    <div class="input-wrapper">
                        <label class="label">Upload Picture</label><br>
                        <input type="file" id="input-file-max-fs" required class="form-control" name="image"
                            data-max-file-size="2M" />
                        <input type="hidden" name="MAX_FILE_SIZE" value="10485760" />
                        <i class="clear-input">
                            <ion-icon name="close-circle"></ion-icon>
                        </i>
                    </div>
                </div><br>
                <div class="form-group basic">
                    <div class="row">
                        <div class="col-6">
                            <a href="<?= $web_url ?>/user/settings.php"
                                class="btn btn-lg btn-danger cancel btn-block">Go Back</a>
                        </div>
                        <div class="col-6">
                            <button type="submit" class="btn btn-lg btn-primary btn-block"
                                name="upload_picture">Upload</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include($_SERVER['DOCUMENT_ROOT'] . "/user/layout/bottom.php");
include($_SERVER['DOCUMENT_ROOT'] . "/user/layout/footer.php");
?>