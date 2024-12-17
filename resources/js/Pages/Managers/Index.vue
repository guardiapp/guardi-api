<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Administradores
            </h2>
            <TableTemplate
                :columns="['Nombre', 'Correo Electrónico', 'Acciones']"
                :data="transformedManagers"
                :rows-per-page="5"
            >
                <template #column-manager="{ value }">
                    <span
                        class="font-semibold text-gray-700 dark:text-gray-300"
                    >
                        {{ value }}
                    </span>
                </template>
                <template #column-actions="{ row }">
                    <div class="flex items-center space-x-4 text-sm">
                        <Link
                            :href="`/managers/${row.actions.id}`"
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            :class="themeStore.dark ? 'text-gray-400' : 'text-purple-600'"
                            aria-label="Edit"
                        >
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z"
                                ></path>
                            </svg>
                        </Link>
                        <button
                            @click="deleteManager(row.actions.id)"
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            :class="themeStore.dark ? 'text-gray-400' : 'text-purple-600'"
                            aria-label="Delete"
                        >
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    fill-rule="evenodd"
                                    d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                                    clip-rule="evenodd"
                                ></path>
                            </svg>
                        </button>
                    </div>
                </template>
            </TableTemplate>
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import { Link } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

const { props } = usePage();
const { managers } = props;

// Transform data for the table
const transformedManagers = managers.map((manager) => ({
    //id: manager.id,
    name: manager.name,
    email: manager.email,
    actions: { id: manager.id }, // Store reference for actions
}));

const deleteManager = (id) => {
    if (confirm("Are you sure you want to delete this manager?")) {
        axios.delete(`/managers/${id}`).then(() => {
            window.location.reload();
        });
    }
};
</script>
