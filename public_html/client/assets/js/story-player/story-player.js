/**
 * StoryPlayer - A customizable story viewer component
 * @version 2.2.0
 * @class
 */
class StoryPlayer {
    /**
     * Constructor to initialize the story player.
     * @param {string} containerId - The ID of the container element.
     * @param {Array} stories - An array of story objects.
     * @param {Object} options - Configuration options.
     */
    constructor(containerId, stories, options = {}) {
        this.container = document.getElementById(containerId);
        this.stories = stories;

        // Current story index
        this.currentStoryIndex = 0;

        // Timers / animation ids
        this.timer = null;                 // image story timer
        this.videoProgressRafId = null;    // requestAnimationFrame id for video progress

        // Image progress state (for pause/resume)
        this.currentImageDuration = null;      // total duration (ms)
        this.imageRemainingDuration = null;    // remaining duration (ms)

        // State
        this.isPaused = false;
        this.isMuted = false;

        // Touch state
        this.touchStartX = 0;
        this.touchEndX = 0;
        this.holdTimeout = null;
        this.isHolding = false;
        this.ignoreNextClick = false;

        // Bound handlers (so we can remove listeners cleanly)
        this.boundHandleKeydown = this.handleKeydown.bind(this);

        // Options
        this.options = {
            isLoggedIn: options.isLoggedIn || false,
            loginUrl: options.loginUrl || '/login',
            onLike: options.onLike || null,
            ...options
        };

        this.init();
    }

    /** Initialize the story player by rendering stories */
    init() {
        this.renderStories();
    }

