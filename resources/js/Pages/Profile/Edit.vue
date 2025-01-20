<script setup>
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import { usePage } from "@inertiajs/vue3";

import MainLayout from "@/Layouts/MainLayout.vue";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

const user = usePage().props.auth.user;

document.title="Perfil del usuario";

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});
</script>

<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="user.type !== 'Admin'"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { label: 'Perfil', isCurrent: true }
                ]"
            />
            <div class="py-12">
                <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                    >
                        <UpdateProfileInformationForm
                            :must-verify-email="mustVerifyEmail"
                            :status="status"
                            class="max-w-xl"
                        />
                    </div>
                    <div
                        class="bg-white p-4 shadow sm:rounded-lg sm:p-8 dark:bg-gray-800"
                    >
                        <UpdatePasswordForm class="max-w-xl" />
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>
