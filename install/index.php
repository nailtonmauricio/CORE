<?php

session_start();

if(is_dir("config")&(is_file("config.txt"))){
    if(in_array("config.txt", scandir(__DIR__."/config"))){
        header("Location: ../index.php");
    }
}

$_SESSION["msg"] = "<div class='text-center alert alert-info alert-dismissible fade show' role='alert'>"
    ."<strong>Bem vindo!</strong> Antes de inciar o projeto é necessário configurar a base de dados!"
    ."<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button></div>";
?>
<!doctype html>
<html lang="pt-br">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.3.0/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.min.js"></script>
    <title>Sistema</title>
    <style>
        #register_button {
            margin-top: 50px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div class="row justify-content-center">
        <h1 class="text-center">DB Configuration</h1>
        <div class="col-6">
            <?php

            if(isset($_SESSION["msg"])){
                echo $_SESSION["msg"];
                unset($_SESSION["msg"]);
            }
            ?>
            <form name="myForm" method="post" action="process.php" class="needs-validation" novalidate autocomplete="off">
                <div class="mb-3">
                    <label for="host_name" class="form-label">Host name</label>
                    <input type="text" class="form-control" id="host_name" name="host_name"
                           aria-describedby="host_name" minlength="5" required/>
                    <div id="host_name" class="form-text">Please set a host name's.</div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please insert a name with a min length 5.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="db_name" class="form-label">Database</label>
                    <input type="text" class="form-control" id="db_name" name="db_name"
                           aria-describedby="db_name" required/>
                    <div id="db_name" class="form-text">Please set a database name's.</div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please insert a name with a min length 5.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="user_name" class="form-label">User name</label>
                    <input type="text" class="form-control" id="user_name" name="user_name"
                           aria-describedby="user_name" required/>
                    <div id="user_name" class="form-text">Please set an user's name.</div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please insert a valid name.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control document" id="password" name="password"
                           aria-describedby="password"/>
                    <div id="password" class="form-text">Please set your user password</div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please insert a password with a min-length 6.
                    </div>
                </div>
                <div class="mb-3">
                    <label for="charset" class="form-label">Charset</label>
                    <input type="text" class="form-control" id="charset" name="charset" value="utf8"
                           aria-describedby="charset" readonly tabindex="-1"/>
                    <div id="charset" class="form-text">Default charset encode</div>
                    <div class="valid-feedback">
                        Looks good!
                    </div>
                    <div class="invalid-feedback">
                        Please insert a valid encode.
                    </div>
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Optional JavaScript; choose one of the two! -->

<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ"
        crossorigin="anonymous"></script>

<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js" integrity="sha384-W8fXfP3gkOKtndU4JGtKDvXbO53Wy8SZCQHczT5FMiiqmQfUpWbYdTil/SxwZgAN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.min.js" integrity="sha384-skAcpIdS7UcVUC05LJ9Dxay8AXcDYfBJqt1CJ85S/CFujBsIzCIv+l9liuYLaMQ/" crossorigin="anonymous"></script>
-->
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW"
        crossorigin="anonymous"></script>
<script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (function () {
        'use strict'

        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        let forms = document.querySelectorAll('.needs-validation')

        // Loop over them and prevent submission
        Array.prototype.slice.call(forms)
            .forEach(function (form) {
                form.addEventListener('submit', function (event) {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
    })()
</script>
</body>
</html>