    /** Render the stories in the container. */
    renderStories() {
        this.container.innerHTML = `
            <div class="stories-wrapper no-overflow">
                <button class="stories-nav-btn stories-nav-prev" aria-label="Scroll Left">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                        <!-- chevron-left -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <div class="stories-scroll">
                    ${this.stories.map((story, index) => `
                        <div class="story" data-index="${index}">
                            <div class="story-avatar">
                                <img src="${story.avatar}" alt="${story.user}" onerror="this.src='https://picsum.photos/70'">
                            </div>
                            <div class="story-username dark:!text-white">${story.user}</div>
                        </div>
                    `).join('')}
                </div>

                <button class="stories-nav-btn stories-nav-next" aria-label="Scroll Right">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                        <!-- chevron-right -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        `;

        const wrapper = this.container.querySelector('.stories-wrapper');
        const scrollContainer = this.container.querySelector('.stories-scroll');
        const prevBtn = this.container.querySelector('.stories-nav-prev');
        const nextBtn = this.container.querySelector('.stories-nav-next');

        // Show scroll buttons ONLY when the row overflows (desktop requirement)
        const updateNavVisibility = () => {
            const hasOverflow = scrollContainer.scrollWidth > scrollContainer.clientWidth + 1;
            wrapper.classList.toggle('no-overflow', !hasOverflow);
        };

        // Scroll controls (physical left/right)
        prevBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({left: -200, behavior: 'smooth'});
        });

        nextBtn.addEventListener('click', () => {
            scrollContainer.scrollBy({left: 200, behavior: 'smooth'});
        });

        // Make the list start from the right side (RTL-like) in a consistent way across browsers
        // Different browsers + row-reverse can behave differently, so we try both ends and keep the one
        // that places the FIRST story closest to the right edge (like Instagram RTL).
        const alignToRtlStart = () => {
            const firstStory = scrollContainer.querySelector('.story[data-index="0"]') || scrollContainer.querySelector('.story');
            if (!firstStory) return;

            const maxScroll = Math.max(0, scrollContainer.scrollWidth - scrollContainer.clientWidth);
            const containerRect = scrollContainer.getBoundingClientRect();

            const candidates = [0, maxScroll];
            let best = candidates[0];
            let bestDist = Infinity;

            for (const candidate of candidates) {
                scrollContainer.scrollLeft = candidate;
                const storyRect = firstStory.getBoundingClientRect();
                const dist = Math.abs(containerRect.right - storyRect.right);
                if (dist < bestDist) {
                    bestDist = dist;
                    best = candidate;
                }
            }

            scrollContainer.scrollLeft = best;
        };

        requestAnimationFrame(() => {
            alignToRtlStart();
            updateNavVisibility();
        });

        window.addEventListener('resize', () => {
            updateNavVisibility();
            requestAnimationFrame(alignToRtlStart);
        });

        // One more pass after the browser has had a chance to load images/fonts
        setTimeout(alignToRtlStart, 0);

        // Setup story click handlers (scoped to this container)
        this.container.querySelectorAll('.story').forEach(story => {
            story.addEventListener('click', () => {
                this.currentStoryIndex = parseInt(story.dataset.index, 10);
                this.openStory();
            });
        });
    }

    /** Open the selected story. */
    openStory() {
        this.createModal();
        this.loadStory();
        document.body.style.overflow = 'hidden';
    }

    /** Create the modal for displaying stories. */
    createModal() {
        this.modal = document.createElement('div');
        this.modal.className = 'story-modal';

        const segmentsHtml = this.stories.map((_, idx) => `
            <div class="story-progress-segment" data-index="${idx}">
                <div class="story-progress-segment-fill"></div>
            </div>
        `).join('');

        this.modal.innerHTML = `
            <div class="story-modal-backdrop"></div>
            <div class="story-modal-container">

                <!-- Desktop Navigation (RTL: Next is LEFT, Prev is RIGHT) -->
                <button class="story-nav-btn story-next-btn desktop-only" aria-label="Next Story">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                        <!-- chevron-left -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5" />
                    </svg>
                </button>

                <div class="story-content-wrapper">
                    <!-- Progress Bar -->
                    <div class="story-progress-container" aria-hidden="true">
                        <div class="story-progress-segments">
                            ${segmentsHtml}
                        </div>
                    </div>

                    <!-- Header -->
                    <div class="story-header">
                        <div class="story-header-left">
                            <button class="story-header-btn story-close-btn" aria-label="Close">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>

                            <button class="story-header-btn story-pause-btn" aria-label="Pause">
                                <svg class="pause-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
                                </svg>
                                <svg class="play-icon" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="20" height="20">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
                                </svg>
                            </button>
                        </div>

                        <div class="story-header-right">
                            <div class="story-user-info">
                                <span class="story-username-text"></span>
                                <span class="story-time-text">اکنون</span>
                            </div>
                            <img class="story-user-avatar" src="" alt="">
                        </div>
                    </div>

                    <!-- Media Container with tap zones -->
                    <div class="story-media-container">
                        <div class="story-tap-zone story-tap-right" aria-label="Previous Story"></div>
                        <div class="story-tap-zone story-tap-left" aria-label="Next Story"></div>

                        <!-- Loading (Instagram-like dots) -->
                        <div class="story-loading" aria-hidden="true">
                            <span></span><span></span><span></span>
                        </div>

                        <!-- Mute indicator (center icon) -->
                        <div class="story-mute-indicator" aria-hidden="true">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="28" height="28" aria-hidden="true">
                                <path d="M16.5 12a4.5 4.5 0 0 0-2.25-3.897v7.794A4.5 4.5 0 0 0 16.5 12ZM19.5 12a7.5 7.5 0 0 0-3.75-6.495v2.278A5.25 5.25 0 0 1 17.25 12a5.25 5.25 0 0 1-1.5 4.217v2.278A7.5 7.5 0 0 0 19.5 12ZM4.5 9.75H8.25L12.22 6.53a.75.75 0 0 1 1.23.586v9.768a.75.75 0 0 1-1.23.586L8.25 14.25H4.5a.75.75 0 0 1-.75-.75v-3a.75.75 0 0 1 .75-.75Z"/>
                                <path d="M21.53 7.47a.75.75 0 0 0-1.06 0L7.47 20.47a.75.75 0 1 0 1.06 1.06l13-13a.75.75 0 0 0 0-1.06Z"/>
                            </svg>
                        </div>

                        <img class="story-media story-image" alt="Story" style="display: none;">
                        <video class="story-media story-video" playsinline preload="metadata"></video>
                    </div>

                    <!-- Footer Actions -->
                    <div class="story-footer">
                        <div class="story-footer-left">
                            <button class="story-action-btn story-like-btn" aria-label="Like">
                                <svg class="like-icon-empty" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z" />
                                </svg>
                                <svg class="like-icon-filled" style="display: none;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" width="24" height="24">
                                    <path d="m11.645 20.91-.007-.003-.022-.012a15.247 15.247 0 0 1-.383-.218 25.18 25.18 0 0 1-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0 1 12 5.052 5.5 5.5 0 0 1 16.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 0 1-4.244 3.17 15.247 15.247 0 0 1-.383.219l-.022.012-.007.004-.003.001a.752.752 0 0 1-.704 0l-.003-.001Z" />
                                </svg>
                                <span class="like-count">0</span>
                            </button>

                            <button class="story-action-btn story-mute-btn" aria-label="Mute">
                                <svg class="mute-icon-off" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.114 5.636a9 9 0 0 1 0 12.728M16.463 8.288a5.25 5.25 0 0 1 0 7.424M6.75 8.25l4.72-4.72a.75.75 0 0 1 1.28.53v15.88a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.507-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.396C2.806 8.756 3.63 8.25 4.51 8.25H6.75Z" />
                                </svg>
                                <svg class="mute-icon-on" style="display: none;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 9.75 19.5 12m0 0 2.25 2.25M19.5 12l2.25-2.25M19.5 12l-2.25 2.25m-10.5-6 4.72-4.72a.75.75 0 0 1 1.28.531V19.94a.75.75 0 0 1-1.28.53l-4.72-4.72H4.51c-.88 0-1.704-.506-1.938-1.354A9.009 9.009 0 0 1 2.25 12c0-.83.112-1.633.322-2.395C2.806 8.757 3.63 8.25 4.51 8.25H6.75Z" />
                                </svg>
                            </button>
                        </div>

                        <!-- Widget Link -->
                        <a href="#" class="story-widget-link" target="_blank" style="display: none;">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="18" height="18">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                            </svg>
                            <span class="widget-link-text"></span>
                        </a>
                    </div>
                </div>

                <!-- Desktop Navigation -->
                <button class="story-nav-btn story-prev-btn desktop-only" aria-label="Previous Story">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" width="24" height="24">
                        <!-- chevron-right -->
                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                    </svg>
                </button>
            </div>
        `;

        document.body.appendChild(this.modal);
        this.setupModalEventListeners();
    }


    /** Load the current story into the modal. */
    loadStory() {
        const story = this.stories[this.currentStoryIndex];
        this.modal.style.display = 'flex';

        // Update header
        this.modal.querySelector('.story-username-text').textContent = story.user;
        this.modal.querySelector('.story-user-avatar').src = story.avatar;

        // Update like button
        this.updateLikeButton(story);

        // Update widget link
        const widgetLink = this.modal.querySelector('.story-widget-link');
        if (story.link && story.linkTitle) {
            widgetLink.href = story.link;
            widgetLink.querySelector('.widget-link-text').textContent = story.linkTitle;
            widgetLink.style.display = 'flex';
        } else {
            widgetLink.style.display = 'none';
        }

        // Desktop navigation buttons: hide if prev/next doesn't exist
        this.updateDesktopStoryNavButtons();

        // Mute button only for videos
        const muteBtn = this.modal.querySelector('.story-mute-btn');
        if (muteBtn) {
            muteBtn.style.display = story.type === 'video' ? '' : 'none';
        }

        // Reset overlays
        this.hideLoading();
        this.updateMuteIndicator(true);

        // Instagram-style segmented progress (past segments filled, current starts at 0)
        this.prepareProgressSegmentsForCurrentStory();

        // Load media
        if (story.type === 'video') {
            this.setupVideo(story);
        } else {
            this.setupImage(story);
        }

        // Mark the story as viewed
        const avatarElement = this.container.querySelectorAll('.story-avatar')[this.currentStoryIndex];
        if (avatarElement) {
            avatarElement.classList.add('viewed');
        }
    }


    /** Update the like button state */
    updateLikeButton(story) {
        const likeBtn = this.modal.querySelector('.story-like-btn');
        const emptyIcon = likeBtn.querySelector('.like-icon-empty');
        const filledIcon = likeBtn.querySelector('.like-icon-filled');
        const countSpan = likeBtn.querySelector('.like-count');

        countSpan.textContent = story.likesCount || 0;

        if (story.isLiked) {
            emptyIcon.style.display = 'none';
            filledIcon.style.display = 'block';
            likeBtn.classList.add('liked');
        } else {
            emptyIcon.style.display = 'block';
            filledIcon.style.display = 'none';
            likeBtn.classList.remove('liked');
        }
    }


    /* ==============================
       Progress Segments Helpers (Instagram-style)
    ============================== */

    /** Get a segment fill element by story index */
    getProgressFillByIndex(index) {
        if (!this.modal) return null;
        return this.modal.querySelector(`.story-progress-segment[data-index="${index}"] .story-progress-segment-fill`);
    }

    /** Get the CURRENT segment fill */
    getCurrentProgressFill() {
        return this.getProgressFillByIndex(this.currentStoryIndex);
    }

    /** Prepare segments when a story is (re)loaded: past=100%, current=0%, future=0% */
    prepareProgressSegmentsForCurrentStory() {
        if (!this.modal) return;

        const segments = this.modal.querySelectorAll('.story-progress-segment');
        segments.forEach(seg => {
            const idx = parseInt(seg.getAttribute('data-index'), 10);
            const fill = seg.querySelector('.story-progress-segment-fill');
            if (!fill) return;

            fill.style.transition = 'none';

            if (idx < this.currentStoryIndex) {
                fill.style.width = '100%';
            } else {
                fill.style.width = '0%';
            }
        });

        // Ensure current is 0% (explicit)
        const currentFill = this.getCurrentProgressFill();
        if (currentFill) {
            currentFill.style.transition = 'none';
            currentFill.style.width = '0%';
            // Force reflow so later transitions are applied reliably
            void currentFill.offsetWidth;
        }
    }

    /** Reset ALL segments to 0% (used when switching/closing) */
    resetAllProgressSegments() {
        if (!this.modal) return;
        this.modal.querySelectorAll('.story-progress-segment-fill').forEach(fill => {
            fill.style.transition = 'none';
            fill.style.width = '0%';
        });
    }

    /** Set CURRENT segment progress percent (0..100) */
    setCurrentSegmentProgress(percent, transitionMs = null) {
        const fill = this.getCurrentProgressFill();
        if (!fill) return;

        const clamped = Math.max(0, Math.min(100, percent));

        if (transitionMs == null) {
            fill.style.transition = 'none';
        } else {
            fill.style.transition = `width ${transitionMs}ms linear`;
            // Force reflow to apply the transition
            void fill.offsetWidth;
        }

        fill.style.width = `${clamped}%`;
    }


    /** Setup the video story. */
    setupVideo(story) {
        const videoMedia = this.modal.querySelector('.story-video');
        const imageMedia = this.modal.querySelector('.story-image');

        // Stop previous image timer state
        clearTimeout(this.timer);
        this.currentImageDuration = null;
        this.imageRemainingDuration = null;

        // Stop any previous video progress animation
        this.stopVideoProgress();

        // Clear old image handlers
        if (imageMedia) {
            imageMedia.onload = null;
            imageMedia.onerror = null;
        }

        // Show video, hide image
        imageMedia.style.display = 'none';
        videoMedia.style.display = 'block';

        // Show loading immediately
        this.showLoading();

        // Reset & assign media
        videoMedia.onloadedmetadata = null;
        videoMedia.onended = null;
        videoMedia.onwaiting = null;
        videoMedia.onstalled = null;
        videoMedia.oncanplay = null;
        videoMedia.onplaying = null;
        videoMedia.onpause = null;
        videoMedia.onseeking = null;
        videoMedia.onseeked = null;
        videoMedia.onerror = null;

        videoMedia.src = story.url;
        videoMedia.muted = this.isMuted;

        this.updateMuteButton();
        this.updateMuteIndicator();

        videoMedia.onloadedmetadata = () => {
            this.resetProgressBar();
            this.startVideoProgress();

            // Respect paused state: if user paused, don't autoplay
            if (this.isPaused) {
                this.hideLoading();
                return;
            }

            videoMedia.play().catch(() => {
                // Autoplay might be blocked, mute and try again
                videoMedia.muted = true;
                this.isMuted = true;
                this.updateMuteButton();
                this.updateMuteIndicator();

                videoMedia.play().catch(() => {
                    // still blocked, keep loader off to avoid "stuck" look
                    this.hideLoading();
                });
            });
        };

        // Buffering / loading states
        videoMedia.onwaiting = () => {
            if (!this.isPaused) this.showLoading();
        };

        videoMedia.onstalled = () => {
            if (!this.isPaused) this.showLoading();
        };

        videoMedia.onseeking = () => {
            if (!this.isPaused) this.showLoading();
        };

        videoMedia.onseeked = () => {
            if (!this.isPaused) this.hideLoading();
        };

        videoMedia.oncanplay = () => {
            if (!this.isPaused) this.hideLoading();
        };

        videoMedia.onplaying = () => {
            if (!this.isPaused) this.hideLoading();
        };

        videoMedia.onpause = () => {
            // If user paused, never show loading
            this.hideLoading();
        };

        videoMedia.onerror = () => {
            this.hideLoading();
        };

        videoMedia.onended = () => {
            this.setCurrentSegmentProgress(100);
            this.nextStory();
        };

        // Start loading without waiting for full download (streaming)
        videoMedia.load();
    }


    /** Start the progress bar for video stories. */
    startVideoProgress() {
        const videoMedia = this.modal.querySelector('.story-video');

        this.stopVideoProgress();

        const animate = () => {
            // Modal can be closed while RAF is still running
            if (!this.modal || !document.body.contains(this.modal)) return;

            if (videoMedia && videoMedia.duration) {
                const progress = (videoMedia.currentTime / videoMedia.duration) * 100;
                this.setCurrentSegmentProgress(progress);
            }

            if (videoMedia && !videoMedia.ended) {
                this.videoProgressRafId = requestAnimationFrame(animate);
            }
        };

        this.videoProgressRafId = requestAnimationFrame(animate);
    }

    /** Stop video progress RAF loop (if any). */
    stopVideoProgress() {
        if (this.videoProgressRafId) {
            cancelAnimationFrame(this.videoProgressRafId);
            this.videoProgressRafId = null;
        }
    }

    /** Setup the image story. */
    setupImage(story) {
        const videoMedia = this.modal.querySelector('.story-video');
        const imageMedia = this.modal.querySelector('.story-image');

        // Stop any previous video progress animation
        this.stopVideoProgress();

        // Stop video completely
        if (videoMedia) {
            videoMedia.pause();
            videoMedia.removeAttribute('src');
            videoMedia.load();

            videoMedia.onloadedmetadata = null;
            videoMedia.onended = null;
            videoMedia.onwaiting = null;
            videoMedia.onstalled = null;
            videoMedia.oncanplay = null;
            videoMedia.onplaying = null;
            videoMedia.onpause = null;
            videoMedia.onseeking = null;
            videoMedia.onseeked = null;
            videoMedia.onerror = null;
        }

        // Hide mute indicator on images
        this.updateMuteIndicator(true);

        videoMedia.style.display = 'none';
        imageMedia.style.display = 'block';

        // Reset progress bar, but DO NOT start timer until image is loaded
        this.resetProgressBar();

        // Loading indicator
        this.showLoading();

        const duration = story.duration || 5000;

        // Set handlers BEFORE src (cache-safe)
        imageMedia.onload = () => {
            this.hideLoading();

            // If user paused before the image loaded, don't start the timer
            if (this.isPaused) {
                this.currentImageDuration = duration;
                this.imageRemainingDuration = duration;
                this.setCurrentSegmentProgress(0);
                return;
            }

            this.startTimer(duration);
        };

        imageMedia.onerror = () => {
            // Don't get stuck forever
            this.hideLoading();

            if (this.isPaused) {
                this.currentImageDuration = duration;
                this.imageRemainingDuration = duration;
                this.setCurrentSegmentProgress(0);
                return;
            }

            this.startTimer(duration);
        };

        imageMedia.src = story.url;
    }


    /** Start the timer for image stories. */
    startTimer(duration) {
        clearTimeout(this.timer);

        this.currentImageDuration = duration;
        this.imageRemainingDuration = duration;

        this.setCurrentSegmentProgress(0);
        this.setCurrentSegmentProgress(100, duration);

        this.timer = setTimeout(() => {
            if (!this.isPaused) this.nextStory();
        }, duration);
    }

    /** Pause image progress exactly where it is (no jumping to the end). */
    pauseImageProgress() {
        if (!this.currentImageDuration) return;

        clearTimeout(this.timer);

        const fill = this.getCurrentProgressFill();
        if (!fill) return;

        const segment = fill.closest('.story-progress-segment') || fill.parentElement;
        const computedWidth = parseFloat(getComputedStyle(fill).width) || 0;
        const containerWidth = (segment && segment.clientWidth) ? segment.clientWidth : 1;

        const progressPercent = Math.min(100, Math.max(0, (computedWidth / containerWidth) * 100));
        this.imageRemainingDuration = Math.max(0, this.currentImageDuration * (1 - progressPercent / 100));

        fill.style.transition = 'none';
        fill.style.width = `${progressPercent}%`;
    }

    /** Resume image progress from the same point. */
    resumeImageProgress() {
        if (!this.currentImageDuration) return;

        const remaining = this.imageRemainingDuration != null ? this.imageRemainingDuration : this.currentImageDuration;
        if (remaining <= 0) {
            this.nextStory();
            return;
        }

        clearTimeout(this.timer);

        // Continue from the current width to 100%
        this.setCurrentSegmentProgress(100, remaining);

        this.timer = setTimeout(() => {
            if (!this.isPaused) this.nextStory();
        }, remaining);
    }

    /** Go to the next story. */
    nextStory() {
        if (this.currentStoryIndex < this.stories.length - 1) {
            this.currentStoryIndex++;
            this.resetMedia();
            this.loadStory();
        } else {
            this.closeStory();
        }
    }

    /** Go to the previous story. */
    prevStory() {
        if (this.currentStoryIndex > 0) {
            this.currentStoryIndex--;
            this.resetMedia();
            this.loadStory();
        }
    }

    /** Reset the media elements in the modal. */
    resetMedia() {
        if (!this.modal) return;

        // Cancel timers / animations
        clearTimeout(this.timer);
        this.stopVideoProgress();

        // Hide overlays
        this.hideLoading();
        this.updateMuteIndicator(true);

        // Reset progress
        this.resetAllProgressSegments();

        // Reset image
        const imageMedia = this.modal.querySelector('.story-image');
        if (imageMedia) {
            imageMedia.onload = null;
            imageMedia.onerror = null;
        }

        // Reset video
        const videoMedia = this.modal.querySelector('.story-video');
        if (videoMedia) {
            videoMedia.pause();
            videoMedia.currentTime = 0;

            // Remove source to stop network downloading when switching stories
            videoMedia.removeAttribute('src');
            videoMedia.load();

            // Clear handlers
            videoMedia.onloadedmetadata = null;
            videoMedia.onended = null;
            videoMedia.onwaiting = null;
            videoMedia.onstalled = null;
            videoMedia.oncanplay = null;
            videoMedia.onplaying = null;
            videoMedia.onpause = null;
            videoMedia.onseeking = null;
            videoMedia.onseeked = null;
            videoMedia.onerror = null;
        }

        // Reset image timer state
        this.currentImageDuration = null;
        this.imageRemainingDuration = null;

        // Reset pause UI
        this.isPaused = false;
        this.updatePauseButton();
    }


    /** Reset the progress bar. */
    resetProgressBar() {
        // Reset ONLY current segment (keep previous segments filled)
        this.setCurrentSegmentProgress(0);
    }

    /** Close the story modal. */
    closeStory() {
        this.resetMedia();

        // Remove key handler added for this modal instance
        document.removeEventListener('keydown', this.boundHandleKeydown);

        if (this.modal) {
            this.modal.remove();
            this.modal = null;
        }

        document.body.style.overflow = '';
    }

    /** Toggle pause state */
    togglePause() {
        this.isPaused = !this.isPaused;

        const story = this.stories[this.currentStoryIndex];
        const videoMedia = this.modal.querySelector('.story-video');

        if (story.type === 'video') {
            if (this.isPaused) {
                videoMedia.pause();
                // Don't show loading while user paused
                this.hideLoading();
            } else {
                videoMedia.play().catch(() => {
                    // ignore
                });
            }
        }

        this.updatePauseButton();
        this.handleProgressBar();
    }


    /** Update pause button icon */
    updatePauseButton() {
        if (!this.modal) return;

        const pauseIcon = this.modal.querySelector('.pause-icon');
        const playIcon = this.modal.querySelector('.play-icon');

        if (!pauseIcon || !playIcon) return;

        if (this.isPaused) {
            pauseIcon.style.display = 'none';
            playIcon.style.display = 'block';
        } else {
            pauseIcon.style.display = 'block';
            playIcon.style.display = 'none';
        }
    }

    /** Update mute button icon */
    updateMuteButton() {
        if (!this.modal) return;

        const muteOff = this.modal.querySelector('.mute-icon-off');
        const muteOn = this.modal.querySelector('.mute-icon-on');

        if (!muteOff || !muteOn) return;

        if (this.isMuted) {
            muteOff.style.display = 'none';
            muteOn.style.display = 'block';
        } else {
            muteOff.style.display = 'block';
            muteOn.style.display = 'none';
        }
    }

    /** Show/Hide loading indicator */
    setLoadingVisible(visible) {
        if (!this.modal) return;
        const loading = this.modal.querySelector('.story-loading');
        if (!loading) return;
        loading.classList.toggle('is-visible', !!visible);
    }

    showLoading() {
        this.setLoadingVisible(true);
    }

    hideLoading() {
        this.setLoadingVisible(false);
    }

    /**
     * Update mute indicator in the middle of the media.
     * Shows ONLY when the current story is video AND muted.
     */
    updateMuteIndicator(forceHide = false) {
        if (!this.modal) return;

        const indicator = this.modal.querySelector('.story-mute-indicator');
        if (!indicator) return;

        const story = this.stories[this.currentStoryIndex];
        const shouldShow = !forceHide && story && story.type === 'video' && this.isMuted;

        indicator.classList.toggle('is-visible', shouldShow);
    }

    /**
     * Desktop story nav buttons:
     * - LEFT button = Next
     * - RIGHT button = Prev
     * Hide them if there is no next/prev.
     */
    updateDesktopStoryNavButtons() {
        if (!this.modal) return;

        const prevBtn = this.modal.querySelector('.story-prev-btn'); // RIGHT
        const nextBtn = this.modal.querySelector('.story-next-btn'); // LEFT

        if (prevBtn) {
            prevBtn.style.display = this.currentStoryIndex > 0 ? '' : 'none';
        }

        if (nextBtn) {
            nextBtn.style.display = this.currentStoryIndex < this.stories.length - 1 ? '' : 'none';
        }
    }

    /** Toggle mute */
    toggleMute() {
        if (!this.modal) return;

        const story = this.stories[this.currentStoryIndex];
        if (!story || story.type !== 'video') return;

        this.isMuted = !this.isMuted;

        const videoMedia = this.modal.querySelector('.story-video');
        if (videoMedia) {
            videoMedia.muted = this.isMuted;
        }

        this.updateMuteButton();
        this.updateMuteIndicator();
    }

    /** Handle like action */
    handleLike() {
        const story = this.stories[this.currentStoryIndex];

        if (!this.options.isLoggedIn) {
            window.location.href = this.options.loginUrl;
            return;
        }

        // Toggle like state locally for immediate feedback
        story.isLiked = !story.isLiked;
        story.likesCount = story.isLiked
            ? (story.likesCount || 0) + 1
            : Math.max(0, (story.likesCount || 1) - 1);

        this.updateLikeButton(story);

        // Call the like callback
        if (this.options.onLike) {
            this.options.onLike(story.id);
        }
    }

    /** Handle the progress bar based on the pause state. */
    handleProgressBar() {
        const story = this.stories[this.currentStoryIndex];

        // Video progress is tied to currentTime, so it naturally pauses/resumes.
        if (story.type === 'video') return;

        // Image progress needs manual pause/resume to avoid jumping.
        if (this.isPaused) {
            this.pauseImageProgress();
        } else {
            this.resumeImageProgress();
        }
    }

    /** Setup event listeners for the modal. */
    setupModalEventListeners() {
        const closeBtn = this.modal.querySelector('.story-close-btn');
        const pauseBtn = this.modal.querySelector('.story-pause-btn');
        const prevBtn = this.modal.querySelector('.story-prev-btn'); // RIGHT button
        const nextBtn = this.modal.querySelector('.story-next-btn'); // LEFT button
        const likeBtn = this.modal.querySelector('.story-like-btn');
        const muteBtn = this.modal.querySelector('.story-mute-btn');
        const backdrop = this.modal.querySelector('.story-modal-backdrop');
        const tapRight = this.modal.querySelector('.story-tap-right'); // right side: prev
        const tapLeft = this.modal.querySelector('.story-tap-left');   // left side: next
        const mediaContainer = this.modal.querySelector('.story-media-container');

        // Close button
        closeBtn.addEventListener('click', () => this.closeStory());
        backdrop.addEventListener('click', () => this.closeStory());

        // Pause button
        pauseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.togglePause();
        });

        // Navigation buttons (desktop)
        prevBtn.addEventListener('click', () => this.prevStory());
        nextBtn.addEventListener('click', () => this.nextStory());

        // Like button
        likeBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.handleLike();
        });

        // Mute button
        muteBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            this.toggleMute();
        });

        // Tap zones for mobile navigation (RTL: left = next, right = prev)
        tapRight.addEventListener('click', (e) => {
            e.stopPropagation();
            this.prevStory();
        });

        tapLeft.addEventListener('click', (e) => {
            e.stopPropagation();
            this.nextStory();
        });

        // Single tap on video (desktop or center area on mobile) toggles mute
        mediaContainer.addEventListener('click', () => {
            if (this.ignoreNextClick) {
                this.ignoreNextClick = false;
                return;
            }

            const story = this.stories[this.currentStoryIndex];
            if (!story || story.type !== 'video') return;

            this.toggleMute();
        });

        // Hold to pause (touch)
        mediaContainer.addEventListener('touchstart', (e) => {
            this.touchStartX = e.changedTouches[0].clientX;

            // Start hold detection
            this.holdTimeout = setTimeout(() => {
                this.isHolding = true;
                if (!this.isPaused) {
                    this.togglePause();
                }
            }, 200);
        });

        mediaContainer.addEventListener('touchend', (e) => {
            clearTimeout(this.holdTimeout);

            if (this.isHolding) {
                // Was holding, release to resume
                this.isHolding = false;

                // Prevent the "click" after touchend from toggling mute
                this.ignoreNextClick = true;

                if (this.isPaused) {
                    this.togglePause();
                }
                return;
            }

            // Handle swipe
            this.touchEndX = e.changedTouches[0].clientX;
            this.handleSwipe();
        });

        mediaContainer.addEventListener('touchmove', () => {
            // Cancel hold if moving
            clearTimeout(this.holdTimeout);
        });

        // Keyboard navigation (remove on close)
        document.addEventListener('keydown', this.boundHandleKeydown);
    }


    /** Handle keyboard navigation */
    handleKeydown(e) {
        if (!this.modal || !document.body.contains(this.modal)) return;

        switch (e.key) {
            case 'ArrowLeft':
                this.nextStory();
                break;
            case 'ArrowRight':
                this.prevStory();
                break;
            case ' ':
                e.preventDefault();
                this.togglePause();
                break;
            case 'Escape':
                this.closeStory();
                break;
            case 'm':
            case 'M':
                this.toggleMute();
                break;
        }
    }

    /** Handle swipe gestures to navigate stories. */
    handleSwipe() {
        const swipeThreshold = 50;
        const swipeDistance = this.touchEndX - this.touchStartX;

        if (swipeDistance > swipeThreshold) {
            this.ignoreNextClick = true;
            this.prevStory();
        } else if (swipeDistance < -swipeThreshold) {
            this.ignoreNextClick = true;
            this.nextStory();
        } else {
            this.ignoreNextClick = false;
        }
    }

}
