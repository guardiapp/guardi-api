<template>
    <div>
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden h-screen w-64 overflow-y-auto md:block flex-shrink-0"
            :class="{
                'bg-white': !themeStore.dark,
                'bg-gray-800 ': themeStore.dark,
            }"
        >
            <div
                class="py-4"
                :class="{
                    'text-gray-500': !themeStore.dark,
                    'text-gray-400': themeStore.dark,
                }"
            >
                <Link
                    class="ml-6 text-lg font-bold"
                    href="/dashboard"
                    :class="{
                        'text-gray-800': !themeStore.dark,
                        'text-gray-200': themeStore.dark,
                    }"
                >
                    Dashboard
                </Link>
                <ul class="mt-6">
                    <li
                        v-for="(menuItem, index) in menuItems"
                        :key="index"
                    >
                    <div
                        class="relative px-6 py-3"
                        v-if="menuItem.roles.includes(user.type)" >
                            <span
                                class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                aria-hidden="true"
                                v-if="isActive(menuItem.link)"
                            ></span>
                            <Link
                                :href="menuItem.link"
                                class="hover:text-gray-800 dark:hover:text-gray-200 text-sm inline-flex items-center w-full transition-colors duration-150"
                                :class="{
                                    'text-gray-800 dark:text-gray-100 font-bond': isActive(menuItem.link),
                                    'text-gray-500 dark:text-gray-400 font-semibold ': !isActive(menuItem.link)
                                }"
                            >
                                <component
                                    :is="iconMap[menuItem.icon]"
                                    class="size-6"
                                />
                                <span class="ml-4">{{ menuItem.label }}</span>
                            </Link>
                        </div>
                    </li>
                </ul>
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div
            v-if="themeStore.isSideMenuOpen"
            transition:enter="transition ease-in-out duration-150"
            transition:enter-start="opacity-0"
            transition:enter-end="opacity-100"
            transition:leave="transition ease-in-out duration-150"
            transition:leave-start="opacity-100"
            transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        ></div>
        <aside
            class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto md:hidden"
            :class="{
                'bg-white': !themeStore.dark,
                'bg-gray-800': themeStore.dark,
            }"
            v-show="themeStore.isSideMenuOpen"
            transition:enter="transition ease-in-out duration-150"
            transition:enter-start="opacity-0 transform -translate-x-20"
            transition:enter-end="opacity-100"
            transition:leave="transition ease-in-out duration-150"
            transition:leave-start="opacity-100"
            transition:leave-end="opacity-0 transform -translate-x-20"
            @click.away="themeStore.closeSideMenu"
            @keydown.escape="themeStore.closeSideMenu"
        >
            <div
                class="py-4"
                :class="{
                    'text-gray-500': !themeStore.dark,
                    'text-gray-400': themeStore.dark,
                }"
            >
                <a
                    class="ml-6 text-lg font-bold"
                    :class="{
                        'text-gray-800': !themeStore.dark,
                        'text-gray-200': themeStore.dark,
                    }"
                    href="#"
                >
                    Visitas
                </a>
                <ul class="mt-6">
                    <li class="relative px-6 py-3"
                        v-for="(menuItem, index) in menuItems"
                        :key="index"
                    >
                        <span
                            v-if="isActive(menuItem.link)"
                            class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"
                        ></span>
                        <Link
                            :href="menuItem.link"
                            class="hover:text-gray-800 dark:hover:text-gray-200 text-sm inline-flex items-center w-full transition-colors duration-150"
                            :class="{
                                'text-gray-800 dark:text-gray-100 font-bond': isActive(menuItem.link),
                                'text-gray-500 dark:text-gray-400 font-semibold ': !isActive(menuItem.link)
                            }"
                        >
                            <component
                                :is="iconMap[menuItem.icon]"
                                class="size-6"
                            />
                            <span class="ml-4">{{ menuItem.label }}</span>
                        </Link>
                    </li>
                </ul>
            </div>
        </aside>
    </div>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";

import {
    ComputerDesktopIcon,
    UserCircleIcon,
    BuildingOffice2Icon,
    BuildingOfficeIcon,
    ShieldExclamationIcon,
    HomeIcon,
    UserGroupIcon,
    BookOpenIcon
} from '@heroicons/vue/24/outline';
const currentPath = usePage().url;

const user = usePage().props.auth.user;

const themeStore = useThemeStore();

const isActive = (menuLink) => {
    return currentPath.startsWith(menuLink);
};

const iconMap = {
    ComputerDesktopIcon,
    UserCircleIcon,
    BuildingOffice2Icon,
    BuildingOfficeIcon,
    ShieldExclamationIcon,
    HomeIcon,
    UserGroupIcon,
    BookOpenIcon
};

const menuItems = [
    {
        label: "Inicio",
        link: "/dashboard",
        icon: "ComputerDesktopIcon",
        roles: ["Admin"],
    },
    { label: "Administradores", link: "/managers", icon: "UserCircleIcon", roles: ["Admin"] },
    { label: "Residencias", link: "/residences", icon:"BuildingOffice2Icon", roles: ["Admin"] },
    { label: "Edificios", link: "/buildings", icon:"BuildingOfficeIcon", roles: ["Admin"] },
    { label: "Apartamentos", link: "/apartments", icon:"HomeIcon", roles: ["Admin", "Manager"] },
    { label: "Vigilantes", link: "/guards", icon:"ShieldExclamationIcon", roles: ["Admin", "Manager"] },
    { label: "Visitantes", link: "/visitors", icon:"UserGroupIcon",roles: ["Admin", "Manager"] },
    { label: "Visitas", link: "/visits", icon:"BookOpenIcon", roles: ["Admin", "Manager"] },
];
</script>
