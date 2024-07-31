
<?php
$usernameErr = $passwordErr = "";
$username = $password = "";
$request = "";
$caughtErr = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
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
            echo "Login";
            login();
        } else if (array_key_exists('register', $_POST)) {
            echo "Register";
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
    if(!userExists()){
        echo "Login failed, account doesn't exist";
    }else{
        echo "Login Succesful";
    }
}

function register(){
    if (userExists()){
        echo "User already exists";
    } else {
        echo "User does not exist yet, registering";
        addUserToDB();
    }
}

function userExists(){
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $sql = "SELECT * FROM users WHERE";
    $result = $conn->query($sql);

    if ($result == null){
        return false;
    }
    return true;
}

function addUserToDB(){
    $conn = new mysqli('localhost', 'root', '', 'wordledb');
    $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES(?, ?)");
    $stmt->bind_param("si", $username, $password);  // "si" denotes string and integer types
    $stmt->execute();
    $stmt->close();
}

?>

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