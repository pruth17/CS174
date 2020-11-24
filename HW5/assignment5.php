<?php
define(TABLE, "AdvisingTable");
require_once 'login.php';
require_once 'databaseMethods.php';
    // Connect to MySQL
buildDB(new mysqli($hn, $un, $pw), $db);
    // connection to the database
$conn = new mysqli($hn, $un, $pw, $db);
buildTable($conn, TABLE);
    // Check connection
if ($conn->connect_error)
    die(mysql_fatal_error($conn->connect_error));
echo <<<_END
<form action="assignment5.php" method="post" enctype='multipart/form-data'><pre>
Advisor <input type="text" name="Advisor">
Student <input type="text" name="Student">
StudentID <input type="text" name="StudentID">
Class code <input type="text" name="ClassCode">
<input type="submit" value="ADD RECORD">
</pre></form>
_END;

echo <<<_END
<form action="assignment5.php" method="post" enctype='multipart/form-data'><pre>
Search <input type="text" name="SearchData">
<input type="submit" value="Search">
</pre></form>
_END;
if (isset($_POST['SearchData'])) // Search and print the record with given ID
    search($conn, TABLE);

if (isset($_POST['Advisor']) && isset($_POST['Student']) && isset($_POST['StudentID']) && isset($_POST['ClassCode']))
    insert($conn, TABLE);

$conn->close();




