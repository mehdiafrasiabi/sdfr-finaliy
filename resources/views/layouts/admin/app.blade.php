<!DOCTYPE html>
<html lang="fa">
<head>
    @include('layouts.admin.link')
    {!! SEO::generate() !!}
</head>
<body class="layout-boxed dark">
<!-- BEGIN LOADER -->
<div id="load_screen"> <div class="loader"> <div class="loader-content">
            <div class="spinner-grow align-self-center"></div>
        </div></div></div>
<!--  END LOADER -->

<!--  BEGIN NAVBAR  -->
<livewire:admin.layout.header>
    <!--  END NAVBAR  -->

    <!--  BEGIN MAIN CONTAINER  -->
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>

        <!--  BEGIN SIDEBAR  -->
        <livewire:admin.layout.sidebar>

            <!--  END SIDEBAR  -->

            <!--  BEGIN CONTENT AREA  -->
            <div id="content" class="main-content">
                <div class="layout-px-spacing">

                    <div class="middle-content container-xxl p-0">

                        <div class="row layout-top-spacing">

                            {{$slot}}

                        </div>

                    </div>

                </div>
                <!--  BEGIN FOOTER  -->
            </div>
            <!--  END CONTENT AREA  -->

    </div>
    <!-- END MAIN CONTAINER -->

@include('layouts.admin.script')


</body>
</html>
