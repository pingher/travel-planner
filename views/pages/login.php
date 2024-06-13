
<?php 
    $title = "Log In";
    function get_content(){
?>
    
<div class="container-fluid m-0 p-0 login-page d-flex justify-content-center align-items-center">
    <div class="formbox p-4 d-flex justify-content-center align-items-center">
        <div class="formvalue">
            <form action="../../controllers/users/login_process.php" method="POST">
                <h2 class="text-center raleway text-white">Login</h2>

                <div class="inputbox">
                    <ion-icon name="person"></ion-icon>
                    <input type="text" name="username" required>
                    <label for="">Username</label>
                </div>

                <div class="inputbox">
                    <ion-icon name="lock"></ion-icon>
                    <input type="password" name="password" required>
                    <label for="">Password</label>
                </div>

                <button class="loginbutton">Log In</button>

                <div class="register">
                    <p>Don't have an Account? <a href="register.php">Register</a></p>
                </div>

            </form>
        </div>
    </div>
</div>

<?php
    }
    require_once '../templates/layout.php';
?>