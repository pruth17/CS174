<?php
    function search($conn, $table) {
        $advisor = sanitizeMySQL($conn, get_post($conn, 'SearchData'));
        $query = "SELECT * FROM {$table} WHERE Advisor='$advisor'";
        $result = $conn->query($query);
        if (! $result)
            die(mysql_fatal_error("Database access failed: " . $conn->error));
        else {
            $row = $result->fetch_array(MYSQLI_NUM);
            if ($row[0] == '' || $row[1] == '' || $row[2] == '' || $row[3] == '')
                echo "Record does not exist!";
            else
                printRecord($row);
        }
        $result->close();
    }
    
    function insert($conn, $table) {
        $advisor = sanitizeMySQL($conn, get_post($conn, 'Advisor'));
        $student = sanitizeMySQL($conn, get_post($conn, 'Student'));
        $studentID = sanitizeMySQL($conn, get_post($conn, 'StudentID'));
        $classCode = sanitizeMySQL($conn, get_post($conn, 'ClassCode'));
        if ($advisor == '' || $student == '' || $studentID == '' || $classCode == '')
            echo "Failed to insert!<br>Blank Fields!";
        else {
            $stmt = $conn->prepare("INSERT INTO {$table} VALUES(?,?,?,?)");
            $stmt->bind_param('ssss', $advisor, $student, $studentID, $classCode);
            $stmt->execute();
            if ($stmt->affected_rows == 1)
                echo "Record successfully added!";
            else
                echo "Failed to insert!";
            $stmt->close();
        }
    }
    
    
    function printRecord($row) {
        echo <<<_END
        <pre>
        Advisor $row[0]
        Student $row[1]
        StudentID $row[2]
        ClassCode $row[3]
        </pre>
        _END;
    }
    
    
    function mysql_fatal_error($error) {
        echo <<<_END
        We are sorry, but it was not possible to complete the requested task.
        Please click the back button on your browser and try again.
        Thank you.
        _END;
        
    }
    
    
    function get_post($conn, $var) {
        return $conn->real_escape_string($_POST[$var]);
    }
    
    
    function sanitizeMySQL($connection, $var) {
        $var = $connection->real_escape_string($var);
        $var = sanitizeString($var);
        return $var;
    }
    
    function sanitizeString($var) {
        $var = stripslashes($var);
        $var = strip_tags($var);
        $var = htmlentities($var);
        return $var;
    }
    
    
    function buildDB($conn, $name) {
        if ($conn->connect_error)
            die(mysql_fatal_error("Could not access MySQLwhen building table: " . $conn->connect_error));
        $sql = "CREATE DATABASE IF NOT EXISTS {$name}";
        if ($conn->query($sql) === FALSE)
            echo mysql_fatal_error("Error creating database: " . $conn->error);
        $conn->close();
    }
    
    function buildTable($conn, $name) {
        if ($conn->connect_error)
            die(mysql_fatal_error("Could not access DB when building table: " . $conn->error));
        $query = "CREATE TABLE IF NOT EXISTS {$name} (
        Advisor VARCHAR(30) NOT NULL,
        Student VARCHAR(30) NOT NULL,
        StudentID VARCHAR(9) NOT NULL,
        ClassCode VARCHAR(5) NOT NULL
        )";
        if (! $conn->query($query))
            die(mysql_fatal_error("Could not build table: " . $conn->error));
    }
