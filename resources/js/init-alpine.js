function data() {
    function getThemeFromLocalStorage() {
        try {
            if (window.localStorage.getItem("dark")) {
                return JSON.parse(window.localStorage.getItem("dark"));
            }
            return (
                !!window.matchMedia &&
                window.matchMedia("(prefers-color-scheme: dark)").matches
            );
        } catch (error) {
            console.warn("Error accessing localStorage:", error);
            return false;
        }
    }

    function setThemeToLocalStorage(value) {
        try {
            window.localStorage.setItem("dark", value);
        } catch (error) {
            console.warn("Error saving to localStorage:", error);
        }
    }

    return {
        dark: getThemeFromLocalStorage(),
        toggleTheme() {
            this.dark = !this.dark;
            console.log("Dark mode toggled:", this.dark);
            setThemeToLocalStorage(this.dark);
        },
        isSideMenuOpen: false,
        toggleSideMenu() {
            this.isSideMenuOpen = !this.isSideMenuOpen;
            console.log("Side menu toggled:", this.isSideMenuOpen);
        },
        closeSideMenu() {
            this.isSideMenuOpen = false;
        },
        isNotificationsMenuOpen: false,
        toggleNotificationsMenu() {
            this.isNotificationsMenuOpen = !this.isNotificationsMenuOpen;
        },
        closeNotificationsMenu() {
            this.isNotificationsMenuOpen = false;
        },
        isProfileMenuOpen: false,
        toggleProfileMenu() {
            this.isProfileMenuOpen = !this.isProfileMenuOpen;
        },
        closeProfileMenu() {
            this.isProfileMenuOpen = false;
        },
        isPagesMenuOpen: false,
        togglePagesMenu() {
            this.isPagesMenuOpen = !this.isPagesMenuOpen;
        },
        // Modal
        isModalOpen: false,
        trapCleanup: null,
        openModal() {
            this.isModalOpen = true;
            const modalElement = document.querySelector("#modal");
            if (modalElement) {
                try {
                    this.trapCleanup = focusTrap(modalElement);
                } catch (error) {
                    console.error("Error initializing focusTrap:", error);
                }
            } else {
                console.warn("Modal element (#modal) not found.");
            }
        },
        closeModal() {
            this.isModalOpen = false;
            if (this.trapCleanup) {
                try {
                    this.trapCleanup();
                } catch (error) {
                    console.error("Error cleaning up focusTrap:", error);
                }
            }
        },
    };
}
