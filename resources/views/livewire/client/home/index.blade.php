<div>
    @push('link')
        <style>

            .story-container {
                display: flex;
                align-items: center;
                overflow: hidden;
                width: 80%;
                margin: 20px auto;
                position: relative;
            }
            .stories {
                display: flex;
                gap: 10px;
                overflow-x: auto;
                scroll-behavior: smooth;
                white-space: nowrap;
                width: 100%;
                padding: 10px 0;
            }
            .story-wrapper {
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            .story {
                width: 80px;
                height: 80px;
                border-radius: 50%;
                background: gray;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                flex-shrink: 0;
                border: 4px solid;
                border-image-source: linear-gradient(315deg,#e03d96,#7d4c9e);
                border-image-slice: 1;
            }
            .story-title {
                margin-top: 5px;
                font-size: 14px;
                color: #ddd;
            }
            .arrow {
                position: absolute;
                background: rgba(255, 255, 255, 0.3);
                border: none;
                padding: 10px;
                cursor: pointer;
                top: 50%;
                transform: translateY(-50%);
                z-index: 10;
            }
            .arrow-left { left: -40px; }
            .arrow-right { right: -40px; }
            .modal {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0, 0, 0, 0.8);
                justify-content: center;
                align-items: center;
            }
            .modal-content {
                background: white;
                padding: 20px;
                border-radius: 10px;
                text-align: center;
                position: relative;
            }
            .close {
                position: absolute;
                top: 10px;
                right: 10px;
                cursor: pointer;
            }
            .like {
                font-size: 24px;
                cursor: pointer;
                color: gray;
            }
            .liked { color: red; }
        </style>
    @endpush
    <div class="space-y-14">
        <!-- container -->
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            <!-- story -->
            <livewire:client.home.story.index lazy/>
            <!-- end story -->

            <!-- intro -->
            <livewire:client.home.intro.index lazy/>
            <!-- end intro -->

            <!-- features -->
            <livewire:client.home.features.index lazy/>
            <!-- end features -->

            <livewire:client.home.collaboration.index/>

            <!-- section:latest-courses -->
            <livewire:client.home.latest-course.index />
            <!-- end section:latest-courses -->


        </div>
        <!-- end container -->

        <!-- feedback -->
        <!-- end feedback -->
        <div class="max-w-7xl space-y-14 px-4 mx-auto">
            <livewire:client.home.top-student.index/>

        </div>
        <livewire:client.home.feedback.index/>

        <!-- blog -->
        <livewire:client.home.blog.index/>


        <!-- end blog -->
    </div>

    @push('script')
            <script>
                function openModal(videoSrc) {
                    document.getElementById("modal").style.display = "flex";
                    document.getElementById("videoSource").src = videoSrc;
                    document.getElementById("video").load();
                }
                function closeModal() {
                    document.getElementById("modal").style.display = "none";
                    document.getElementById("video").pause();
                }
                function toggleLike(element) {
                    element.classList.toggle("liked");
                }
                function scrollStories(amount) {
                    document.getElementById("stories").scrollBy({ left: amount, behavior: "smooth" });
                }
            </script>
    @endpush
</div>
