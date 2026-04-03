<div>
    <div class="space-y-14">
        <!-- container -->
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            <!-- story -->
            <livewire:client.home.story.index />
            <!-- end story -->

            <!-- intro -->
            <livewire:client.home.intro.index lazy/>
            <!-- end intro -->

            <!-- features -->
            <livewire:client.home.features.index lazy/>
            <!-- end features -->

{{--            <livewire:client.home.collaboration.index lazy/>--}}

            <!-- section:latest-courses -->
            <livewire:client.home.latest-course.index/>
            <!-- end section:latest-courses -->

            <!-- section:sdfr-help -->
            <livewire:client.home.sdfr-help.index lazy/>
            <!-- end section:sdfr-help -->
        </div>
        <!-- end container -->

        <!-- feedback -->
        <!-- end feedback -->
{{--        <div class="max-w-7xl space-y-14 px-4 mx-auto">--}}
{{--            <livewire:client.home.top-student.index lazy/>--}}
{{--        </div>--}}

        <!-- testimonials -->
        <livewire:client.home.testimonials.index />
        <!-- end testimonials -->
        <livewire:client.home.feedback.index />

        <!-- blog -->
        <livewire:client.home.blog.index lazy/>


        <!-- end blog -->
    </div>

</div>
