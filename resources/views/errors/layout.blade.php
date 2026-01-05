<!DOCTYPE html>

<html lang="fa" dir="rtl">

<head>

    @include('layouts.client.link')

    <title>@yield('title', 'خطا')</title>

    <style>

        .error-container {

            min-height: 100vh;

            display: flex;

            align-items: center;

            justify-content: center;

            padding: 2rem;

        }


        .astronaut-image {

            max-width: 100%;

            height: auto;

            animation: float 6s ease-in-out infinite;

        }


        @keyframes float {

            0%, 100% {

                transform: translateY(0px);

            }

            50% {

                transform: translateY(-20px);

            }

        }


        .error-code {

            font-size: clamp(4rem, 15vw, 10rem);

            font-weight: 800;

            line-height: 1;

        }


        .error-title {

            font-size: clamp(1.5rem, 4vw, 2.5rem);

            font-weight: 700;

        }


        .error-description {

            font-size: clamp(1rem, 2vw, 1.25rem);

        }


        @media (max-width: 768px) {

            .error-container {

                padding: 1rem;

            }

        }

    </style>

</head>

<body class="bg-white dark:bg-gray-900 transition-colors duration-300">

<div class="error-container">

    <div class="max-w-7xl mx-auto w-full">

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12 items-center">

            <!-- Right Side: Image -->

            <div class="order-2 lg:order-1 flex justify-center">

                <img src="@yield('image', 'https://hostiran.net/assets/img/error/404.png')"

                     alt="Error Illustration"

                     class="astronaut-image w-full max-w-md lg:max-w-lg">

            </div>


            <!-- Left Side: Content -->

            <div class="order-1 lg:order-2 text-center lg:text-right space-y-6">

                <!-- Error Code -->

                <div class="error-code bg-gradient-to-l from-blue-500 to-cyan-500 bg-clip-text text-transparent">

                    @yield('code')

                </div>


                <!-- Error Title -->

                <h1 class="error-title text-gray-900 dark:text-white">

                    @yield('title')

                </h1>


                <!-- Error Description -->

                <p class="error-description text-gray-600 dark:text-gray-400 max-w-md mx-auto lg:mx-0">

                    @yield('message')

                </p>


                <!-- Action Button -->

                <div class="pt-4">

                    <a href="@yield('button-link', '/')"

                       class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-l from-blue-500 to-cyan-500 hover:from-blue-600 hover:to-cyan-600 text-white font-bold rounded-full transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl">

                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>

                        </svg>

                        <span>@yield('button-text', 'بازگشت به صفحه اصلی')</span>

                    </a>

                </div>


                @yield('extra-buttons')

            </div>

        </div>

    </div>

</div>


@include('layouts.client.script')

</body>

</html>
