<html>
    <title>Dashboard</title>
    <link rel="stylesheet" href="tab.css"/>
    <link rel="stylesheet" href="util.css"/>
    <body>
    <div class="limiter">
		<div class="container-table100">
			<div class="wrap-table100">
				<div class="table100 ver1 m-b-110">
					<div class="table100-head">
						<table>
							<thead>
								<tr class="row100 head">
									<th class="cell100 column1">User</th>
									<th class="cell100 column2">Points</th>
								</tr>
							</thead>
						</table>
					</div>

					<div class="table100-body js-pscroll">
						<table>
							<tbody>
<?php
    $usersDB = json_decode(file_get_contents("./db/user.json"), true);
    $it = 0;

    
    $final = array();
    foreach ($usersDB["users"] as &$user) {
        if (isset($usersDB["userMeta"][$it]["points"])) {
            $final[$it]["points"] = $usersDB["userMeta"][$it]["points"];
            $final[$it]["login"] = $user["login"];
        }
        else {
            $final[$it]["points"] = 0;
            $final[$it]["login"] = $user["login"];
        }
        $it++;
    }

    $sortArray = array();
    foreach($final as $person){
        foreach($person as $key=>$value){
            if(!isset($sortArray[$key])){
                $sortArray[$key] = array();
            }
            $sortArray[$key][] = $value;
        }
    }
    $orderby = "points";
    array_multisort($sortArray[$orderby],SORT_DESC,$final);
    
    foreach ($final as &$user) {
        echo "<tr class=\"row100 body\">";
        echo "<td class=\"cell100 column1\">".$user["login"]."</td>";
        echo "<td class=\"cell100 column2\">".$user["points"]."</td>";
        echo "</tr>";
    }
?>
							</tbody>
						</table>
					</div>
				</div>
    </body>
</html>