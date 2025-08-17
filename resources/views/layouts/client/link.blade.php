<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="icon" type="image/svg+xml" href="/client/assets/images/favicon.svg" />
<link rel="stylesheet" href="/client/assets/css/dependencies/swiper-bundle.min.css" />
<link rel="stylesheet" href="/client/assets/css/dependencies/plyr.min.css" />
<link rel="stylesheet" href="/client/assets/css/fonts.css" />
<link rel="stylesheet" href="/client/assets/css/app.css" />
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<link href="/client/assets/css/custom-pagination.css" rel="stylesheet" type="text/css" />
<link href="/client/assets/css/custom-pagination2.css" rel="stylesheet" type="text/css" />
<style>
    #loadingOverlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000; /* Black background for loading */
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        z-index: 9999; /* Ensure it's on top */
        opacity: 1;
        transition: opacity 1s ease-out; /* Smooth fade out */
    }
    #loadingOverlay.hidden {
        opacity: 0;
        pointer-events: none; /* Disable interactions when hidden */
    }
    .loading-logo {
        max-width: 200px; /* Size for loading logo */
        height: auto;
        border-radius: 12px;
        animation: pulse 2s infinite ease-in-out; /* Pulsing animation for logo */
    }

</style>

@stack('link')
