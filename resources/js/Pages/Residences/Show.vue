<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { label: residence.name, isCurrent: true },
                ]"
            />
            <div class="flex items-center justify-between">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    {{ residence.name }}
                </h2>
            </div>
            <h3 :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'">
                {{ residence.address }}
            </h3>
            <div class="my-6">
                <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-3 my-5">
                    <div v-for="(item, index) in cardItems" :key="index">
                        <Link
                        v-if="item.value > 0"
                        :href="item.link">
                            <CardTemplate
                                :title="item.title"
                                :value="item.value"
                                :iconBgClass="item.iconBgClass"
                            >
                                <template #icon>
                                    <component
                                        :is="item.iconKey"
                                        class="w-5 h-5 size-6"
                                    />
                                </template>
                            </CardTemplate>
                        </Link>
                        <div v-else>
                            <CardTemplate
                                :title="item.title"
                                :value="item.value"
                                :iconBgClass="item.iconBgClass"
                            >
                                <template #icon>
                                    <component
                                        :is="item.iconKey"
                                        class="w-5 h-5 size-6"
                                    />
                                </template>
                            </CardTemplate>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import UsersIcon from "@/Components/Icons/UsersIcon.vue";
import MoneyIcon from "@/Components/Icons/MoneyIcon.vue";
import ShoppingCartIcon from "@/Components/Icons/ShoppingCartIcon.vue";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import CardTemplate from "@/Components/CardTemplate.vue";

import {
    ComputerDesktopIcon,
    UserCircleIcon,
    BuildingOfficeIcon,
    BuildingOffice2Icon,
    ShieldExclamationIcon,
    HomeIcon,
    UserGroupIcon,
    BookOpenIcon
} from '@heroicons/vue/24/solid';

const iconMap = {
    ComputerDesktopIcon,
    UserCircleIcon,
    BuildingOfficeIcon,
    BuildingOffice2Icon,
    ShieldExclamationIcon,
    HomeIcon,
    UserGroupIcon,
    BookOpenIcon
};


import { usePage, Link } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();
import { reactive, computed } from "vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import { MapIcon } from "@heroicons/vue/24/outline";

const { props } = usePage();
const residence = props.residence;
const user = usePage().props.auth.user;

// Lista de íconos (relaciona las claves con los componentes)
const icons = {
    "users-icon": UsersIcon,
    "money-icon": MoneyIcon,
    "shopping-cart-icon": ShoppingCartIcon,
    "message-icon": MessageIcon,
};

const getIconComponent = (iconKey) => {
    return icons[iconKey];
};

// Datos reactivos
const cardItems = reactive([
    {
        title: "Edificios",
        link: `/residences/${residence.id}/buildings`,
        value: residence.buildings.length,
        iconKey: iconMap.BuildingOffice2Icon,
        iconBgClass: computed(() =>
            themeStore.dark
                ? "text-purple-100 bg-purple-500"
                : "text-purple-500 bg-purple-100"
        ),
    },
    {
        title: "Apartamentos",
        link: `/residences/${residence.id}/apartments`,
        value: residence.apartments.length,
        iconKey: iconMap.BuildingOfficeIcon,
        iconBgClass: computed(() =>
            themeStore.dark
                ? "text-orange-100 bg-orange-500"
                : "text-orange-500 bg-orange-100"
        ),
    },
    {
        title: "Vigilantes",
        link: `/residences/${residence.id}/guards`,
        value: residence.guards.length,
        iconKey: iconMap.ShieldExclamationIcon,
        iconBgClass: computed(() =>
            themeStore.dark
                ? "text-blue-100 bg-blue-500"
                : "text-blue-500 bg-blue-100"
        ),
    },
    {
        title: "Vistantes",
        link: `/residences/${residence.id}/visitors`,
        value: residence.apartments.reduce(
            (total, apartment) => total + (apartment.visitors?.length || 0),
            0
        ),
        iconKey: iconMap.UserGroupIcon,
        iconBgClass: computed(() =>
            themeStore.dark
                ? "text-red-100 bg-red-500"
                : "text-red-500 bg-red-100"
        ),
    },
    {
        title: "Visitas",
        link: `/residences/${residence.id}/visits`,
        value: residence.apartments.reduce(
            (total, apartment) => total + (apartment.visits?.length || 0),
            0
        ),
        iconKey: iconMap.BookOpenIcon,
        iconBgClass: computed(() =>
            themeStore.dark
                ? "text-teal-100 bg-teal-500"
                : "text-teal-500 bg-teal-100"
        ),
    },
]);
</script>
