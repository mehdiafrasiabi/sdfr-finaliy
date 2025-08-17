<div>

<style>
    .intro-skeleton {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
        background-color: #0f0f0f;
        color: white;
        min-height: 100vh;
        font-family: sans-serif;
    }

    .intro-skeleton .container {
        display: flex;
        background-color: #181818;
        border-radius: 20px;
        padding: 2rem;
        max-width: 1200px;
        width: 100%;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .intro-skeleton .skeleton-img {
        flex: 1 1 100px;
        aspect-ratio: 1 / 1.3;
        background: #333;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
    }

    .intro-skeleton .skeleton-img::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: url('https://via.placeholder.com/400x520?text=Image');
        background-size: cover;
        background-position: center;
        filter: grayscale(1) brightness(0.4);
    }

    .intro-skeleton .skeleton-content {
        flex: 2 1 400px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        gap: 1rem;
    }

    .intro-skeleton .skeleton-badge {
        width: 100px;
        height: 20px;
        background: #333;
        border-radius: 10px;
        animation: shimmer 2s infinite linear;
    }

    .intro-skeleton .skeleton-title {
        width: 70%;
        height: 30px;
        background: #333;
        border-radius: 10px;
        animation: shimmer 2s infinite linear;
    }

    .intro-skeleton .skeleton-subtitle {
        width: 90%;
        height: 24px;
        background: #333;
        border-radius: 10px;
        animation: shimmer 2s infinite linear;
    }

    .intro-skeleton .skeleton-text {
        width: 100%;
        height: 100px;
        background: #333;
        border-radius: 6px;
        animation: shimmer 2s infinite linear;
    }

    .intro-skeleton .skeleton-button {
        width: 130px;
        height: 40px;
        background: #333;
        border-radius: 9999px;
        animation: shimmer 2s infinite linear;
        margin-top: 1rem;
    }

    @keyframes shimmer {
        0% {
            background-position: -200px 0;
        }
        100% {
            background-position: calc(200px + 100%) 0;
        }
    }

    .intro-skeleton .skeleton-badge,
    .intro-skeleton .skeleton-title,
    .intro-skeleton .skeleton-subtitle,
    .intro-skeleton .skeleton-text,
    .intro-skeleton .skeleton-button {
        background: linear-gradient(
            90deg,
            #2a2a2a 25%,
            #3a3a3a 50%,
            #2a2a2a 75%
        );
        background-size: 200% 100%;
    }

    /* ریسپانسیو برای موبایل */
    @media (max-width: 768px) {
        .intro-skeleton .container {
            flex-direction: column;
            align-items: center;
            text-align: center;
        }

        .intro-skeleton .skeleton-content {
            align-items: center;
        }


    }
</style>

    <section class="intro-skeleton">
        <div class="container">
            <div class="skeleton-img"></div>
            <div class="skeleton-content">
                <div class="skeleton-badge"></div>
                <div class="skeleton-title"></div>
                <div class="skeleton-subtitle"></div>
                <div class="skeleton-text"></div>
                <div class="skeleton-button"></div>
            </div>
        </div>
    </section>
</div>

