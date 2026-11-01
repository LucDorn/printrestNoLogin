<?php
session_start();

require_once('../basic/basicPage.php');

// gets the cords and than the location
function getLocationFromImage($filePath)
{
    $exif = @exif_read_data($filePath);

    if (!$exif || !isset(
        $exif['GPSLatitude'],
        $exif['GPSLongitude'],
        $exif['GPSLatitudeRef'],
        $exif['GPSLongitudeRef']
    )) {
        return null;
    }

    // Convert EXIF GPS fractions (e.g. "40/") to a decimal degree value.
    $getGpsDecimal = function (array $coordinate, string $hemisphere): float {
        for ($i = 0; $i < 3; $i++) {
            $part = explode('/', $coordinate[$i]);
            if (count($part) == 1) {
                $coordinate[$i] = (float) $part[0];
            } else if (count($part) == 2 && (float) $part[1] != 0) {
                $coordinate[$i] = $part[0] / $part[1];
            } else {
                $coordinate[$i] = 0;
            }
        }

        $degrees = $coordinate[0];
        $minutes = $coordinate[1];
        $seconds = $coordinate[2];

        $flip = ($hemisphere == 'W' || $hemisphere == 'S') ? -1 : 1;
        return ($degrees + ($minutes / 60) + ($seconds / 3600)) * $flip;
    };

    $latitude  = $getGpsDecimal($exif['GPSLatitude'],  $exif['GPSLatitudeRef']);
    $longitude = $getGpsDecimal($exif['GPSLongitude'], $exif['GPSLongitudeRef']);

    // Reverse geocode the coordinates to a city name.
    $url = "https://api.bigdatacloud.net/data/reverse-geocode-client?"
        . "latitude="  . urlencode($latitude)
        . "&longitude=" . urlencode($longitude)
        . "&localityLanguage=en";

    $context  = stream_context_create(['http' => ['timeout' => 5]]);
    $response = @file_get_contents($url, false, $context);
    if ($response === FALSE) {
        return null;
    }

    $data = json_decode($response, true);
    if (!empty($data['city'])) {
        return $data['city'];
    } elseif (!empty($data['locality'])) {
        return $data['locality'];
    }

    return null;
}


function handlePhotoUpload($name, $location, $fileName)
{
    if (trim($name) === '') {
        return ['error' => "Name is required."];
    }

    // Check file upload
    if (!isset($_FILES['uploadfile']) || $_FILES['uploadfile']['error'] !== UPLOAD_ERR_OK) {
        return ['error' => "Please upload a valid profile file."];
    }

    // File information
    $fileTmpPath      = $_FILES['uploadfile']['tmp_name'];
    $fileNameOriginal = $_FILES['uploadfile']['name'];
    $fileSize         = $_FILES['uploadfile']['size'];

    // Maximum 2MB
    if ($fileSize > 2 * 1024 * 1024) {
        return ['error' => "File is too large. Maximum size is 2MB."];
    }

    $allowedExtensions = ['jpg', 'jpeg', 'png', 'svg'];
    $fileExtension = strtolower(pathinfo($fileNameOriginal, PATHINFO_EXTENSION));
    if (!in_array($fileExtension, $allowedExtensions)) {
        return ['error' => "Invalid file type. Only JPG, JPEG, SVG and PNG are allowed."];
    }
    // gets the cords and than the location
    $detectedLocation = getLocationFromImage($fileTmpPath);

    if ($detectedLocation !== null) {
        $location = $detectedLocation;
    }

    $safeFileName = time() . '_' . $fileName . '.' . $fileExtension;

    // path to folder
    $uploadDir = __DIR__ . '/photos/';

    // makes folder if not found
    if (!is_dir($uploadDir)) {
        if (!mkdir($uploadDir, 0755, true)) {
            return ['error' => "Upload folder could not be created."];
        }
    }

    // Checks if folder is writable
    if (!is_writable($uploadDir)) {
        return ['error' => "Upload folder is not writable."];
    }

    $uploadFolder = $uploadDir . $safeFileName;

    // Move uploaded file
    if (!move_uploaded_file($fileTmpPath, $uploadFolder)) {
        return ['error' => "Failed to upload image."];
    }

    return [
        'fileName' => $safeFileName,
        'location' => $location,
        'fullPath' => $uploadFolder,
    ];
}


function start($name, $location, $connection, $userPassword = null)
{
    // if password has no input it will give random numbers
    if (is_string($userPassword) && trim($userPassword) !== '') {
        $password = trim($userPassword);

        if (strlen($password) > 64) {
            return "Password is too long (max 64 characters).";
        }
    } else {
        $password = bin2hex(random_bytes(8));
    }


    $fileName = bin2hex(random_bytes(8));

    // photo location
    $photo = handlePhotoUpload($name, $location, $fileName);
    if (isset($photo['error'])) {
        return $photo['error'];
    }

    $safeFileName = $photo['fileName'];
    $location     = $photo['location'];
    $uploadFolder = $photo['fullPath'];

    try {
        // if everything correct insert
        $stmt1 = $connection->prepare("INSERT INTO photo (name, location, fileLocation) VALUES (?, ?, ?)");
        $stmt2 = $connection->prepare("INSERT INTO make_edit (password) VALUES (?) ");

        $stmt1->bind_param("sss", $name, $location, $safeFileName);
        $stmt2->bind_param("s", $password);

        $stmt1->execute();
        $stmt2->execute();

        $stmt1->close();
        $stmt2->close();
    } catch (Throwable $e) {
        // if something goes wrong nothing would stay stuck 
        if (file_exists($uploadFolder)) {
            unlink($uploadFolder);
        }
        return "Database error occurred." .  $e->getMessage();
    }
}

// Check if the form was submitted
try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!isset($conn) || !($conn instanceof mysqli)) {
            $_SESSION['message'] = "Database connection failed.";
        } else {
            $_SESSION['message'] = start(
                $_POST['name']     ?? '',
                $_POST['location'] ?? '',
                $conn,
                $_POST['password'] ?? null
            );
            $conn->close();
        }
    }
} catch (Throwable $e) {
    error_log('Failed to add photo for user with ' . $_POST['password'] . ': ' . $e->getMessage());
} finally {

    header('Location: ../home.php');
    exit();
}
