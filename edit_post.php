<?php
include 'config.php';

include '.includes/header.php';

$postIdToEdit = $_GET['post_id'];

$query = "SELECT * FROM posts WHERE id_post = $postIdToEdit";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    $post = $result->fetch_assoc();

} else {
    echo "Post Not Found";
    exit();
}
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-10">
            <div class="card mb-4">
                <div class="card-body">
                    <form method="POST" action="proses_post.php" enctype="multipart/form-data">
                        <input type="hidden" name="post_id" value="<?php echo $postIdToEdit; ?>">

                        <div class="mb-3">
                            <label for="post_title" class="form-label">Judul Postingan</label>
                            <input type="text" class="form-control" id="post_title" name="post_title" value="<?php echo $post['psot_title']; ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="formFile" class="form-label">Unggah Gambar</label>
                            <input type="file" class="form-control" id="formFile" name="iamge_path" accept="image/*">
                            <?php if (!empty($post['image_path'])): ?>
                            <!--menampilkan gambar yang sudah diunggah-->
                            <div class="mt-2">
                                <img src="<?= $post['iamge_post']; ?>" alt="Current Image" class="img-thumbnail" style="max-width: 200px;">
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="mb-3">
                            <label for="category_id" class="form-label">Kategori</label>
                            <select name="category_id" id="category_id" class="form-select" required>
                                <option value="" selected disabled>Select one</option>
                                  <?php
                                    $queryCategories = "SELECT * FROM categories";
                                    $resultCategories = $conn->query($queryCategories);
                                    if ($resultCategories->num_rows>0) {
                                        while ($row = $resultCategories->fetch_assoc()){
                                            $selected = ($row["category_id"] == $post['category_id']) ? "selected" : "";
                                            echo "<option value='" . $row["category_id"] . "' $selected>" . $row["category_name"] .  "</option>";
                                        }
                                    }
                                  ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="content" class="form-label">Konten</label>
                            <textarea name="content" id="content" class="form-control"required><?php echo $post['content']; ?></textarea>
                        </div>
                        <button type="submit" name="update" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
include '.includes/footer.php';
?>