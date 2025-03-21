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
                <Link
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :href="`/visits/create`"
                    >
                    Nuevo
                    <span class="ml-2" aria-hidden="true">+</span>
                </Link>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                :current-url="residenceId ? `/residences/${residenceId}/visits` : '/visits'"
                @update:filters="syncFilters"
            />
            <ul class="flex flex-wrap text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:border-gray-700 dark:text-gray-400">
                <li class="me-2">
                    <button
                        @click="switchTab('without_stay')"
                        class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                        :class="filters?.visitType != 'without_stay' || 'text-purple-600 dark:text-purple-500 bg-gray-100 dark:bg-gray-800 active'"
                        >
                        Unicas
                    </button>
                </li>
                <li class="me-2">
                    <button
                        @click="switchTab('with_stay')"
                        class="inline-block p-4 rounded-t-lg hover:text-gray-600 hover:bg-gray-50 dark:hover:bg-gray-800 dark:hover:text-gray-300"
                        :class="filters?.visitType != 'with_stay' || 'text-purple-600 dark:text-purple-500 bg-gray-100 dark:bg-gray-800 active'"
                    >
                        Recurrentes
                    </button>
                </li>
            </ul>

            <div v-if="visits">
                <TableTemplate
                    :columns="columnsList"
                    :data="visits"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
                    :total="total"
                    :current-page="currentPage"
                    :without-top-radius="true"
                    @change-page="changePage"
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
                            <button
                                @click="handleEdit(row.actions.id)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
                                aria-label="Show"
                            >
                                <PencilIcon class="size-6" />
                            </button>
                            <button
                                @click="handleDelete(row.actions.id)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
                                aria-label="Show"
                            >
                                <TrashIcon class="size-6" />
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
import TableTemplate from "@/Components/TableTemplateAjax.vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import FilterTemplate from "@/Components/FilterTemplate.vue";
import ShowDetails from "@/Components/modals/ShowDetails.vue";
import Swal from "sweetalert2";
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/24/solid';
import { Link } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
import { usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch, onMounted } from "vue";
import { HttpParams } from "@/exchange/classes/HttpParams.class";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;


const residence = ref(props.residence || null);
const residenceId = residence.value?.id || null;
const residenceName = residence.value?.name || null;

const visits = ref([]);
const links = ref([]);
const from = ref([]);
const to = ref([]);
const total = ref([]);
const currentPage = ref([]);
const rowsPerPage = ref([] ?? 20);

onMounted(function () {
    findAllVisits();
});

const parseVisits = (data) => {
    if (filters.value.visitType == 'without_stay') {
        return data.map((visit) => ({
            visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
            resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
            residence: visit.apartment.building.residence.name,
            date: new Date(visit.visit_date).toLocaleDateString('es-VE'),
            hour: visit.entry_time,
            status: visit.visited,
            actions: { id: visit.id },
        }));
    }

    return data.map((visit) => ({
        visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
        resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
        date: new Date(visit.expiration_date).toLocaleDateString('es-VE'),
        actions: { id: visit.id },
    }));
}

/*const transformedVisits = computed(() => {
    if (filters.value.visitType == 'without_stay') {
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
});*/

const normalTab = ref(props.filters?.normalTab === undefined || props.filters.normalTab === 'true' || props.filters.normalTab === true || props.filters.normalTab == 1 || props.filters.normalTab == 0);

const switchTab = (tab) => {
    normalTab.value = tab === "normal";
    filters.value.visitType = tab;
    findAllVisits();
    //fetchFilteredVisits();
};

const columnsList = computed(() => {
    return filters.value.visitType == 'without_stay'
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

const changePage = (e) => {
    filters.value.page = e;
    findAllVisits();
}


const filters = ref({
    visitor_name:"",
    resident_name:"",
    apartment:"",
    visit_date:"",
    expiration_date:"",
    page: 1,
    visitType:"without_stay",
    managerId: user.type == 'Manager' ? user.id : '',
    entry_time: ""
});

const filtersEnabled = computed(() => ({
    visitor_name: true,
    resident_name: true,
    visit_date: normalTab.value,
    expiration_date: !normalTab.value,
    entry_time: true
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

const findAllVisits = async () => {
    const params = new HttpParams();
    params.append(
        { param: "visitor_name", value: filters.value.visitor_name ?? "" },
        { param: "resident_name", value: filters.value.resident_name ?? "" },
        { param: "apartment", value: filters.value.apartment ?? "" },
        { param: "visit_date", value: filters.value.visit_date ?? "" },
        { param: "expiration_date", value:filters.value.expiration_date ?? "" },
        { param: "visitType", value: filters.value.visitType ?? "" },
        { param: "page", value: filters.value.page ?? 1 },
        { param: "managerId", value: filters.value.managerId ?? "" },
        { param: "entry_time", value: filters.value.entry_time ?? "" },
    );
    const response = await axios.get(`/visits/find-all?${params.toString()}`);
    const data = response.data;
    visits.value = parseVisits(data.data);
    links.value = data.links;
    total.value = data.total;
    from.value = data.from;
    to.value = data.to;
    currentPage.value = data.current_page;
}


const syncFilters = (updatedFilters) => {
    filters.value = updatedFilters;
    findAllVisits();
};

/**
 *
 * @param {Number} id
 */
const handleEdit = (id) => {
    router.visit('/visits/edit/' + id);
};

/**
 *
 * @param {Number} id
 */
const handleDelete = async (id) => {

    Swal.fire({
        customClass: {
            popup: themeStore.dark
                ? "bg-gray-900 text-white"
                : "bg-white text-gray-900",
        },
        text: "¿Desea eliminar esta visita?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#7e3af2",
        cancelButtonColor: "#4c4f52",
        confirmButtonText: "Si, eliminar",
        cancelButtonText: "Cancelar",
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('visits.destroy', id), {
                onSuccess: (response) => {
                    fetchFilteredVisits();
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "La visita ha sido eliminada",
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


</script>
