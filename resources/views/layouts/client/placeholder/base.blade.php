<div>
    <style>

        @keyframes skeleton-loading {

            0% {

                background-position: -200% 0;

            }

            100% {

                background-position: 200% 0;

            }

        }


        .skeleton {

            background: linear-gradient(
                90deg,
                rgba(229, 231, 235, 0.4) 0%,
                rgba(229, 231, 235, 0.6) 50%,
                rgba(229, 231, 235, 0.4) 100%
            );

            background-size: 200% 100%;

            animation: skeleton-loading 1.5s ease-in-out infinite;

        }


        .dark .skeleton {

            background: linear-gradient(
                90deg,
                rgba(39, 39, 42, 0.4) 0%,
                rgba(63, 63, 70, 0.6) 50%,
                rgba(39, 39, 42, 0.4) 100%
            );

            background-size: 200% 100%;

        }


        .skeleton-circle {

            border-radius: 9999px;

        }


        .skeleton-rounded {

            border-radius: 0.75rem;

        }


        .skeleton-rounded-xl {

            border-radius: 1rem;

        }


        .skeleton-rounded-2xl {

            border-radius: 1.5rem;

        }


        .skeleton-rounded-3xl {

            border-radius: 1.75rem;

        }


        .skeleton-text {

            height: 1rem;

            margin-bottom: 0.5rem;

            border-radius: 0.25rem;

        }


        .skeleton-text-sm {

            height: 0.875rem;

        }


        .skeleton-text-lg {

            height: 1.25rem;

        }

    </style>

</div>
