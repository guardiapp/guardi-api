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
            <div v-if="visitors">
                <TableTemplate
                    :columns="user.type === 'Admin' ? ['Documento', 'Nombre', 'Residente', 'Residencia', 'Administrador', 'Mostrar'] :  ['Documento', 'Nombre', 'Residente', 'Residencia', 'Mostrar']"
                    :data="transformedVisitors"
                    :links="links"
                    :rows-per-page="rowsPerPage"
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
import { useThemeStore } from "@/stores/themeStore";
import { usePage } from "@inertiajs/vue3";
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

const visitors = ref(props.data);
const links = ref(props.links);
const total = ref(props.total);
const currentPage = ref(props.current_page);
const rowsPerPage = ref(props.per_page ?? 5);

const transformedVisitors = computed(() => {
    console.log(visitors)
    if (user.type === "Admin") {
        return visitors.value.map((visitor) => ({
            document: visitor.document,
            name:  `${visitor.first_name} ${visitor.last_name}`,
            resident:`${visitor.apartment.resident.first_name} ${visitor.apartment.resident.last_name}`,
            residence: visitor.apartment.building.residence.name,
            manager: visitor.apartment.building.residence.manager.name,
            actions: { id: visitor.id },
        }));
    }

    return visitors.value.map((visitor) => ({
        document: visitor.document,
        name:  `${visitor.first_name} ${visitor.last_name}`,
        resident:`${visitor.resident.first_name} ${visitor.resident.last_name}`,
        residence: visitor.resident.building.residence.name,
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
        console.log(visitor);
        selectedVisitor.value = visitor;
        openVisitorModal();
    } else {
        console.error("No se encontró el visitante con el ID especificado");
    }
}
</script>
