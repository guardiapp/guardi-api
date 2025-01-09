<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Visitantes
                </h2>
            </div>
            <div v-if="visits">
                <TableTemplate
                    :columns="user.type === 'Admin' ? ['Vistante', 'Residente', 'Residencia', 'Fecha', 'Administrador', 'Mostrar'] :  ['Vistante', 'Residente', 'Residencia', 'Fecha', 'Mostrar']"
                    :data="transformedVisits"
                    :links="links"
                    :rows-per-page="rowsPerPage"
                    :total="total"
                    :current-page="currentPage"
                >
                    <!-- Custom column for actions -->
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
                            <button
                                @click="handleShowVisit(row)"
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
        <ShowVisit
            :is-modal-open="isVisitModalOpen"
            :visit="selectedVisit"
            @close-modal="isVisitModalOpen = false"
        />
    </MainLayout>
</template>

<script setup>
import MainLayout from "@/Layouts/MainLayout.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";
import { usePage } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { EyeIcon } from '@heroicons/vue/24/outline';
import ShowVisit from "@/Components/modals/ShowVisit.vue";

document.title="Listado de visitas";

const themeStore = useThemeStore();
const { props } = usePage();

const user = usePage().props.auth.user;

const isVisitModalOpen = ref(false);

const openVisitModal = () => {
    isVisitModalOpen.value = true;
};

const visits = ref(props.data);
const links = ref(props.links);
const total = ref(props.total);
const currentPage = ref(props.current_page);
const rowsPerPage = ref(props.per_page ?? 5);

const transformedVisits = computed(() => {
    if (user.type === "Admin") {
        return visits.value.map((visit) => ({
            visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
            resident:`${visit.apartment.resident.profile.first_name} ${visit.apartment.resident.profile.last_name}`,
            residence: visit.apartment.building.residence.name,
            date: visit.visit_date,
            manager: visit.apartment.building.residence.manager.name,
            actions: { id: visit.id },
        }));
    }

    return visits.value.map((visit) => ({
        visitante:  `${visit.visitor.first_name} ${visit.visitor.last_name}`,
        resident:`${visit.apartment.resident.first_name} ${visit.apartment.resident.last_name}`,
        residence: visit.apartment.building.residence.name,
        date: visit.visit_date,
        actions: { id: visit.id },
    }));
});

const selectedVisit = ref({});

const handleShowVisit = (row) => {
    const visit = visits.value.find(
        (visit) => visit.id === row.actions.id
    );

    if (visit) {
        console.log(visit);
        selectedVisit.value = visit;
        openVisitModal();
    } else {
        console.error("No se encontró el visitante con el ID especificado");
    }
}
</script>
