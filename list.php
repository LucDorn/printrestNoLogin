<?php
require_once('./basic/basicPage.php');
require_once('./basic/basic-frame.php');
// select all things that have visebole 1 

$stmt2 = $conn->prepare("SELECT user_id, name, location, fileLocation FROM photo WHERE visible = 1");
$stmt2->execute();

$result = $stmt2->get_result();

while ($row = $result->fetch_assoc()) {
    $id     = (int) $row['user_id'];
    $name   = htmlspecialchars($row['name'], ENT_QUOTES, 'UTF-8');
    $loc    = htmlspecialchars($row['location'], ENT_QUOTES, 'UTF-8');
    $file   = htmlspecialchars($row['fileLocation'], ENT_QUOTES, 'UTF-8');
    $url    = './view.php?id=' . urlencode($id);

    echo "<a href='{$url}' style='text-decoration:none; color:inherit; display:block;'>";
    echo   "<div style='border:3px solid black; padding:10px; margin:10px 0;'>";
    echo     "<h3>{$name}</h3>";
    echo     "<p>{$loc}</p>";
    echo     "<img src='./betweenPage/photos/{$file}' alt='{$name}' style='max-width:300px;'>";
    echo   "</div>";
    echo "</a>";
}
