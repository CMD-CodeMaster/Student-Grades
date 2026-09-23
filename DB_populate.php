<?php
include_once 'DB_Conn.php';
executeSQLFromFile('SQL/del.sql');
executeSQLFromFile('SQL/table.sql');

function updateTextFiles($conn) {
    // Query the database for the latest data
    $courseResult = $conn->query("SELECT * FROM courses");
    $nameResult = $conn->query("SELECT * FROM names");

    // Open the text files in write mode
    $courseFile = fopen("Data\CourseFile.txt", "w");
    $nameFile = fopen("Data\NameFile.txt", "w");

    // Write the data to the text files
    while ($row = $courseResult->fetch(PDO::FETCH_NUM)) {
        fwrite($courseFile, $row[0] . ', ' . $row[1] . ', ' . $row[2] . ', ' . $row[3] . ', ' . $row[4] . ', ' . $row[5] . "\n");
    }
    while ($row = $nameResult->fetch(PDO::FETCH_NUM)) {
        fwrite($nameFile, $row[0] . ', ' . $row[1] . "\n");
    }

    // Close the text files
    fclose($courseFile);
    fclose($nameFile);
}

// Function to read a file line by line and execute a callback to populate tables
function insertFromFile($filename, $callback) {
    try {
        $file = new SplFileObject($filename);
        while (!$file->eof()) {
            $line = $file->fgets();
            if (!empty(trim($line))) {
                call_user_func($callback, trim($line));
            }
        }
    } catch (Exception $e) {
        echo "Error processing file ($filename): " . $e->getMessage();
    } 
}

// Function to insert a name into the names table
function insertName($line) {
    global $conn;
    list($studentID, $studentName) = explode(', ', $line);
    $sql = "INSERT IGNORE INTO names (Student_ID, Student_NAME) VALUES (:studentID, :studentName)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':studentID', $studentID);
    $stmt->bindParam(':studentName', $studentName);
    $stmt->execute();
}

// Function to insert a course into the courses table
function insertCourse($line) {
    global $conn;
    list($studentID, $courseID, $test1, $test2, $test3, $finalExam) = explode(', ', $line);
    $sql = "INSERT INTO courses (Student_ID, Course_ID, Test_1, Test_2, Test_3, Final_Exam) VALUES (:studentID, :courseID, :test1, :test2, :test3, :finalExam)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':studentID', $studentID);
    $stmt->bindParam(':courseID', $courseID);
    $stmt->bindParam(':test1', $test1);
    $stmt->bindParam(':test2', $test2);
    $stmt->bindParam(':test3', $test3);
    $stmt->bindParam(':finalExam', $finalExam);
    $stmt->execute();
}

// Insert data from NameFile.txt into the names table
insertFromFile('Data/NameFile.txt', 'insertName');

// Insert data from CourseFile.txt into the courses table
insertFromFile('Data/CourseFile.txt', 'insertCourse');

$sql = "SELECT names.Student_ID, names.Student_NAME, courses.Course_ID, courses.Test_1, courses.Test_2, courses.Test_3, courses.Final_Exam 
        FROM names 
        JOIN courses ON names.Student_ID = courses.Student_ID";

try {
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    // Set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $joinedData = $stmt->fetchAll();

} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>