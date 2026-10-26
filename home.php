<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Read once, then remove it so a refresh doesn't show the popup again
$newPassword = $_SESSION['new_password'] ?? null;
unset($_SESSION['new_password']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <link rel="stylesheet" href="./css/home.css">
</head>

<body>
    <?php
    $location = "home.php";
    require_once('./basic/basic-frame.php');
    ?>

    <!-- ---------- MAIN CONTENT ---------- -->
    <main class="content">
        <div class="description-box">
            bij onze website kan je andere mensen helpen om je mooie fotos op testuren en
            daar mee andere mensen helpen om de kieze om naar die locatie willen gaan
        </div>

        <div class="photos-box">
            photos
        </div>
    </main>

    <nav class="bottom-nav">
        <button class="nav-item" aria-label="Add" onclick="window.location.href='add-edit.php'">
            <span class=" icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="4" width="18" height="16" rx="2" stroke="currentColor" stroke-width="1.8" />
                    <circle cx="8.5" cy="9.5" r="1.5" fill="currentColor" />
                    <path d="M3 16l5-5 4 4 3-3 6 6" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
            <span class="label">add</span>
        </button>

        <button class="nav-item" aria-label="Edit" onclick="openPasswordPopup()">
            <span class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M4 20h16" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
                    <path d="M14.5 4.5l3 3L8 17l-4 1 1-4 9.5-9.5z" stroke="currentColor" stroke-width="1.8"
                        stroke-linejoin="round" />
                </svg>
            </span>
            <span class="label">edit</span>
        </button>

        <button class="nav-item" aria-label="List">
            <span class="icon-wrap">
                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="6" width="14" height="14" rx="1.5" stroke="currentColor" stroke-width="1.8" />
                    <path d="M7 21h11a2 2 0 0 0 2-2V6" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" />
                    <path d="M6 17l3-3 2.5 2.5L15 13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </span>
            <span class="label">list</span>
        </button>
    </nav>

    <?php if ($newPassword): ?>
        <div class="password-popup" id="passwordPopup" role="dialog" aria-live="polite">
            <button class="popup-close" onclick="closePasswordSuccess()" aria-label="Close">&times;</button>
            <p class="popup-title">your photo has been added to the list</p>
            <p class="popup-sub">the password to edit your img:</p>
            <div class="password-box" id="passwordBox" title="Click to copy">
                <?= htmlspecialchars($newPassword) ?>
            </div>
            <small class="copy-hint" id="copyHint">click the password to copy it</small>
        </div>

        <script>
            const popup = document.getElementById('passwordPopup');

            // Slide in from the right after the page has rendered
            requestAnimationFrame(() => requestAnimationFrame(() => popup.classList.add('show')));

            function closePasswordSuccess() {
                popup.classList.remove('show');
                setTimeout(() => popup.remove(), 500);
            }

            document.getElementById('passwordBox').addEventListener('click', () => {
                navigator.clipboard.writeText(document.getElementById('passwordBox').textContent.trim())
                    .then(() => document.getElementById('copyHint').textContent = 'copied!');
            });
        </script>
    <?php endif; ?>
</body>

</html>