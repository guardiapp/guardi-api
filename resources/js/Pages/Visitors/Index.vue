<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residenceId}`, label: residenceName },
                    { label: 'Visitantes', isCurrent: true }
                ]"
            />
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Visitantes
                </h2>
            </div>
            <FilterTemplate
                :initial-filters="filters"
                :filters-enabled="filtersEnabled"
                :current-url="residenceId ? `/residences/${residenceId}/visitors` : '/visitors'"
                @update:filters="syncFilters"
            />
            <div v-if="visitors">
                <TableTemplate
                    :columns="user.type === 'Admin' ? ['Documento', 'Nombre', 'Apartamento', 'Residente', 'Administrador', 'Mostrar'] :  ['Documento', 'Nombre', 'Apartamento', 'Residente', 'Mostrar']"
                    :data="transformedVisitors"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :from="from"
                    :to="to"
                    :total="total"
                    :current-page="currentPage"
                >
                    <!-- Custom column for actions -->
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
                            <button
                                @click="handleShowVisitor(row)"
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
        <ShowVisitors
            :is-modal-open="isVisitorModalOpen"
            :visitor="selectedVisitor"
            @close-modal="isVisitorModalOpen = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import FilterTemplate from "@/Components/FilterTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";
import { usePage, router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { EyeIcon } from '@heroicons/vue/24/outline';
import ShowVisitors from "@/Components/modals/ShowVisitors.vue";

document.title="Listado de vistantes";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const isVisitorModalOpen = ref(false);

const openVisitorModal = () => {
    isVisitorModalOpen.value = true;
};

const visitors = ref(props.visitors.data);
const residence = ref(props.residence || null);
const residenceId = residence.value?.id || null ;
const residenceName = residence.value?.name || null;
const links = ref(props.visitors.links);
const from = ref(props.visitors.from);
const to = ref(props.visitors.to);
const total = ref(props.visitors.total);
const currentPage = ref(props.visitors.current_page);
const rowsPerPage = ref(props.visitors.per_page ?? 5);

const transformedVisitors = computed(() => {
    if (user.type === "Admin") {
        return visitors.value.map((visitor) => ({
            document: visitor.document,
            name:  `${visitor.first_name} ${visitor.last_name}`,
            apartment: visitor.apartment.identifier,
            resident:`${visitor.apartment.resident.profile.first_name} ${visitor.apartment.resident.profile.last_name}`,
            manager: visitor.apartment.building.residence.manager.name,
            actions: { id: visitor.id },
        }));
    }

    return visitors.value.map((visitor) => ({
        document: visitor.document,
        name:  `${visitor.first_name} ${visitor.last_name}`,
        apartment: visitor.apartment.identifier,
        resident:`${visitor.apartment.resident.profile.first_name} ${visitor.apartment.resident.profile.last_name}`,
        actions: { id: visitor.id },
    }));
});

const selectedVisitor = ref({
    document: '',
    first_name: '',
    last_name: '',
    resident: { first_name: '', last_name: '' },
});

const handleShowVisitor = (row) => {
    const visitor = visitors.value.find(
        (visitor) => visitor.id === row.actions.id
    );

    if (visitor) {
        selectedVisitor.value = visitor;
        openVisitorModal();
    } else {
        console.error("No se encontró el visitante con el ID especificado");
    }
}

// Variables para filtros reactivos
const filters = ref({ ...props.filters });

const filtersEnabled = {
    document: true,
    name: true,
    apartment: true,
    resident_name: true
};

// Función para obtener los datos filtrados
const fetchFilteredVisitors = () => {

    const endpoint = residenceId
    ? route("visitors.indexByResidence", { residenceId })
    : route("visitors.index");

    router.get( endpoint,
        {
            ...filters.value,
            preserveScroll: true,
            preserveState: true,
        },
        {
            onSuccess: (page) => {
                visitors.value = page.props.visitors.data;
                links.value = page.props.visitors.links;
                total.value = page.props.visitors.total;
                currentPage.value = page.props.visitors.current_page;
                from.value = page.props.visitors.from;
                to.value = page.props.visitors.to;
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
    fetchFilteredVisitors();
};


</script>
