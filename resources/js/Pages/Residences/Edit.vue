<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Editar Residencia
            </h2>

            <form @submit.prevent="submit">
                <div
                    class="px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Input para Nombre -->
                    <div class="text-sm">
                        <label class="block text-sm">
                            <span
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-gray-700'
                                "
                                >Nombre</span
                            >
                            <input
                                v-model="form.name"
                                class="block w-full mt-1 text-sm form-input"
                                :class="{
                                    'border-red-500': errors.name,
                                    ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                        !themeStore.dark,
                                    'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                        themeStore.dark,
                                }"
                                placeholder="Nombre de la residencia"
                            />
                            <p
                                v-if="errors.name"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.name }}
                            </p>
                        </label>
                    </div>

                    <!-- Input para Dirección -->
                    <div class="mt-4 text-sm">
                        <label class="block text-sm">
                            <span
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-gray-700'
                                "
                                >Dirección</span
                            >
                            <input
                                v-model="form.address"
                                class="block w-full mt-1 text-sm form-input"
                                :class="{
                                    'border-red-500': errors.address,
                                    'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                        !themeStore.dark,
                                    'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                        themeStore.dark,
                                }"
                                placeholder="Dirección de la residencia"
                            />
                            <p
                                v-if="errors.address"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.address }}
                            </p>
                        </label>
                    </div>
                </div>

                <!-- Botones -->
                <div class="flex justify-end space-x-4">
                    <button
                        type="button"
                        @click="cancel"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        :disabled="!isFormValid"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>

        <div class="container px-6 mx-auto grid">
            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Edificios
                </h2>
                <button
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    @click="openBuildingModal"
                >
                    Agregar nueva
                    <span class="ml-2" aria-hidden="true">+</span>
                </button>
            </div>

            <div
                class="px-4 py-3 mb-8 rounded-lg shadow-md"
                :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
            >
                <TableTemplate
                    v-if="transformedBuildings.length > 0"
                    :columns="['Nombre', 'Cantidad de apartamentos', 'Apartamentos por piso', 'Residentes', 'Eliminar']"
                    :data="transformedBuildings"
                    :rows-per-page="5"
                    :show-pagination="false"
                >
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
                            <button
                                @click="handleUpdateBuilding(row)"
                                class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-400'
                                        : 'text-purple-600'
                                "
                                aria-label="Actualizar"
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
                                @click="handleDeleteBuilding(row.actions.id)"
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
                <div
                    v-else
                    class="my-6 text-xs font-semibold text-center"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    No se han agregado residencias
                </div>
            </div>
        </div>
        <CreateBuilding
            :is-modal-open="isBuildingModalOpen"
            :residence="residence"
            :building="selectedBuilding"
            @update-buildings="updateBuildings"
            @reset-building="selectedBuilding = null"
            @close-modal="isBuildingModalOpen = false"
        />
    </MainLayout>
</template>

<script setup>
import TableTemplate from "@/Components/TableTemplate.vue";
import CreateBuilding from "@/Components/modals/CreateBuilding.vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { computed, reactive, ref, watch } from "vue";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();
import { notify } from "notiwind";
import Swal from "sweetalert2";

// Props recibidas
const { props } = usePage();
const residence = reactive(props.residence);

const selectedBuilding = ref({});

// Configuración del formulario
const form = useForm({
    name: residence.name || "",
    address: residence.address || "",
});

const transformedBuildings = ref(
    residence.buildings.map((building) => ({
        name: building.name,
        floors_number: building.floors_number,
        aparments_per_floor: building.apartments_per_floor,
        residents: building.residents.length ?? '',
        actions: { id: building.id },
    }))
);

// Transform data for the table
watch(
    () => residence.buildings,
    (newBuildings) => {
        console.log('Se ejecutara el watch');
        transformedBuildings.value = newBuildings.map((building) => ({
            name: building.name,
            floors_number: building.floors_number,
            aparments_per_floor: building.apartments_per_floor,
            residents: building.residents.length ?? '',
            actions: { id: building.id },
        }));
    },
    { deep: true }
)

// Validar si el formulario es válido (ambos campos llenos)
const isFormValid = computed(
    () => form.name.trim() !== "" && form.address.trim() !== ""
);

// Manejar errores
const errors = reactive({
    name: "",
    address: "",
});

// Manejar el envío del formulario
const submit = () => {
    errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
    errors.address =
        form.address.trim() === "" ? "La dirección es obligatoria." : "";

    if (!isFormValid.value) return;

    form.put(`/residences/${residence.id}`, {
        onSuccess: () => {
            form.reset();
            notify(
                {
                    group: "info",
                    title: "Cambio realizado",
                    text: "La residencia ha sido editada",
                },
                4000
            );

            //router.visit("/residences"); // Redirige al listado
        },
    });
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/residences");
};

const isBuildingModalOpen = ref(false);

const openBuildingModal = () => {
    isBuildingModalOpen.value = true;
};

const updateBuildings = (updatedBuildings) => {
    residence.buildings = updatedBuildings;
};

const handleUpdateBuilding = (row) => {
    const building = residence.buildings.find(
        (building) => building.id === row.actions.id
    );

    if (building) {
        selectedBuilding.value = building;
        openBuildingModal();
    } else {
        console.error("No se encontró el edificio con el ID especificado");
    }
}

const handleDeleteBuilding = (id) => {
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
            router.delete(route('buildings.destroy', id),{
                // onBefore: () => {
                //     return window.confirm('Are you sure you want to delete this post?');
                // },
                onSuccess: (response) => {
                    residence.buildings = response.props.residence.buildings;
                    notify(
                        {
                            group: "info",
                            title: "Cambio realizado",
                            text: "El edificio ha sido eliminado",
                        },
                        4000
                    );
                },
                onError: () => {
                    notify(
                        {
                            group: "error",
                            title: "Falló",
                            text: "No se pudo eliminar",
                        },
                        4000
                    );
                }
            });
        }
    });
};


</script>
