<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residenceId}`, label: residenceName },
                    { label: 'Apartamentos', isCurrent: true }
                ]"
            />
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Apartamentos
                </h2>
                <Link
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :href="`/apartments/create`"
                    >
                    Nuevo
                    <span class="ml-2" aria-hidden="true">+</span>
                </Link>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                :current-url="residence ? `/residences/${residenceId}/apartments` : '/apartments'"
                @update:filters="syncFilters"
            />
            <div v-if="apartments">
                <TableTemplate
                    :columns="user.type == 'Admin' ? ['', 'Identificador', 'Residente', 'Edificio', 'Residencia', 'Acciones'] : ['', 'Identificador', 'Residente', 'Edificio', 'Acciones']"
                    :data="transformedApartments"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
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

                    <!-- Custom column for actions -->
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
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
                                :href="`/apartments/${row.actions.id}`"
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
                                @click="deleteApartment(row.actions.id)"
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
                identifier: 'Identificador',
                resident: 'Residente',
                building: 'Edificio',
                residence: 'Residencia',
            }"
            title="Detalles del Apartamento"
            @close="closeModal"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import FilterTemplate from "@/Components/FilterTemplate.vue";
import ShowDetails from "@/Components/modals/ShowDetails.vue";
import { useThemeStore } from "@/stores/themeStore";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import Swal from "sweetalert2";
import { notify } from "notiwind";
import { EyeIcon } from "@heroicons/vue/24/solid";
EyeIcon
document.title="Listado de apartamentos";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const apartments = ref(props.apartments.data);
const residence = ref(props.residence || null);
const residenceId = residence.value?.id || null;
const residenceName = residence.value?.name || null;
const links = ref(props.apartments.links);
const from = ref(props.apartments.from);
const to = ref(props.apartments.to);
const total = ref(props.apartments.total);
const currentPage = ref(props.apartments.current_page);
const rowsPerPage = ref(props.apartments.per_page ?? 5);

const transformedApartments = computed(() => {
    if (user.type == "Admin") {
        return apartments.value.map((apartment) => ({
            avatar: apartment.resident.avatar,
            identifier:  apartment.identifier,
            resident: `${apartment.resident.profile.first_name} ${apartment.resident.profile.last_name}`,
            building: apartment.building.name,
            residence: apartment.building.residence.name,
            actions: { id: apartment.id },
        }));
    }

    return apartments.value.map((apartment) => ({
        avatar: apartment.resident.avatar,
        identifier:  apartment.identifier,
        resident: `${apartment.resident.profile.first_name} ${apartment.resident.profile.last_name}`,
        building: apartment.building.name,
        actions: { id: apartment.id },
    }));
});

const deleteApartment = (id) => {
    Swal.fire({
        customClass: {
            popup: themeStore.dark
                ? "bg-gray-900 text-white"
                : "bg-white text-gray-900",
        },
        text: "¿Desea eliminar este apartamento?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7e3af2",
        cancelButtonColor: "#4c4f52",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('apartments.destroy', id), {
                onSuccess: (response) => {
                    apartments.value = response.props.data;
                    links.value = response.props.links;
                    from.value = response.props.from;
                    to.value = response.props.to;
                    total.value = response.props.total;
                    currentPage.value = response.props.currentPage;
                    rowsPerPage.value = response.props.rowsPerPage;
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "El apartamento ha sido eliminado",
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
    const apartment = apartments.value.find((apartment) => apartment.id === id);
    if (apartment) {
        selectedRow.value = formatData(apartment);
        isModalOpen.value = true;;
    } else {
        console.error("No se encontró el apartamento con el ID especificado");
    }
};

const formatData = (apartment) => {
    if (!apartment) return {};

    return {
        ...apartment,
        resident: apartment.resident?.profile
            ? `${apartment.resident.profile.first_name} ${apartment.resident.profile.last_name}`
            : "Sin asignar",
        building: apartment.building
            ? `${apartment.building.name}`
            : "Sin asignar",
        residence: apartment.building.residence ? apartment.building.residence.name : "Sin asignar",
    };
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedRow.value = null;
};



// Variables para filtros reactivos
const filters = ref({ ...props.filters });

const filtersEnabled = {
    identifier: true,
    resident_name: true,
    building_name: true,
};

// Función para obtener los datos filtrados
const fetchFilteredApartments = () => {
    const endpoint = residenceId
        ? route("apartments.indexByResidence", { residenceId })
        : route("apartments.index");

        router.get(
            endpoint,
        {
            ...filters.value,
            preserveScroll: true,
            preserveState: true,
        },
        {
            onSuccess: (page) => {
                apartments.value = page.props.apartments.data;
                links.value = page.props.apartments.links;
                from.value = page.props.apartments.from;
                to.value = page.props.apartments.to;
                total.value = page.props.apartments.total;
                currentPage.value = page.props.apartments.current_page;
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
    fetchFilteredApartments();
};
</script>
