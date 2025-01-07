<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Administradores
                </h2>
                <Link
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :href="`/managers/create`"
                    >
                    Nuevo
                    <span class="ml-2" aria-hidden="true">+</span>
                </Link>
            </div>
            <div v-if="managers">
                <TableTemplate
                    :columns="['','Nombre', 'Correo Electrónico', 'Acciones']"
                    :data="transformedManagers"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :total="total"
                    :current-page="currentPage"
                >
                    <!-- Custom column for avatar -->
                    <template #column-avatar="{ value }">
                        <div class="w-10 h-10 rounded-full overflow-hidden">
                            <img
                                v-if="value"
                                :src="`/storage/${value}`"
                                alt="Avatar"
                                class="object-cover w-full h-full"
                            />
                            <div
                                v-else
                                class="flex items-center justify-center w-full h-full text-xs text-gray-500 bg-gray-300"
                            >
                                N/A
                            </div>
                        </div>
                    </template>

                    <!-- Custom column for manager -->
                    <template #column-manager="{ value }">
                        <span
                            v-if="value"
                            class="font-semibold"
                            :class="
                                themeStore.dark ? 'text-gray-300' : 'text-gray-700'
                            "
                        >
                            {{ value }}
                        </span>
                    </template>

                    <!-- Custom column for actions -->
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
                            <Link
                                :href="`/managers/${row.actions.id}`"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
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
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
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
        </div>
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Swal from "sweetalert2";
import { notify } from "notiwind";

document.title="Gestión de administradores";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const managers = ref(props.data);
const links = ref(props.links);
const total = ref(props.total);
const currentPage = ref(props.current_page);
const rowsPerPage = ref(props.per_page ?? 5);

const transformedManagers = computed(() => {
    return managers.value.map((manager) => ({
        avatar: manager.avatar,
        name: manager.name,
        email: manager.email,
        actions: { id: manager.id },
    }));
});

const deleteManager = (id) => {
    Swal.fire({
        customClass: {
            popup: themeStore.dark
                ? "bg-gray-900 text-white"
                : "bg-white text-gray-900",
        },
        text: "¿Desea eliminar este administrador?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7e3af2",
        cancelButtonColor: "#4c4f52",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('managers.destroy', id), {
                onSuccess: (response) => {
                    console.log(response.props);
                    managers.value = response.props.data;
                    currentPage.value = response.currentPage
                    links.value = response.props.links;
                    total.value = response.props.total;
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "El administrador ha sido eliminado",
                        },
                        4000
                    );
                },
                onError: (error) => {
                    console.error(
                        "Error al eliminar:",
                        error.response || error
                    );
                }
            })
        }
    });
};
</script>
