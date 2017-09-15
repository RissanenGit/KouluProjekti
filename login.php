<?php
session_start();
if (isset($_SESSION['user']['id'])) {
    $_SESSION['timeout'] = time() + 2700;
    header('Location:index.php?site=About');
    die();
} else {
    ?>
    <!-- Modal-->
    <div id="myModal" class="modal fade" role="dialog"  data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="container">
                        <h2>Login</h2>
                        <form action='controller.php' method="post">
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-sm-4'>
                                        <label for="usr">Username:</label>
                                        <input type="text" class="form-control" name="username" id ="usr">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="row">
                                    <div class='col-sm-4'>
                                        <label for="pwd">Password:</label>
                                        <input type="password" class="form-control" name="password" id="pwd">
                                    </div>
                                </div>
                            </div>
                            <input type="hidden" name="action" value="LogIn">
                            <input type="submit" class="btn btn-info" value="Log in">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php }; ?>
