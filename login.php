<?php
//include '/method_php/api.php'


/*
$data = array(
    'username' => $_POST['Uname'],
    'password' => $_POST['Password'],
);
*/
function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();
    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
            }
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);

    curl_close($curl);

    return $result;
}

$register = false;
// Generation de l'html
if (isset($_GET['v'])) {
    $register = true;
    echo <<< EOT
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/login.css">
        <title>Login | RS</title>
    </head>

    <body>
        <div class="form-style">
            <div class="inside-form">
                <p class="under-text">Bienvenue sur le RS !</p>
                <h1 class="title">REGISTER</h1>
                <form method="post">
                    <div>
                        <input type="text" name="Uname" placeholder="username" required><br>
                    </div>
                    <div>
                        <input type="password" name="Password" placeholder="password" required><br>
                    </div>
                    <div>
                        <input type="submit" name="register" value="CREATE">
                    </div>
                </form>
                <a href="/jaquon/login.php">Déja un compte? Connecte toi!</a>
            </div>
        </div>
    </body>

    </html>
    EOT;
} else {
    echo <<< EOT
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="./css/login.css">
        <title>Login | RS</title>
    </head>

    <body>
        <div class="form-style">
            <div class="inside-form">
                <p class="under-text">Bienvenue sur le RS !</p>
                <h1 class="title">CONNEXION</h1>
                <form method="post">
                    <div>
                        <input type="text" name="Uname" placeholder="username" required><br>
                    </div>
                    <div>
                        <input type="password" name="Password" placeholder="password" required><br>
                    </div>
                    <div>
                        <input type="submit" name="register" value="LOGIN">
                    </div>
                </form>
                <a href="/jaquon/login.php?v=create">Pas de compte? Inscrit toi!</a>
            </div>
        </div>
    </body>

    </html>
    EOT;
}


if (isset($_POST['Uname'])) {
    //var_dump($_POST);
    $data = array(
        'username' => $_POST['Uname'],
        'password' => $_POST['Password'],
    );

    if ($register) $result = CallAPI("POST", "http://barja.xyz/AddUser", $data);
    else $result = CallAPI("POST", "http://barja.xyz/GetUser", $data);
    
    echo "<script type='text/javascript'>alert('$result');</script>";
}



?>