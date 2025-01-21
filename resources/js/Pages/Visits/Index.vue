<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residenceId}`, label: residenceName },
                    { label: 'Visitas', isCurrent: true }
                ]"
            />
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Visitas
                </h2>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                :current-url="residenceId ? `/residences/${residenceId}/visits` : '/visits'"
                @update:filters="syncFilters"
            />
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <li class="me-2">
                    <button @click="switchTab('normal')"
                    aria-current="page"
                    class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                    :class="!normalTab || 'text-purple-600 dark:text-purple-500 bg-gray-100 dark:bg-gray-800 active'"
                    >Unicas</button>
                </li>
                <li class="me-2">
                    <button @click="switchTab('recurrent')"
                    class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                    :class="normalTab || 'text-purple-600 dark:text-purple-500 bg-gray-100 dark:bg-gray-800 active'"
                    >Recurrentes</button>
                </li>
            </ul>

            <div v-if="visits">
                <TableTemplate
                    :columns="columnsList"
                    :data="transformedVisits"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
                    :total="total"
                    :current-page="currentPage"
                    :without-top-radius="true"
                >
                    <template #column-date="{ value }">
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
                    <template #column-hour="{ value }">
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
                    <template #column-status="{ value }">
                        <span
                            v-if="value == 1"
                            class="font-semibold"
                            :class="
                                themeStore.dark ? 'text-gray-300' : 'text-gray-700'
                            "
                        >
                            Efectuada
                        </span>
                        <span
                            v-else
                            class="font-semibold"
                            :class="
                                themeStore.dark ? 'text-gray-300' : 'text-gray-700'
                            "
                        >
                            Pendiente
                        </span>
                    </template>

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
                date: selectedRow.visit_date? 'Fecha' : 'Fecha de vencimiento',
                type: 'Tipo',
                visitor: 'Visitante',
                resident: 'Residente',
                apartment: 'Apartamento',
                residence: 'Residencia',
                active: 'Estatus',
            }"
            title="Detalles de la visita"
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
import { EyeIcon } from '@heroicons/vue/24/solid';
import { useThemeStore } from "@/stores/themeStore";
import { usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";

document.title="Listado de visitas";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const isVisitModalOpen = ref(false);

const openVisitModal = () => {
    isVisitModalOpen.value = true;
};

const residence = ref(props.residence || null);
const residenceId = residence.value?.id || null;
const residenceName = residence.value?.name || null;

const visits = ref(props.visits.data);
const links = ref(props.visits.links);
const from = ref(props.visits.from);
const to = ref(props.visits.to);
const total = ref(props.visits.total);
const currentPage = ref(props.visits.current_page);
const rowsPerPage = ref(props.visits.per_page ?? 5);

const transformedVisits = computed(() => {
    if (normalTab.value) {
        return visits.value.map((visit) => ({
            visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
            resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
            residence: visit.apartment.building.residence.name,
            date: new Date(visit.visit_date).toLocaleDateString('es-VE'),
            hour: new Date(visit.visit_date).toLocaleTimeString('es-VE', { hour: '2-digit', minute: '2-digit', hour12: true }),
            status: visit.visited,
            actions: { id: visit.id },
        }));
    }

    return visits.value.map((visit) => ({
        visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
        resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
        date: new Date(visit.expiration_date).toLocaleDateString('es-VE'),
        actions: { id: visit.id },
    }));
});

const normalTab = ref(props.filters?.normalTab === undefined || props.filters.normalTab === 'true' || props.filters.normalTab === true);

const switchTab = (tab) => {
    normalTab.value = tab === "normal";
    fetchFilteredVisits();
};

const columnsList = computed(() => {
    return normalTab.value
        ? ['Visitante', 'Residente', 'Residencia', 'Fecha', 'Hora', 'Estatus', 'Acciones']
        : ['Visitante', 'Residente', 'Fecha de expiración', 'Acciones'];
});

const isModalOpen = ref(false);
const selectedRow = ref({});

const handleShowDetails = (id) => {
    const visit = visits.value.find((visit) => visit.id === id);
    if (visit) {
        selectedRow.value = formatData(visit);
        isModalOpen.value = true;;
    } else {
        console.error("No se encontró el edificio con el ID especificado");
    }
};

const formatData = (visit) => {
    if (!visit) return {};

    return {
        ...visit,
        visitor:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
        resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
        residence: visit.apartment.building.residence.name,
        apartment: visit.apartment.identifier,
        type: visit.visit_date ? 'Unica' : 'Recurrente',
        date: formatVisitDate(visit),
        active: visit.active ? "Efectuada" : "Pendiente",
    };
};

const formatVisitDate = (visit) => {
    if (visit.visit_date) {
        return `${new Date(visit.visit_date).toLocaleDateString('es-VE')} a las ${new Date(visit.visit_date).toLocaleTimeString('es-VE')}`;
    } else if (visit.expiration_date) {
        return`${new Date(visit.expiration_date).toLocaleDateString('es-VE')}`
    }
    return  'No defiinida';
}

const closeModal = () => {
    isModalOpen.value = false;
    selectedRow.value = null;
};


const filters = ref({ ...props.filters });

const filtersEnabled = computed(() => ({
    visitor_name: true,
    resident_name: true,
    visit_date: normalTab.value,
    expiration_date: !normalTab.value,
}));

const fetchFilteredVisits = () => {

    const endpoint = residenceId
        ? route("visits.indexByResidence", { residenceId })
        : route("visits.index");

    filters.value.normalTab = normalTab.value ? 1 : 0;
    router.get(endpoint,
        {
            ...filters.value,
            preserveScroll: true,
            preserveState: true,
        },
        {
            onSuccess: (page) => {
                visits.value = page.props.visits.data;
                links.value = page.props.visits.links;
                total.value = page.props.visits.total;
                from.value = page.props.visits.from;
                to.value = page.props.visits.to;
                currentPage.value = page.props.visits.current_page;
            },
            onError: (error) => {
                console.error("Error al obtener visitas filtradas:", error);
            },
        }
    );
};


const syncFilters = (updatedFilters) => {
    filters.value = updatedFilters;
    fetchFilteredVisits();
};


</script>
