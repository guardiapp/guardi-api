<template>
    <MainLayout>


        <div class="container px-6 mx-auto grid">

            <BreadcrumbTemplate
            :homeLink="{ url: '/', label: 'Inicio' }"
            :crumbs="[
                { label: 'Residencias', isCurrent: true }
            ]"
            />


            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Residencias
                </h2>
                <Link
                    v-if="user.type == 'Admin'"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :href="`/residences/create`"
                    >
                    Nuevo
                    <span class="ml-2" aria-hidden="true">+</span>
                </Link>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                current-url="/residences"
                @update:filters="syncFilters"
            />
            <div v-if="residences">
                <TableTemplate
                    :columns="user.type === 'Admin' ? ['Nombre', 'Direccion', 'Administrador', 'Acciones'] : ['Nombre', 'Direccion', 'Acciones']"
                    :data="transformedResidences"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
                    :total="total"
                    :current-page="currentPage"
                >
                    <!-- Custom column for manager -->
                    <template #column-name="{ value }">
                        <button
                            v-if="value && value.id"
                            @click="showResidence(value.id)"
                            class="font-semibold"
                            :class="themeStore.dark ? 'text-gray-300' : 'text-gray-700'"
                        >
                            {{ value.text }}
                        </button>
                        <span v-else>{{ value?.text || 'N/A' }}</span>
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
                            <button
                                @click="showResidence(row.actions.id)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
                                aria-label="Show"
                            >
                                <EyeIcon class="size-6" />
                            </button>
                            <button
                                v-if="user.type == 'Admin'"
                                @click = editResidence(row.actions.id)
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
                            </button>
                            <button
                                v-if="user.type == 'Admin'"
                                @click="manageManagers(row.actions.id)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="themeStore.dark
                                 ? 'text-gray-400'
                                 : 'text-purple-600'"
                                 aria-label="Gestionar administradores"
                                 title="Gestionar administradores">
                                 <ArchiveBoxIcon class="size-6"/>
                            </button>
                            <button
                                v-if="user.type == 'Admin'"
                                @click="handleDeleteResidence(row.actions.id)"
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
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import FilterTemplate from "@/Components/FilterTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
//import { useGlobalFunctions } from "@/composables/useGlobalFunctions";
import { EyeIcon, ArchiveBoxIcon, ArchiveBoxArrowDownIcon } from '@heroicons/vue/24/solid';
import Swal from "sweetalert2";
import { notify } from "notiwind";

document.title="Gestión de Residencias";

//const { deleteResidence } = useGlobalFunctions();

const themeStore = useThemeStore();
const residenceStore = useResidenceStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const residences = ref(props.residences.data);
const links = ref(props.residences.links);
const from = ref(props.residences.from);
const to = ref(props.residences.to);
const total = ref(props.residences.total);
const currentPage = ref(props.residences.current_page);
const rowsPerPage = ref(props.residences.per_page ?? 5);

const transformedResidences = computed(() => {
    if (user.type === "Admin") {
        return residences.value.map((residence) => ({
            name: { text: residence.name, id: residence.id },
            address: residence.address,
            manager: residence.manager[0]?.name || "N/A",
            actions: { id: residence.id },
        }));
    }
    //Para Manager:
    return residences.value.map((residence) => ({
        name: { text: residence.name, id: residence.id },
        address: residence.address,
        actions: { id: residence.id },
    }));
});


// const handleDeleteResidence = (id) => {
//     deleteResidence(id, () => {
//         console.log("Se recargaron los datos correctamente.");
//     });
// };

const handleDeleteResidence = (id) => {
    Swal.fire({
        customClass: {
            popup: themeStore.dark
                ? "bg-gray-900 text-white"
                : "bg-white text-gray-900",
        },
        text: "¿Desea eliminar esta residencia?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7e3af2",
        cancelButtonColor: "#4c4f52",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('residences.destroy', id), {
                onSuccess: (response) => {
                    residences.value = response.props.residences.data;
                    currentPage.value = response.props.residences.current_page
                    links.value = response.props.residences.links;
                    from.value = response.props.residences.from;
                    to.value = response.props.residences.to;
                    total.value = response.props.residences.total;
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "La residencia ha sido eliminada",
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

// Variables para filtros reactivos
const filters = ref({ ...props.filters });

const filtersEnabled = {
    name: true,
    address: true,
    manager: user.type == 'Admin' || false,
};

// Función para obtener los datos filtrados
const fetchFilteredResidences = () => {
    router.get(
        route("residences.index"),
        {
            ...filters.value, // Enviar filtros como parámetros directos
            preserveScroll: true,
            preserveState: true,
        },
        {
            onSuccess: (page) => {
                residences.value = page.props.residences.data;
                links.value = page.props.residences.links;
                from.value = page.props.residences.from;
                to.value = page.props.residences.to;
                total.value = page.props.residences.total;
                currentPage.value = page.props.residences.current_page;
            },
            onError: (error) => {
                console.error("Error al obtener residencias filtradas:", error);
            },
        }
    );
};

// Función para sincronizar filtros al cambiar inputs
const syncFilters = (updatedFilters) => {
    filters.value = updatedFilters; // Actualiza todos los filtros reactivamente
    fetchFilteredResidences();
};

const setSelectedResidence = (id) => {
    const residence = residences.value.find((r) => r.id === id);

    if (residence) {
        residenceStore.setSelectedResidence(residence);
    } else {
        console.error("Residencia no encontrada");
    }
};

const showResidence = (id) => {
    setSelectedResidence(id)
    if (!residenceStore.selectedResidence) {
        console.error("No hay residencia seleccionada");
        return;
    }
    const residenceid = residenceStore.selectedResidence.id;
    router.visit(`/residences/${residenceid}`);
};

/**
 *
 * @param {Number} id
 */
const manageManagers = (id) => {
    const endpoint = `/residences/${id}/manageManagers`;
    router.visit(endpoint);
}

const editResidence = (id) => {
    setSelectedResidence(id)
    if (!residenceStore.selectedResidence) {
        console.error("No hay residencia seleccionada");
        return;
    }
    const residenceid = residenceStore.selectedResidence.id;
    router.visit(`/residences/edit/${residenceid}`);
};


</script>
