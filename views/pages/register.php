
<?php 
    $title = "Register";
    function get_content(){
?>
    
<div class="container-fluid m-0 p-0 register-page d-flex justify-content-center align-items-center">
    <div class="formbox2 p-4 d-flex justify-content-center align-items-center">
        <div class="formvalue">
            <form action="../../controllers/users/register_process.php" method="POST">
                <h2 class="text-center raleway text-white">Register</h2>

                <div class="inputbox">
                    <ion-icon name="person"></ion-icon>
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="mail"></ion-icon>
                    <input type="email" name="email" required>
                    <label for="">Email</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock"></ion-icon>
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock"></ion-icon>
                    <input type="password" name="password2" required>
                    <label for="">Confirmed Password</label>
                </div>

                <button class="loginbutton">Register</button>

                <div class="register">
                    <p>Already have an Account? <a href="login.php">Login</a></p>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
    }
    require_once '../templates/layout.php';
?>