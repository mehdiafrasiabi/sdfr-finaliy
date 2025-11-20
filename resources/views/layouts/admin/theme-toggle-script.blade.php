<script>
    (() => {
        const root = document.documentElement;
        const body = document.body;
        const toggles = document.querySelectorAll('#light-dark-toggle');

        const setIcon = (mode) => {
            toggles.forEach((toggle) => {
                const icon = toggle.querySelector('i');
                if (icon) {
                    icon.textContent = mode === 'dark' ? 'dark_mode' : 'light_mode';
                }
            });
        };

        const applyTheme = (mode) => {
            const isDark = mode === 'dark';
            body.classList.toggle('dark', isDark);
            root.classList.toggle('dark', isDark);
            localStorage.setItem('admin-theme', mode);
            setIcon(mode);
        };

        const savedTheme = localStorage.getItem('admin-theme');
        const initialTheme = savedTheme ?? (body.classList.contains('dark') ? 'dark' : 'light');
        applyTheme(initialTheme);

        toggles.forEach((toggle) => {
            toggle.addEventListener('click', () => {
                const nextTheme = body.classList.contains('dark') ? 'light' : 'dark';
                applyTheme(nextTheme);
            });
        });
    })();
</script>
