import './bootstrap';
//import Alpine from 'alpinejs';
import { createIcons, icons } from 'lucide';

//window.Alpine = Alpine;

window.appLayout = () => ({
    mobileSidebarOpen: false,
    sidebarCollapsed: false,
    darkMode: false,
    isDesktop: false,
    resizeHandler: null,
    mainPadding: 'lg:px-8 xl:px-10',
    contentContainer: 'max-w-full gap-6 sm:gap-7',
    init() {
        this.syncTheme();
        this.updateBreakpoint();
        this.resizeHandler = () => this.updateBreakpoint();
        window.addEventListener('resize', this.resizeHandler);
        this.mobileSidebarOpen = this.isDesktop;
        this.updateLayoutMetrics();
        createIcons({ icons });
    },
    destroy() {
        if (this.resizeHandler) {
            window.removeEventListener('resize', this.resizeHandler);
        }
    },
    updateBreakpoint() {
        const previous = this.isDesktop;
        this.isDesktop = window.innerWidth >= 1024;

        if (this.isDesktop) {
            this.mobileSidebarOpen = true;
        } else if (previous && !this.isDesktop) {
            this.mobileSidebarOpen = false;
            this.sidebarCollapsed = false;
        }

        this.updateLayoutMetrics();
    },
    syncTheme() {
        const stored = localStorage.getItem('af-theme');
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;

        this.darkMode = stored ? stored === 'dark' : prefersDark;
        document.documentElement.classList.toggle('dark', this.darkMode);
    },
    toggleMobileSidebar() {
        if (this.isDesktop) {
            this.sidebarCollapsed = !this.sidebarCollapsed;
            this.updateLayoutMetrics();
            return;
        }

        this.mobileSidebarOpen = !this.mobileSidebarOpen;
        this.updateLayoutMetrics();
    },
    closeMobileSidebar() {
        if (!this.isDesktop) {
            this.mobileSidebarOpen = false;
        }

        this.updateLayoutMetrics();
    },
    updateLayoutMetrics() {
        if (!this.isDesktop) {
            this.mainPadding = 'lg:px-8 xl:px-10';
            this.contentContainer = 'max-w-full gap-6 sm:gap-7';
            return;
        }

        if (this.sidebarCollapsed) {
            this.mainPadding = 'lg:px-5 xl:px-6';
            this.contentContainer = 'max-w-[1560px] gap-6 xl:gap-7';
        } else {
            this.mainPadding = 'lg:px-12 xl:px-16';
            this.contentContainer = 'max-w-[1100px] gap-5 xl:gap-6';
        }
    },
    toggleDark() {
        this.darkMode = !this.darkMode;
        document.documentElement.classList.toggle('dark', this.darkMode);
        localStorage.setItem('af-theme', this.darkMode ? 'dark' : 'light');
        createIcons({ icons });
    },
});

//Alpine.start();

const initIcons = () => createIcons({ icons });

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initIcons);
} else {
    initIcons();
}

document.addEventListener('livewire:navigated', () => {
    initIcons();
});
