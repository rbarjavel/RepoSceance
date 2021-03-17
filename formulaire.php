<link rel="stylesheet" href="temp.css"/>


<?php

if (isset($_GET["userid"])) {
    $file = file_get_contents("./db/question.json", "r");
    $question= $_GET["question"];
    $name = "";

    echo "<form method=\"post\">";

    create_question($file, $question);
    $json = json_decode($file, true);
    // ===== GET RESPONSE =====
    foreach ($json as $key => $value) {
        foreach ($value as $k) {
            foreach ($k as $keys => $value) {
                if ($keys == "name") {
                    $name = $value;
                }
                if (isset($_POST[$name])){
                    if ($keys == "reponse") {
                    if(count($value) > 1){
                        $comptage = 0;
                        foreach ($value as $values) {
                        foreach ($_POST[$name] as $useranswer){
                            if ($useranswer == $values) {
                                $comptage += 1;
                            }
                        }
                        }
                        if ($comptage == count($value)){
                        echo "Bien joué ! 😄";
                        addPoints();
                        }else{
                        echo "Dommage 😥";
                        }
                    }else{
                        $reponse = reset($value);
                        if ($_POST[$name] == $reponse) {
                        echo "Bien joué !😄";
                        addPoints();
                        }else{
                        echo "Dommage 😥";
                        }
                    }
                }
                }
            }
            }
        }
} else {
    echo <<< EOT
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
        <input type="password" class="text" name="mdp" placeholder="password" tabindex="2" required />
        <span class="required">Password</span>
        </label>
    </div>
    <input type="submit" value="login" name="login"/>
    </form>
EOT;


}

function create_question($file,$question){
  $json = json_decode($file, true);
  foreach ($json as $key => $value) {
      foreach ($value as $k) {
        $display = false;
        foreach ($k as $keys => $value) {
            if ($keys == "name") {
              if ($value == $question){
                $name = $value;
                $display = true;
              }
            }
            if ($keys == "question" && $display == true) {
                echo "<br>";
                echo "<p>".$value."</p>";
                echo "<br>";
            }
            if ($keys == "reponse" && $display == true){
                $reponse = $value;
            }
            if ($keys == "values" && $display == true) {
              if(count($reponse) == 1){
                foreach ($value as $values) {
                    echo "<label>".$values."</label>";
                    echo "<input type=\"radio\" name=\"$name\" value=\"$values\">";
                }echo "<input type=\"submit\" value=\"validate\">";
                echo "</form>";
              }
              else{
                foreach ($value as $values) {
                    echo "<label>".$values."</label>";
                    // echo $values;
                    $nn = $name. "[]";
                    echo "<input type=\"checkbox\" name=\"$nn\" id=\"$name\" value=\"$values\">";
                }echo "<input type=\"submit\" value=\"validate\">";
                echo "</form>";
              }
            }
        }
      }
    }
}

function getUserMeta(){
    $userId = $_GET["userid"];
    $db = json_decode(file_get_contents("./db/user.json"), true);
    $meta = $db["userMeta"][$userId];
    return $meta;
}

function addPoints() {
    $userId = $_GET["userid"];
    $meta = getUserMeta();

    if (!isset($meta["points"]) || !isset($meta["qt"])) {
        $meta["points"] = 1;
        $meta["qt"] = $_GET["question"];
    } else {
        if (strpos($meta["qt"], $_GET["question"], 0) === false) {
            $data = $meta["points"];
            $data2 = $meta["qt"];
            $meta["points"] = $data + 1;
            $meta["qt"] = $data2.", ".$_GET["question"];
        }
    }
    $db = json_decode(file_get_contents("./db/user.json"), true);

    $db["userMeta"][$userId] = $meta;

    $db = json_encode($db);
    file_put_contents("./db/user.json", $db);
    header("location: http://localhost/~barja/index.php");
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
        
        if ($index >= 0)
            header("location: ".$url."?question=".$_GET["question"]."&userid=".$index); 
    }
    
}

?>
