<!DOCTYPE html>
<html lang="en">

<?php include 'pages/head.php'; ?>
<?php include 'admin.php'; ?>
<?php include 'checkAdmin.php'; ?>

<body>
    <div id="app">
        <?php include 'pages/sidebar.php'; ?>
            <div id="main">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                </header>

                <div class="page-heading">
                    <h3>Gallery</h3>
                </div>
                <div class="row">
                    <div class="col-lg-10">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="index.php">Dashboard</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Image Gallery List</li>
                            </ol>
                        </nav>
                    </div>
                    <div class="col-lg-2">
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#GalleryImage"> Add
                            New</button>
                    </div>
                </div>
                <div class="page-content">
                    <section class="row">
                        <div class="col-12 col-lg-12">
                            <div class="row">
                                <table id="datatablesSimple">
                                    <tbody>
                                        <?php
                                        $getall = getAllgalleryImages();

                                        while ($row = mysqli_fetch_assoc($getall)) {

                                            $gallery_id = $row['gallery_id'];
                                            $img = $row['gallery_image'];
                                            $img_src = "../server/uploads/gallery/" . $img; ?>


                                            <tr>
                                                <td><img width="500px" src='<?php echo $img_src; ?>'></td>
                                                <td>

                                                    <button type="button"
                                                        onclick="permenantdeleteData(<?php echo $row['gallery_id']; ?>, 'gallery', 'gallery_id')"
                                                        class="btn btn-darkblue"> <i class="fa-solid fa-trash"></i>
                                                    </button>

                                                </td>
                                            </tr>

                                        <?php } ?>

                                    </tbody>
                                </table>

                            </div>
                        </div>

                    </section>
                </div>

                <?php include 'pages/footer.php'; ?>
            </div>
        </div>


        <!-- Modal -->
        <div class="modal fade" id="GalleryImage" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Gallery Image Uploading</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form enctype="multipart/form-data" method="POST">
                            <div class="mb-3">
                                <input onchange="insertImage(this.form);" class="form-control" name="file" type="file"
                                    id="formFile">
                            </div>
                        </form>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/vendors/apexcharts/apexcharts.js"></script>
    <script src="assets/js/pages/dashboard.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>