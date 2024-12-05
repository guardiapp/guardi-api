<template>
    <body :class="{ 'theme-dark': themeStore.dark }">
        <div class="flex items-center min-h-screen p-6"
        :class="themeStore.dark ? 'bg-gray-900' : 'bg-gray-50'"
        >
            <div
                class="flex-1 h-full max-w-4xl mx-auto overflow-hidden rounded-lg shadow-xl"
                :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
            >
                <div class="flex flex-col overflow-y-auto md:flex-row">
                    <div class="h-32 md:h-auto md:w-1/2">
                        <img
                            aria-hidden="true"
                            class="object-cover w-full h-full"
                            :class="themeStore.dark ? 'hidden' : 'block'"
                            src="assets/img/login-office.jpeg"
                            alt="Office"
                        />
                        <img
                            aria-hidden="true"
                            class="object-cover w-full h-full"
                            :class="themeStore.dark ? 'block' : 'hidden'"
                            src="assets/img/login-office-dark.jpeg"
                            alt="Office"
                        />
                    </div>
                    <slot />
                </div>
            </div>
        </div>
    </body>
</template>
<script setup>
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();
</script>
