
<?php
$usernameErr = $passwordErr = "";
$username = $password = "";
$request = "";
$caughtErr = false;
$loggedIn = false;
$showLogin = true;
$showUser = $showAdmin = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    global $username, $password;

    if (empty($_POST["username"])) {
        $usernameErr = "* required";
        $caughtErr = true;
    } else {
        $username = test_input($_POST["username"]);
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
}

function test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function login(){
    global $loggedIn;
    if(!userExists()){
        echo "Login failed, account doesn't exist or password is wrong";
    }else{
        echo "Login Succesful";
        $loggedIn = true;
        updateView();
    }
}

function register(){
    global $loggedIn;
    if (userExists()){
        echo "User already exists";
    } else {
        echo "User is being registered";
        addUserToDB();
        $loggedIn = true;
        updateView();
    }
}

function loggout(){
    global $username, $password, $loggedIn;
    if (isset($_POST['loggout'])){
        $username = $password = "";
        $loggedIn = false;
        updateView();
    }
}

function userExists(){
    global $username, $password;
    //echo "now checking if user " . $username . " with password " . $password . " exists. <br>";
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $sql = "SELECT * FROM users WHERE username='" . $username . "' AND password='" . $password . "'";
    //echo "SQL: " . $sql . "<br>";
    $result = $conn->query($sql);
    $conn->close();
    //echo "result user: " . $result->fetch_assoc()['username'] . "<br>";

    if (is_null($result->fetch_assoc()['username'])){
        return false;
    }
    return true;
}

function addUserToDB(){
    global $username, $password;

    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
    $stmt->bind_param("si", $username, $password);  // "si" denotes string and integer types
    $stmt->execute();
    $stmt->close();
}

function updateView(){
    global $username, $password, $loggedIn, $showLogin, $showAdmin, $showUser;
    if (!empty($username) && $loggedIn){
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
        <span class="error"><?php echo $passwordErr ?></span> <br><br>

        <button type="submit" name="login">Login</button>
        <button type="submit" name="register">Register</button>

    </form>
</div>

<div id="logged-in-view" class="user-view" <?php if ($loggedIn === false) { ?>style="display:none"<?php } ?>>
    <h3 class="title-2">Account</h3>
    <span>You are logged in as <?php echo $username ?>.</span>

    <div id="user-view" <?php if ($showUser === false) { ?>style="display:none"<?php } ?>>
        user
    </div>

    <div id="admin-view" <?php if ($showAdmin === false) { ?>style="display:none"<?php } ?>>
        admin
    </div>

    <form method="post">
        <button type="submit" name="loggout">Loggout</button>
    </form>
</div>
