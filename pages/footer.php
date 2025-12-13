<?php
// Load settings if not already loaded
if (!isset($res)) {
    if (!function_exists('getAllSettings')) {
        include 'server/inc/get.php';
    }
    if (function_exists('getAllSettings')) {
        $setting = getAllSettings();
        $res = mysqli_fetch_assoc($setting);
    } else {
        // Fallback if getAllSettings doesn't exist
        $res = array(
            'about_desc' => 'FOJ Express provides fast and reliable courier services.',
            'link_facebook' => '#',
            'link_twiiter' => '#',
            'link_instragram' => '#'
        );
    }
}
?>
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6 mr-auto">
                        <h2 class="footer-heading mb-4">About Us</h2>
                        <p><?php echo isset($res['about_desc']) ? htmlspecialchars($res['about_desc']) : 'FOJ Express provides fast and reliable courier services.'; ?></p>
                    </div>

                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4">Quick Links</h2>
                        <ul class="list-unstyled">
                            <li><a href="index.php">Home</a></li>
                            <li><a href="#section-about">About Us</a></li>
                            <li><a href="#section-gallery">Gallery</a></li>
                            <li><a href="#section-contact">Contact Us</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h2 class="footer-heading mb-4">Follow Us</h2>
                        <a href="<?php echo isset($res['link_facebook']) ? htmlspecialchars($res['link_facebook']) : '#'; ?>" class="pl-0 pr-3"><span
                                class="icon-facebook"></span></a>
                        <a href="<?php echo isset($res['link_twiiter']) ? htmlspecialchars($res['link_twiiter']) : '#'; ?>" class="pl-3 pr-3"><span
                                class="icon-twitter"></span></a>
                        <a href="<?php echo isset($res['link_instragram']) ? htmlspecialchars($res['link_instragram']) : '#'; ?>" class="pl-3 pr-3"><span
                                class="icon-instagram"></span></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row pt-5 mt-5 text-center">
            <div class="col-md-12">
                <div class="border-top pt-5">
                    <p>
                        Copyright &copy;
                        <script>
                            document.write(new Date().getFullYear());
                        </script> All rights reserved | FOJ Express
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>