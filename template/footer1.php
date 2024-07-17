<?php
$name="";
if(isset($_GET['email']))
{
     $email=$_GET['email'];
     //echo $email;
}
if (isset($_GET['name'])) {
    # code...

    $name=$_GET['name'];
    //echo $name;

}
?>


    <!-- Footer Section Begin -->

<footer class="footer spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="footer__about">
                        <?php if($name == "" && $name == NULL): ?>
                        <div class="footer__about__logo">
                        <a href="./index1.php"><img src="img/logo.png" alt=""></a>
                    </div>
                    <?php endif; ?>
                    <?php if($name != "" && $name != NULL): ?>
                        <div class="footer__about__logo">
                        <a href="./index1.php?name=<?= $name ?>&email=<?= $email ?>"><img src="img/logo.png" alt=""></a>
                    </div>
                    <?php endif; ?>
                        
                        <ul class="text-center">
                            <li>Address: Windsor, ON, Canada</li>
                            <li>Email: farmease@gmail.com</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-6 offset-lg-1">
                    <div class="footer__widget">
                        <h6>Useful Links</h6>
                        <ul>
                            <li><a href="home.php">Home</a></li>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-4 col-md-12">
                    <div class="footer__widget">
                        <h6>Contact us</h6>
                        <p>Get E-mail updates about our latest shop and special offers.</p>
                        <form action="#">
                            <input type="text" placeholder="farmease@gmail.com
">
                            <button type="submit" class="site-btn">Subscribe</button>
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="footer__copyright">
                        <div class="footer__copyright__text"><p>
  Copyright &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved</p></div>
                        
                    </div>
                </div>
            </div>
        </div>
    </footer>
     <!-- Footer Section End -->
