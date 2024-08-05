
<?php
$usernameErr = $passwordErr = $loginErr = "";
$username = $password = "";
$request = "";
$showLogin = true;
$showUser = $showAdmin = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $username, $password;

    $caughtErr = false;

    if (empty($_POST["username"])) {
        $usernameErr = "* required";
        $caughtErr = true;
    } else {
        $username = strtolower(test_input($_POST["username"]));
        if (!preg_match("/^[a-zA-Z-' ]*$/", $username)) {
            $usernameErr = "* only letters and white space allowed";
            $caughtErr = true;
        }
    }

    if (empty($_POST["password"])) {
        $passwordErr = "* required";
        $caughtErr = true;
    } else {
        $password = test_input($_POST["password"]);
    }

    if (!$caughtErr){
        if (array_key_exists('login', $_POST)) {
            login();
        } else if (array_key_exists('register', $_POST)) {
            register();
        }
    }

    if (array_key_exists('loggout', $_POST)) {
        loggout();
    }

    if (array_key_exists('reset', $_POST)) {
        reset_scores();
    }
}

function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login(){
    global $username, $loginErr;
    if(!userPwExists()){
        $loginErr = "Account doesn't exist or password is incorrect";
    }else{
        //echo "Login Succesful";
        setStatus($username);
    }
}

function register(){
    global $username, $loginErr;
    if (userExists()){
        $loginErr = "Username already exists";
    } else {
        //echo "User is being registered";
        addUserToDB();
        setStatus($username);
    }
}

function loggout(){
    setStatus("");
}

function reset_scores(){
    include ("destroy_score.php");
}

function userExists(){
    global $username, $password;
    //echo "now checking if user " . $username . " with password " . $password . " exists. <br>";
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $sql = "SELECT * FROM users WHERE username='" . strtolower($username) . "'";
    //echo "SQL: " . $sql . "<br>";
    $result = $conn->query($sql);
    $conn->close();
    //echo "result user: " . $result->fetch_assoc()['username'] . "<br>";

    try{
        error_reporting(E_ERROR | E_PARSE);

        if (is_null($result->fetch_assoc()['username'])) {
            return false;
        }
        return true;

    }catch(\Throwable $e){
        console_log("Error: " . $e);
        return false;
    }
}

function userPwExists()
{
    global $username, $password;
    //echo "now checking if user " . $username . " with password " . $password . " exists. <br>";
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $sql = "SELECT * FROM users WHERE username='" . strtolower($username) . "' AND password='" . $password . "'";
    //echo "SQL: " . $sql . "<br>";
    $result = $conn->query($sql);
    $conn->close();
    //echo "result user: " . $result->fetch_assoc()['username'] . "<br>";

    try {
        error_reporting(E_ERROR | E_PARSE);

        if (is_null($result->fetch_assoc()['username'])) {
            return false;
        }
        return true;

    } catch (\Throwable $e) {
        console_log("Error: " . $e);
        return false;
    }
}

function addUserToDB(){
    global $username, $password;

    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $stmt->close();
}

function setStatus($username){
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $stmt = $conn->prepare("UPDATE status SET username = '" . $username . "';");
    $stmt->execute();
    $conn->close();
}

function getStatus(){
    global $username;

    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $sql = "SELECT * FROM status";
    $result = $conn->query($sql);
    $conn->close();

    $result = $result->fetch_assoc();
    $username = $result['username'];
}

function updateView(){
    global $username, $password, $showLogin, $showAdmin, $showUser;
    getStatus();
    //echo "name: " . $username;
    //echo "pw: " . $password;
    if (!empty($username)){
        if ($username == "admin") {
            $showAdmin = true;
            $showLogin = $showUser = false;
        }else{
            $showUser = true;
            $showLogin = $showAdmin = false;
        }
    } else {
        $showLogin = true;
        $showUser = $showAdmin = false;
    }
}

function console_log($data)
{
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

updateView();
?>

<div id="login" <?php if ($showLogin === false) { ?>style="display:none"<?php } ?>>
    <h3 class="title-2">User Login</h3>

    <form class="login" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label>Username: </label> <br>
        <input type="text" name="username" placeholder="Enter Username..." /> <br>
        <span class="error"><?php echo $usernameErr ?></span><br><br>

        <label>Password: </label> <br>
        <input type="password" name="password" placeholder="Enter Password..." /> <br>
        <span class="error"><?php echo $passwordErr ?></span> <br>

        <span class="error"><?php echo $loginErr ?></span> <br>

        <button type="submit" name="login">Login</button>
        <button type="submit" name="register">Register</button>

    </form>
</div>

<div id="logged-in-view" class="user-view" <?php if ($showLogin === true) { ?>style="display:none"<?php } ?>>
    <h3 class="title-2">Account</h3>
    <span class="status">You are logged in as <?php echo $username ?>.</span>

    <div id="user-view" <?php if ($showUser === false) { ?>style="display:none"<?php } ?>>
        -<br>
        <p>Your scores will be saved to the leaderboard.</p> <br>

    </div>

    <div id="admin-view" <?php if ($showAdmin === false) { ?>style="display:none"<?php } ?>>
        <form method="post">
            <button type="submit" name="reset" class="warning">RESET LEADERBOARD</button> <br>   
        </form>
    </div>

    <form method="post">
        <button type="submit" name="loggout">Logout</button>    
    </form>
</div>
