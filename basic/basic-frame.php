<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>basicPage frame</title>
    <link rel="stylesheet" href="./css&script/basic-frame.css">
</head>

<body>

    <header class="topbar">
        <button class="icon-btn menu" aria-label="Menu" onclick="openDrawer()">
            <!-- Hamburger icon: thin bars on desktop, bold bars on mobile via CSS -->
            <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect class="bar" x="3" y="6" width="26" height="3" rx="1" fill="currentColor" />
                <rect class="bar" x="3" y="14.5" width="26" height="3" rx="1" fill="currentColor" />
                <rect class="bar" x="3" y="23" width="26" height="3" rx="1" fill="currentColor" />
            </svg>
        </button>

        <div class="divider"></div>

        <button class="icon-btn home logo-btn" aria-label="Home">
            <!-- Site logo: location pin + camera, spins on hover -->
            <svg viewBox="0 0 400 400" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="bgGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FF7A59" />
                        <stop offset="100%" stop-color="#F94D8A" />
                    </linearGradient>
                    <linearGradient id="pinGrad" x1="0%" y1="0%" x2="0%" y2="100%">
                        <stop offset="0%" stop-color="#FFFFFF" />
                        <stop offset="100%" stop-color="#F1F1F1" />
                    </linearGradient>
                </defs>

                <circle cx="200" cy="200" r="190" fill="url(#bgGrad)" />
                <circle cx="200" cy="200" r="190" fill="none" stroke="#FFFFFF" stroke-opacity="0.15" stroke-width="6" />

                <path d="M200 90
                     C150 90 115 128 115 175
                     C115 235 200 305 200 305
                     C200 305 285 235 285 175
                     C285 128 250 90 200 90 Z" fill="url(#pinGrad)" />


                <g transform="translate(200,200) scale(2.687) translate(-200,-200)">
                    <rect x="150" y="150" width="100" height="72" rx="10" fill="#2E2A45" />
                    <rect x="182" y="136" width="36" height="18" rx="4" fill="#2E2A45" />
                    <circle cx="200" cy="187" r="26" fill="#F94D8A" />
                    <circle cx="200" cy="187" r="17" fill="#2E2A45" />
                    <circle cx="200" cy="187" r="9" fill="#FF7A59" />
                    <circle cx="228" cy="163" r="5" fill="#FFCF5C" />
                </g>

                <circle cx="95" cy="130" r="7" fill="#FFFFFF" fill-opacity="0.85" />
                <circle cx="310" cy="140" r="5" fill="#FFFFFF" fill-opacity="0.7" />
                <circle cx="320" cy="230" r="6" fill="#FFFFFF" fill-opacity="0.6" />
            </svg>
        </button>

        <div class="search-wrap">
            <input type="text" placeholder="">
            <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="10.5" cy="10.5" r="6.5" stroke="currentColor" stroke-width="2" />
                <line x1="15.3" y1="15.3" x2="21" y2="21" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" />
            </svg>
        </div>
    </header>

    <!-- Darkening overlay, click to close (shared by drawer + popup) -->
    <div class="overlay" id="overlay" onclick="closeAll()"></div>

    <!-- Slide-in drawer -->
    <nav class="drawer" id="drawer" aria-label="Main menu">
        <div class="drawer-header">
            <button class="icon-btn" aria-label="Close menu" onclick="closeDrawer()">
                <svg viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <rect x="3" y="6" width="26" height="3" rx="1" fill="currentColor" />
                    <rect x="3" y="14.5" width="26" height="3" rx="1" fill="currentColor" />
                    <rect x="3" y="23" width="26" height="3" rx="1" fill="currentColor" />
                </svg>
            </button>
        </div>

        <div class="drawer-nav">
            <button class="drawer-link" onclick="window.location.href='home.php'"><span class="dot"></span>home page</button>
            <button class="drawer-link" onclick="window.location.href='list.php'"> <span class="dot"></span>list of other photos</button>
            <button class="drawer-link" onclick="window.location.href='add-edit.php'"><span class="dot"></span>add your own photo</button>
            <button class="drawer-link" onclick="openPasswordPopup()"><span class="dot"></span>edit your own
                photo</button>
        </div>
    </nav>

    <!-- Password popup (opened from "edit your own photo") -->
    <form action="./betweenPage/pre-edit.php" class="password-popup" id="passwordPopup" method="post" enctype="multipart/form-data">
        <p class="popup-title">password of the img:</p>
        <input name="location" type="hidden" placeholder="<?php echo $location ?>" value="<?php echo $location ?>">
        <input name="password" type="password" id="imgPasswordInput" autocomplete="off">
        <input type="hidden" name="which?" value="edit">
        <input type="submit" value="confirm">
    </form>

    <script src="./css&script/basic-frame.js"></script>

</body>

</html>