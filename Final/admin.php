<?php
    // Note: username "admin" and password "CS174Final"

    // Import database credentials
require_once 'login.php';
    // Load methods in other file
require_once 'databaseMethods.php';
    // Creating a db connection
$conn = new mysqli($hn, $un, $pw, $db);

    // Authenticating the admin
if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
    // Sanitizing variables
    $username = sanitizeMySQL($conn, $_SERVER['PHP_AUTH_USER']);
    $password = sanitizeMySQL($conn, $_SERVER['PHP_AUTH_PW']);
    checkPassword($conn, $username, $password);
}
else {
    header('WWW-Authenticate: Basic realm="Restricted Section"');
    header('HTTP/1.0 401 Unauthorized');
    die("Please enter your username and password");
}
    // Printing out the webpage
echo <<<_END
<form action="admin.php" method="post" enctype='multipart/form-data'><pre>
Name <input type="text" name="Name">
Select File <input type='file' name='filename' size='10'>
<input type="submit" value="ADD MALWARE">
</pre></form>
_END;

    // Uploading the file
if (isset($_POST['Name']) && $_FILES['filename'])
    uploadMalware($conn, "Malwares");
$conn->close();

    // Authenticates the user by checking the password
function checkPassword($conn, $username, $password) {
    $query = "SELECT * FROM users WHERE username='$username'";
    $res = $conn->query($query);
    if (! $res)
        die($conn->error);
    elseif ($res->num_rows) { // If user exists
        $row = $res->fetch_array(MYSQLI_NUM);
        $resx->close();
        $salt = $row[2];
        $token = hash('sha256', "$salt$password");
        if ($token == $row[1]) // Check password
            echo "Welcome User: " . $username;
        else
            die("Invalid username/password combination"); // Incorrect password
    } else
        die("Invalid username/password combination"); // Incorrect username
}

/**
 * Allows admin to upload malware to malware DB
 */
function uploadMalware($conn, $table) {
    // Name of the file and sanitizing it
    $name = sanitizeMySQL($conn, get_post($conn, 'Name'));
    $sig= sanitizeMySQL($conn, getMalware($_FILES['filename']['tmp_name']));
    $stmt = $conn->prepare("INSERT INTO {$table} VALUES(?,?)");
    $stmt->bind_param('ss', $name, $sig);
    $stmt->execute();
    if ($stmt->affected_rows == 1)
        echo "Malware uploaded!"; // Print confirmation message
    else
        die("Failed to upload!"); // Close the program if user misbehaves
    $stmt->close();
}

/**
 * Gets first 20 bytes/chars of malware file
 */
function getMalware($fname) {
    // Try to access the file. If not, quit.
    $fh = fopen($fname, 'r') or die(mysql_fatal_error("Cannot open file!"));
    $text = fread($fh, 20); // Read 20 chars
    fclose($fh); // Close the file
    return $text; // Return extracted text
}
