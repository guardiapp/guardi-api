import { defineStore } from "pinia";

export const useThemeStore = defineStore("themeStore", {
    state: () => ({
        dark: false,
        isSideMenuOpen: false,
        isNotificationsMenuOpen: false,
        isProfileMenuOpen: false,
        isPagesMenuOpen: false,
    }),
    actions: {
        syncThemeWithLocalStorage() {
            const storedTheme = localStorage.getItem("dark");
            if (storedTheme !== null) {
                this.dark = JSON.parse(storedTheme);
            } else {
                // Detectar el tema preferido del navegador
                this.dark = window.matchMedia("(prefers-color-scheme: dark)").matches;
                localStorage.setItem("dark", JSON.stringify(this.dark));
            }
        },
        toggleTheme() {
            this.dark = !this.dark;
            localStorage.setItem("dark", JSON.stringify(this.dark));
        },
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen;
        },
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen;
        },
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen;
        },
        togglePagesMenu() {
            this.isPagesMenuOpen = !this.isPagesMenuOpen;
        },
        closeSideMenu() {
            this.isSideMenuOpen = false;
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false;
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false;
        },
        closePagesMenu() {
            this.isPagesMenuOpen = false;
        },
    },
});
