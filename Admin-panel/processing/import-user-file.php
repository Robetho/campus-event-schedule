
<?php
include '../includes/session.php';
require '../vendor/autoload.php';
// require '../PHPMailer/src/Exception.php';
// require '../PHPMailer/src/PHPMailer.php';
// require '../PHPMailer/src/SMTP.php';

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\Exception;



//$mail = new PHPMailer(true);

if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

if (isset($_POST['import-file'])) {
    $allowed_ext = ['xls', 'csv', 'xlsx'];
    $fileName = $_FILES['imported-file']['name'];
    $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

  
    if (!in_array($file_ext, $allowed_ext)) {
        $_SESSION['error'] = 'Invalid file format. Please upload xls, csv, or xlsx.';
        
    }

    $inputFileNamePath = $_FILES['imported-file']['tmp_name'];
    try {
        $spreadsheet = IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();

        $conn->begin_transaction();

        foreach ($data as $key => $row) {
            if ($key == 0) continue; // Skip the header row

            $firstname = $conn->real_escape_string($row[0]);
            $middlename = $conn->real_escape_string($row[1]);
            $lastname = $conn->real_escape_string($row[2]);
            $uname = $conn->real_escape_string($row[3]);
            $email = $conn->real_escape_string($row[4]);
            $gender = $conn->real_escape_string($row[5]);
            $role_as = $conn->real_escape_string($row[6]);
            

            $password = password_hash($lastname, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO tbl_users 
                (firstname, middlename, lastname, username, password, email,  gender, role_as) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ssssssss', $firstname, $middlename, $lastname, $uname, $password, $email, $gender, $role_as);

            if (!$stmt->execute()) {
                $_SESSION['error'] = "Database error on row $key.";
                $conn->rollback();
                exit;
            }
        }

        $conn->commit();
        $_SESSION['success'] = "File successfully imported";
        header('location: ../users-lists');
    } catch (Exception $e) {
        $_SESSION['error'] = "Error processing file:" . $e->getMessage();
        $conn->rollback();
    }
}

header('location: ../import-user-file');
exit;
?>
