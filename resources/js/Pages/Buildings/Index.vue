<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residenceId}`, label: residenceName },
                    { label: 'Edificios', isCurrent: true }
                ]"
            />
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Edificios
                </h2>
                <Link
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :href="`/buildings/create`"
                    >
                    Nuevo
                    <span class="ml-2" aria-hidden="true">+</span>
                </Link>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                :current-url="residenceId ? `/residences/${residenceId}/buildings` : '/buildings'"
                @update:filters="syncFilters"
            />
            <div v-if="buildings">
                <TableTemplate
                    :columns="user.type == 'Admin' ? ['Administrador', 'Nombre', 'Pisos', 'Estatus', 'Acciones'] : ['Nombre', 'Pisos', 'Estatus', 'Acciones']"
                    :data="transformedBuildings"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
                    :total="total"
                    :current-page="currentPage"
                >
                    <!-- Custom column for avatar -->
                    <template #column-active="{ value }">
                        <span v-if="value === 1">Activo</span>
                        <span v-else>Inactivo</span>
                    </template>

                    <!-- Custom column for actions -->
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-1 text-sm">
                            <button
                                @click="handleShowDetails(row.actions.id)"
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
                            <Link
                                :href="`/buildings/${row.actions.id}`"
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
                                @click="deleteBuilding(row.actions.id)"
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

        <!-- Modal Details-->
        <ShowDetails
            v-if="isModalOpen && selectedRow"
            :is-modal-open="isModalOpen"
            :selected-row="selectedRow || {}"
            :fields-to-show="{
                name: 'Nombre',
                residence: 'Residencia',
                manager_name: 'Administrador',
                floors_number: 'Cantidad de pisos',
                apartments_per_floor: 'Apartamentos por piso',
                active: 'Estatus',
            }"
            title="Detalles del Edificio"
            @close="closeModal"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import ShowDetails from "@/Components/modals/ShowDetails.vue";
import FilterTemplate from "@/Components/FilterTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Swal from "sweetalert2";
import { notify } from "notiwind";
import { EyeIcon } from '@heroicons/vue/24/solid';

document.title="Listado de edificios";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const buildings = ref(props.buildings.data);
const residence = ref(props.residence || null);
const residenceId = residence.value?.id || null;
const residenceName = residence.value?.name || null;
const links = ref(props.buildings.links);
const from = ref(props.buildings.from);
const to = ref(props.buildings.to);
const total = ref(props.buildings.total);
const currentPage = ref(props.buildings.current_page);
const rowsPerPage = ref(props.buildings.per_page ?? 5);

const transformedBuildings = computed(() => {
    if (user.type == "Admin") {
        return buildings.value.map((building) => ({
            manager: building.residence.manager.name,
            building: building.name,
            floorsNumber: building.floors_number,
            active: building.active,
            actions: { id: building.id },
        }));
    }

    return buildings.value.map((building) => ({
        building: building.name,
        floorsNumber: building.floors_number,
        active: building.active,
        actions: { id: building.id },
    }));
});

const deleteBuilding = (id) => {
    Swal.fire({
        customClass: {
            popup: themeStore.dark
                ? "bg-gray-900 text-white"
                : "bg-white text-gray-900",
        },
        text: "¿Desea eliminar este edificio?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7e3af2",
        cancelButtonColor: "#4c4f52",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('buildings.destroy', id), {
                onSuccess: (response) => {
                    buildings.value = response.props.data;
                    links.value = response.props.links;
                    total.value = response.props.total;
                    currentPage.value = response.props.currentPage;
                    rowsPerPage.value = response.props.rowsPerPage;
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "El edificio ha sido eliminado",
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
            });
        }
    });
};

const isModalOpen = ref(false);
const selectedRow = ref({});

const handleShowDetails = (id) => {
    const building = buildings.value.find((building) => building.id === id);
    if (building) {
        selectedRow.value = formatData(building);
        isModalOpen.value = true;;
    } else {
        console.error("No se encontró el edificio con el ID especificado");
    }
};

const formatData = (building) => {
    if (!building) return {};

    return {
        ...building,
        residence: building.residence.name,
        manager_name: building.residence?.manager
            ? `${building.residence.manager.name}`
            : "Sin asignar",
        active: building.active ? "Activo" : "Inactivo",
    };
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedRow.value = null;
};

// Variables para filtros reactivos
const filters = ref({ ...props.filters });

const filtersEnabled = {
    name: true,
    active: true,
};

// Función para obtener los datos filtrados
const fetchFilteredBuildings = () => {
    router.get(route("buildings.indexByResidence", { residenceId }),
        {
            ...filters.value,
            preserveScroll: true,
            preserveState: true,
        },
        {
            onSuccess: (page) => {
                console.log(page)
                buildings.value = page.props.buildings.data;
                links.value = page.props.buildings.links;
                total.value = page.props.buildings.total;
                currentPage.value = page.props.buildings.current_page;
                from.value = page.props.buildings.from;
                to.value = page.props.buildings.to;
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
    fetchFilteredBuildings();
};
</script>
