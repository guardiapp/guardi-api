<template>
    <body :class="{ 'theme-dark': themeStore.dark }">
        <div
            class="flex h-screen"
            :class="{ 'overflow-hidden': isSideMenuOpen, 'bg-gray-50': !themeStore.dark, 'bg-gray-900 ': themeStore.dark }"
        >
            <SidebarTemplate />
            <div class="flex flex-col flex-1 w-full">
                <HeaderTemplate />
                <main class="h-full overflow-y-auto">
                    <slot />
                </main>
            </div>
        </div>
    </body>
</template>

<script setup>
import SidebarTemplate from "@/Components/SidebarTemplate.vue";
import HeaderTemplate from "@/Components/HeaderTemplate.vue";

import { useThemeStore } from "@/stores/themeStore";

// Obtener el store de tema
const themeStore = useThemeStore();

// Sincronizar el estado del tema con localStorage al cargar
themeStore.syncThemeWithLocalStorage();
</script>
