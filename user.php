<!-- <html>
<head>
  <title>Login | Register</title>
</head>

<body>
  <form method="post">
    <label for="username">Username :<br />
    <input type="text" name="username" /><br />
    <label for="password">Password :<br />
    <input type="password" name="password" /><br />
    <input type="submit" name="register" /><br />
  </form>

  <form method="post">
    <label for="username"><label for="username">Login :<br />
    <input type="text" name="log" /><br />
    <label for="password">Password :<br />
    <input type="password" name="mdp" /><br />
    <input type="submit" name="login" /><br />
  </form>
</body>
</html> -->

<link rel="stylesheet" href="temp.css"/>
<form method="post" class="login-form">
  <h1 class="a11y-hidden">Login Form</h1>
  <div>
    <label class="label-email">
      <input type="text" class="text" name="log" placeholder="login" tabindex="1" required />
      <span class="required">Login</span>
    </label>
  </div>
  <div>
    <label class="label-password">
      <input type="text" class="text" name="mdp" placeholder="password" tabindex="2" required />
      <span class="required">Password</span>
    </label>
  </div>
  <input type="submit" value="login" name="login"/>
</form>

 <?php

function addUserToDataBase($user, $password){
    $usersDB = json_decode(file_get_contents("./db/user.json"), true);

    $userToInsert = array();
    $metaToInsert = array();

    $userToInsert["login"] = $user;
    $userToInsert["password"] = $password;
    $metaToInsert["placeholder"] = "placeholder";

    array_push($usersDB["users"], $userToInsert);
    array_push($usersDB["userMeta"], $metaToInsert);

    $usersDB = json_encode($usersDB);
    file_put_contents("./db/user.json", $usersDB);
}

function findUserInDatabase($user, $password){
    $usersDB = json_decode(file_get_contents("./db/user.json"), true);
    $index = 0;
    foreach($usersDB["users"] as &$item) {
        if ($item["login"] == $user && $item["password"] == $password) {
            echo'<p>user found<p/>';
            return $index;
        }
        $index++;
    }
    return-84;
}

if (isset($_POST['login'])) {
    $username = $_POST['log'];
    $password = $_POST['mdp'];
    $url = $_GET['url'];
    if ($password != "" && $username != "") {
        $index = findUserInDatabase($username, $password);
        
        header("location: ".$url."&userid=".$index);
        
    }
    
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    echo'<p>register<p/>';
    if ($password != "" && $username != "")
        addUserToDataBase($username, $password);
}

?> 
