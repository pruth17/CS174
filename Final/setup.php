<?php
    
    setup();
    
    /**
     * admin username "admin" and password "CS174Final"
     */
    function setup() {
        require_once 'login.php';
        buildDB(new mysqli($hn, $un, $pw), $db);
        $conn = new mysqli($hn, $un, $pw, $db);
        buildUserTable($conn, "Users");
        insertAdmin($conn, "Users");
        buildMalwareTable($conn, "Malwares");
    }
    
    function buildUserTable($conn, $name) {
        if ($conn->connect_error)
            die(mysql_fatal_error("Could not access DB when building table: " . $conn->error));
        $query = "CREATE TABLE IF NOT EXISTS {$name} (
        Username VARCHAR(10) NOT NULL,
        Password CHAR(64) NOT NULL,
        Salt Char(4) NOT NULL
        )";
        if (! $conn->query($query))
            die(mysql_fatal_error("Could not build table: " . $conn->error));
    }
    
    function buildMalwareTable($conn, $name) {
        if ($conn->connect_error)
            die(mysql_fatal_error("Could not access DB when building table: " . $conn->error));
        $query = "CREATE TABLE IF NOT EXISTS {$name} (
        Name VARCHAR(10) NOT NULL,
        Signature CHAR(20) NOT NULL
        )";
        if (! $conn->query($query))
            die(mysql_fatal_error("Could not build table: " . $conn->error));
    }
    
    /**
     * Inserts an admin in the table of users
     */
    function insertAdmin($conn, $name) {
        $username = "admin";
        $password = "CS174Final";
        $salt = generateSalt(4); // Salt size is 4
        $token = hash('sha256', "$salt$password"); // 64 characters
        $query = "INSERT INTO {$name} VALUES('$username', '$token', '$salt')";
        $result = $conn->query($query);
        if (! $result)
            die(mysql_fatal_error("Failed to insert: $query<br>" . $conn->error . "<br><br>"));
    }
    
    /**
     * Builds a database if AdvisingDatabase doesn't already exist
     */
    function buildDB($conn, $name) {
        if ($conn->connect_error)
            die(mysql_fatal_error("Could not access MySQLwhen building table: " . $conn->connect_error)); // Checking connection
        $sql = "CREATE DATABASE IF NOT EXISTS {$name}";
        if ($conn->query($sql) === FALSE)
            echo mysql_fatal_error("Error creating database: " . $conn->error);
        $conn->close(); // Close the connection
    }
    
    /**
     * Function to notify the user when error occurs
     */
    function mysql_fatal_error($error) {
        echo <<<_END
        We are sorry, but it was not possible to complete the requested task.
        Please click the back button on your browser and try again.
        Thank you.
        _END;
    }
    
    /**
     * Generates a salt with given length
     */
    function generateSalt($length)
    {
        $charSet = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz!@#$%&*?0123456789"; // Char set
        $salt = ""; // Salt
        for ($i = 0; $i < $length; $i ++)
            $salt .= $charSet{mt_rand(0, (strlen($charSet) - 1))};
        return $salt;
    }
