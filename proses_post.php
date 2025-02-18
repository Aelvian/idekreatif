<?php
include 'config.php';

session_start();
//mendapatkkan id pengguna dari sesi
$userId = $_SESSION["user_id"];

//menangani form untuk menambahkan postingan baru
if(isset($_POST['simpan'])){

    //mendapatkan data dari form
    $postTitle = $_POST["post_title"]; // judul postingan
    $content = $_POST["content"]; //konten postingan
    $categoryId = $_POST["category_id"]; // id kategori

    //mengaturr direktori penyimpanan file gambar
    $imageDir = "assets/img/uploads/";
    $imageName = $_FILES["image"]["name"]; //nama file gambar
    $imagePath = $imageDir . basename($imageName);//path lengkap gambar


     //memindahkan  file gambar yang diunggah ke direktori tujuan
     if(move_uploaded_file($_FILES["image"]["tmp_name"],$imagePath)){
        // jika unggahan berhasil , masukkan data postingan ke dalam database
        $query = "INSERT INTO posts (post_title, content, created_at, category_id, user_id, image_path) VALUES
        ('$postTitle', '$content', NOW(), $categoryId, $userId, '$imagePath')";

        if($conn->query($query) === TRUE){
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'POST BERHASIL DITAMBAHKAN'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Menambahkan Post' . $conn->error
            ];

        }
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'gagal mengupload image.'

            ];
        }
        header('Location: dashboard.php');
        exit();
     }
     if (isset($_POST['delete'])) {
        $postID = $_POST['postID'];

        $exec = mysqli_query($conn, "DELETE FROM posts WHERE id_post='$postID'");

        if ($exec) {
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'Post Successfully Deleted'
            ];

        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Error Deleting Post' . mysqli_error($conn)

            ];
        }
        header('Location: dashboard.php');
        exit();
     }

     if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update'])) {
        $postId = $_POST['psot_id'];
        $postTitle = $_POST["post_title"];
        $content = $_POST["content"];
        $categoryId = $_POST["category_id"];
        $imageDir = "assets/img/uploads/";

        if(!empty($_FILES["image_path"]["name"])){
            $imageName = $_FILES["iamge_path"]["name"];
            $imagePath = $imageDir . $imageName;

            move_uploaded_file($_FILES["iamge_path"]["tmp_name"], $imagePath);

            $queryOldImage = "SELECT image_path FROM posts WHERE id_post = $postId";
            $resultOldImage = $conn->query($queryOldImage);
            if ($resultOldImage->num_rows>0) {
                $oldImage = $resultOldImage->fetch_assoc()['image_path'];
                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
        } else {
            $ImagePathQuery = "SELECT image_path FROM posts WHERE id_post = $postId";
            $result = $conn->query($ImagePathQuery);
            $imagePath = ($result->num_rows>0) ? $result->fetch_assoc()['image_path'] : null;
        }
        $queryUpdate = "UPDATE posts SET posts_title = '$postTitle',
        content = '$content', category_id = $categoryId,
        image_path = '$imagePath' WHERE id_post = $$postId";

        if ($conn->query($queryUpdate) === TRUE) {
            $_SESSION['notification'] = [
                'type' => 'primary',
                'message' => 'Postingan Berhasil Diperbarui'
            ];
        } else {
            $_SESSION['notification'] = [
                'type' => 'danger',
                'message' => 'Gagal Diperbarui Postingan'
            ];
        }
        header('Location: dashboard.php');
        exit();
     }

     


