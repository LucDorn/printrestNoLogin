<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Photo details</title>
    <style>
        .page {
            display: flex;
            gap: 44px;
            align-items: flex-start;
            width: 100%;
            max-width: 800px;
            padding: 40px 24px;
        }

        .left {
            flex: 0 0 338px;
        }

        .photo {
            width: 338px;
            height: 288px;
            background: #d9d9d9;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            max-width: 500px;
            max-height: 500px;
        }

        .scrollbar {
            margin-top: 15px;
            height: 14px;
            background: #9d9d9d;
            border-radius: 7px;
        }

        .right {
            flex: 1;
            padding-top: 74px;
            min-width: 0;
        }

        .field {
            background: #eaeaea;
            height: 25px;
            margin-bottom: 20px;
            display: flex;
            align-items: flex-start;
            gap: 8px;
            overflow: hidden;
        }

        .field .label {
            font-size: 14px;
            line-height: 19px;
            border-bottom: 2px solid #111;
            padding-right: 2px;
            white-space: nowrap;
        }

        .field .value {
            font-size: 14px;
            line-height: 19px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .export {
            display: block;
            margin: 28px 0 0 58px;
            background: #d9d9d9;
            border: 0;
            border-radius: 14px;
            padding: 2px 6px;
            font: inherit;
            font-size: 20px;
            color: #1a1a1a;
            cursor: pointer;
        }

        .export:hover {
            background: #cbcbcb;
        }

        .export:focus-visible {
            outline: 2px solid #555;
            outline-offset: 2px;
        }

        @media (max-width: 720px) {
            .page {
                flex-direction: column;
            }

            .left {
                flex: none;
                width: 100%;
            }

            .photo {
                width: 100%;
            }

            .right {
                padding-top: 0;
                width: 100%;
            }
        }
    </style>
</head>

<body>
    <?php
    session_start();
    require_once('./basic/basicPage.php');
    require_once('./basic/basic-frame.php');

    if (empty($_GET['id'])) {
        header('Location: ./home.php');
        exit;
    }

    $id = $_GET['id'];
    $stmt1 = $conn->prepare("SELECT role WHERE user_id = ?, name =?, password = ?");
    $stmt1->bind_param("i", $id);
    $stmt1->execute();

    $result = $stmt1->get_result();
    $row = $result->fetch_assoc();

    $imageExists = false;
    $name = 'no img found';
    $fileLocation = './photos/none';

    if ($row) {
        $fullPath = './photos/' . $row['fileLocation'];

        if (!empty($row['fileLocation']) && file_exists($fullPath)) {
            $name         = $row['name'];
            $fileLocation = $fullPath;
            $imageExists  = true;
        }
    }
    $stmt1->close();
    ?>


    <main class="page">
        <section class="left">
            <img class="photo" src="<?php echo htmlspecialchars($fileLocation); ?>" alt="<?php echo htmlspecialchars($name); ?>">
            <div class="scrollbar"></div>
        </section>
        <section class="right">
            <form method="post" action="./betweenPage/getPDFXLS.php">
                <div class="field">
                    <span class="label">name of the photo:</span>
                    <span class="value" id="text"><?php echo htmlspecialchars($name); ?></span>
                    <input type="hidden" name="text" value="<?php echo htmlspecialchars($name); ?>">
                </div>
                <div class="field">
                    <span class="label">location name:</span>
                    <span class="value" id="location-name"><?php echo htmlspecialchars($row['location']); ?></span>
                    <input type="hidden" name="location" value="<?php echo htmlspecialchars($row['location']); ?>">
                </div>

                <!-- hidden field carrying the image location to the next page -->
                <input type="hidden" name="img_location" value="<?php echo htmlspecialchars($fileLocation); ?>">

                <button class="export" type="submit">export to pdf and xls</button>
            </form>

        </section>
    </main>
</body>

</html>