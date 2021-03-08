<?php
    
    require_once 'login.php';
    require_once 'databaseMethods.php';
    $conn = new mysqli($hn, $un, $pw, $db);
    
    // printing out the webpage
    echo <<<_END
    <form action="user.php" method="post" enctype='multipart/form-data'><pre>
    Select Putative Infected File <input type='file' name='filename' size='10'>
    <input type="submit" value="CHECK">
    </pre></form>
    _END;
    // Uploading the malware file
    if ($_FILES['filename']) // Check if file is uploaded
    checkMalware($conn, "Malwares"); // Check for malware in table "Malwares"
    
    $conn->close();
    
    
    /**
     * Allows the user to submit a putative infected file
     */
    function checkMalware($conn, $name) {
        // Read the file, extract its contents, and sanitize text
        $content = sanitizeMySQL($conn, getPutativeFile($_FILES['filename']['tmp_name']));
        $isMalware = false; // Flag for the loop
        for ($i = 0; $i < strlen($content) && $isMalware == false; $i ++)
            $isMalware = searchMalware($conn, $name, substr($content, $i, 20));
        if ($isMalware)
            echo "File infected with malware!<br>";
        else
            echo "No malware detected!<br>";
    }
    
    function searchMalware($conn, $table, $string) {
        $query = "SELECT * FROM {$table} WHERE Signature='$string'";
        $result = $conn->query($query);
        if (! $result)
            die($conn->error);
        elseif ($result->num_rows) {
            $row = $result->fetch_array(MYSQLI_NUM);
            $result->close();
            echo "Malware detected: " . $row[1] . "<br>";  malware name
            return true;
        }
        return false;
    }
    
    
    function getPutativeFile($fname) {
        $fh = fopen($fname, 'r') or die(mysql_fatal_error("Cannot open file!"));
        while (! feof($fh))
            $text .= fgets($fh);
        fclose($fh); // Close the file
        return $text; // Return extracted text
    }
