<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// true or false: add or edit
$isEdit = !empty($_SESSION['fileLocation']) || !empty($_SESSION['name']) || !empty($_SESSION['location']);

$location = "add-edit.php";
require_once('./basic/basic-frame.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add-edit</title>
    <link rel="stylesheet" href="./css/add-edit.css">
</head>

<body>

    <!-- Photo upload section -->
    <form action="<?php echo $isEdit ? './betweenPage/edit.php' : './betweenPage/add.php'; ?>" method="post" enctype="multipart/form-data" class="upload-section">
        <div class="upload-photo-col">
            <?php $existingPhoto = isset($_SESSION['fileLocation']) && $_SESSION['fileLocation'] !== '' ? './photos/' . $_SESSION['fileLocation'] : ''; ?>
            <div class="photo-drop" role="button" tabindex="0" id="photoDrop">
                <img src="<?php echo htmlspecialchars($existingPhoto); ?>" alt="" class="preview-img" id="previewImg" style="<?php echo $existingPhoto ? 'display:block;' : ''; ?>">
                <span class="drop-label" id="dropLabel"><?php echo $existingPhoto ? 'change photo' : 'choose a photo'; ?></span>
                <input type="file" accept="image/*" name="uploadfile" id="uploadfile" <?php echo $existingPhoto ? '' : 'required'; ?> style="display:none;">
            </div>
            <div class="photo-progress"></div>
        </div>

        <div class="upload-form-col">
            <div class="form-field">
                <label for="photo-name">name of the photo:</label>
                <input type="text" name="name" id="photo-name" value="<?php echo htmlspecialchars($_SESSION['name'] ?? ''); ?>" required>
            </div>

            <div class="form-field">
                <label for="location-name">location name:</label>
                <input type="text" name="location" id="location-name" value="<?php echo htmlspecialchars($_SESSION['location'] ?? ''); ?>" required>
            </div>

            <div class="confirm-row">
                <?php if ($existingPhoto): ?>
                    <input type="submit" value="delete" name="delete" class="confirm-btn delete-btn" formaction="./betweenPage/delete.php" formnovalidate>
                <?php endif; ?>
                <input type="submit"
                    value="<?php echo $isEdit ? 'edit' : 'add'; ?>"
                    name="<?php echo $isEdit ? 'edit' : 'submit'; ?>"
                    class="confirm-btn">
            </div>
        </div>
    </form>

    <div class="overlay" id="overlay" onclick="closeAll()"></div>

</body>
<script src="./script/add-edit.js"></script>

</html>