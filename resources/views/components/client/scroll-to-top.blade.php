<div x-data="{
        visible: false,
        hasTour: false,
        checkScroll() {
            this.visible = window.scrollY > 300;
        }
     }"
     x-init="
        checkScroll();
        window.addEventListener('scroll', () => checkScroll(), { passive: true });
        hasTour = !!document.getElementById('sdfr-page-tour-trigger');
     ">

    <button type="button"
            x-show="visible"
            x-cloak
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            :class="hasTour
                ? 'bottom-[calc(9.5rem+env(safe-area-inset-bottom))] md:bottom-20'
                : 'bottom-[calc(6rem+env(safe-area-inset-bottom))] md:bottom-6'"
            title="بازگشت به بالای صفحه"
            aria-label="بازگشت به بالای صفحه"
            class="fixed left-3 sm:left-5 z-40 w-11 h-11 rounded-full bg-secondary border border-border shadow-lg
                   flex items-center justify-center text-muted hover:text-primary hover:border-primary/40
                   transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg"  class="w-6 h-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 15L12 9L18 15" />
        </svg>
    </button>
</div>
