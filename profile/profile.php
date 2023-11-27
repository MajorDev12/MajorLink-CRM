<?php
$conn = mysqli_connect("localhost", "root", "123456", "imgdb");

$_SESSION["id"] = 1;

$session = $_SESSION["id"];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM user WHERE id = $session"));

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
        }

        form {
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .upload {
            width: 140px;
            position: relative;
            margin: auto;
            text-align: center;
        }

        .upload img {
            border-radius: 50%;
            border: 8px solid grey;
            width: 125px;
            height: 125px;
        }

        .upload .rightRound {
            position: absolute;
            bottom: 0;
            right: 0;
            background-color: green;
            width: 32px;
            height: 32px;
            line-height: 33px;
            text-align: center;
            border-top-left-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .upload .leftRound {
            position: absolute;
            bottom: 0;
            left: 0;
            background-color: red;
            width: 32px;
            height: 32px;
            line-height: 33px;
            text-align: center;
            border-top-left-radius: 50%;
            overflow: hidden;
            cursor: pointer;
        }

        .upload #cancel {
            display: none;
        }

        .upload #confirm {
            display: none;
        }

        .upload .fa {
            color: wheat;
        }

        .upload input {
            position: absolute;
            transform: scale(2);
            opacity: 0;
        }

        .upload input::-webkit-file-upload-button,
        .upload input[type=submit] {
            cursor: pointer;
        }
    </style>
</head>

<body>

    <form action="" enctype="multipart/form-data" method="post">
        <div class="col-md-4 text-center">
            <input type="hidden" name="id" id="id" value="<?= $user['id']; ?>">
            <div class="upload">
                <img id="image" src="../img/<?= $user['profilePic']; ?>" class="img-fluid rounded-start" alt="..." width="200px" height="100px">

                <div class="rightRound" id="upload">
                    <input type="file" name="fileImg" id="fileImg" accept=".jpg, .jpeg, .png">
                    <i class="fa fa-camera camera"></i>
                </div>

                <div class="leftRound" id="cancel">
                    <!-- <i class="fa fa-camera"></i> -->
                    <i class="fa fa-times"></i>
                </div>

                <div class="rightRound" id="confirm">
                    <input type="submit" name="" value="">
                    <i class="fa fa-check"></i>
                </div>
            </div>
        </div>

    </form>

</body>

</html>
<script>
    document.getElementById("fileImg").onchange = function() {
        document.getElementById("image").src = URL.createObjectURL(event.target.files[0]);

        document.getElementById("cancel").style.display = "block";
        document.getElementById("confirm").style.display = "block";

        document.getElementById("upload").style.display = "none";
    }

    var userImage = document.getElementById("image").src;
    document.getElementById("cancel").onclick = function() {
        document.getElementById("image").src = userImage;

        document.getElementById("cancel").style.display = "none";
        document.getElementById("confirm").style.display = "none";

        document.getElementById("upload").style.display = "block";


    }
</script>

<?php
if (isset($_FILES["fileImg"]["name"])) {
    $id = $_POST["id"];

    $src = $_FILES["fileImg"]["tmp_name"];
    $imageName = uniqid() . $_FILES["fileImg"]["name"];

    $target = "../img/" . $imageName;

    move_uploaded_file($src, $target);

    $query = "UPDATE user SET profilePic = '$imageName' WHERE id = $id";

    mysqli_query($conn, $query);

    header("location: trial.php");
}
?>