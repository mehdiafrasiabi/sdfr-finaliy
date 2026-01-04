<div class="min-h-screen flex items-center justify-center bg-background p-5">

    <div class="text-center">

        <div class="loading-spinner mx-auto mb-4"></div>

        <p class="text-muted">در حال انتقال به صفحه ورود...</p>

    </div>



    @push('link')

        <style>

            .loading-spinner {

                border: 3px solid hsl(var(--secondary));

                border-top-color: hsl(var(--primary));

                border-radius: 50%;

                width: 40px;

                height: 40px;

                animation: spin 0.8s linear infinite;

            }



            @keyframes spin {

                to { transform: rotate(360deg); }

            }

        </style>

    @endpush

</div>
