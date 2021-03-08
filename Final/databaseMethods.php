<?php
    
    
/**
* Sanitizes input
*/
function sanitizeMySQL($connection, $var) {
    $var = $connection->real_escape_string($var);
    $var = sanitizeString($var);
    return $var;
}

/**
 * Notifies user when an error occurs
 */
function mysql_fatal_error($error) {
    echo <<<_END
    We are sorry, but it was not possible to complete the requested task.
    Please click the back button on your browser and try again.
    Thank you.
    _END;
}
    
/**
* Helper method for sanitizeMySQL
*/
function sanitizeString($var) {
    $var = stripslashes($var);
    $var = strip_tags($var);
    $var = htmlentities($var);
    return $var;
}

/**
 * Gets the next object
 */
function get_post($conn, $var) {
    return $conn->real_escape_string($_POST[$var]);
}
