<template>
    <div
        v-show="isModalOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
        <!-- Modal -->
        <div
            v-show="isModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 transform translate-y-1/2"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0  transform translate-y-1/2"
            @keydown.escape="emitCloseModal"
            class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
            role="dialog"
            id="modal"
        >
            <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
            <header class="flex justify-end">
                <button
                    class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                    aria-label="close"
                    @click="emitCloseModal"
                >
                    <svg
                        class="w-4 h-4"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        role="img"
                        aria-hidden="true"
                    >
                        <path
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                            fill-rule="evenodd"
                        ></path>
                    </svg>
                </button>
            </header>
            <form @submit.prevent="submit">
                <!-- Modal body -->
                <div class="mt-4 mb-6">
                    <!-- Modal title -->
                    <p
                        class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300"
                    >
                        Agregar Edificio
                    </p>

                    <div
                        class="px-4 py-3 mb-8 rounded-lg shadow-md"
                        :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                    >
                        <!-- Modal description -->
                        <p
                            class="text-sm my-5"
                            :class="
                                themeStore.dark
                                    ? 'text-gray-400'
                                    : 'text-gray-700'
                            "
                        >
                            Residencia:
                            <span
                                class="font-semibold"
                                :class="
                                    themeStore.dark
                                        ? 'text-gray-300'
                                        : 'text-gray-700'
                                "
                            >
                                {{ residence.name }}</span
                            >
                        </p>

                        <!-- Input para Nombre -->
                        <div class="text-sm my-5">
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
                                    v-model="formBuilding.name"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput()"
                                />
                                <p
                                    v-if="errorName"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errorName }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para cantidad de pisos -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Cantidad de pisos</span
                                >
                                <input
                                    v-model="formBuilding.floors_number"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="clearError('floors_number')"
                                />
                                <p
                                    v-if="errorFloors"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errorFloors }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para apartamentos por piso -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Apartamentos por piso</span
                                >
                                <input
                                    v-model="formBuilding.apartments_per_floor"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="clearError('apartments_per_floor')"
                                />
                                <p
                                    v-if="errorApart"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errorApart }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para información -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Información</span
                                >
                                <input
                                    v-model="formBuilding.information"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                />
                            </label>
                        </div>
                    </div>
                </div>
                <footer
                    class="flex flex-col items-center justify-end px-6 py-3 -mx-6 -mb-4 space-y-4 sm:space-y-0 sm:space-x-6 sm:flex-row bg-gray-50 dark:bg-gray-800"
                >
                    <button
                        @click="emitCloseModal"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray"
                    >
                        Cancelar
                    </button>
                    <button
                        type="submit"
                        class="w-full px-5 py-3 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg sm:w-auto sm:px-4 sm:py-2 active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    >
                        Agregar
                    </button>
                </footer>
            </form>
        </div>
    </div>
</template>

<script setup>
import { useForm, router } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
import { ref } from "vue";
import { notify } from "notiwind";
const themeStore = useThemeStore();

const props = defineProps({
    isModalOpen: Boolean,
    residence: Object,
});

const formBuilding = useForm({
    residence_id: props.residence.id,
    name: "",
    floors_number: "",
    apartments_per_floor: "",
    information: "",
    active: ""
});

const hasSubmitted = ref(false)

const errorName = ref('');
const errorFloors = ref('');
const errorApart = ref('');

const emit = defineEmits(["closeModal", "updateBuildings"]);

// Función para emitir el evento al padre
const emitCloseModal = () => {
    errorName.value = '';
    hasSubmitted.value = false;
    formBuilding.reset();
    emit("closeModal");
};

const handleInput = () => {
    errorName.value = hasSubmitted.value && formBuilding.name.trim() === ""
        ? "el nombre es obligatorio"
        : ""
};

const clearError = (err) => {
    if (err == 'apartments_per_floor') errorApart.value = '';
    if (err == 'floors_number') errorFloors.value = '';
}


// Manejar el envío del formulario
const submit = async () => {
    if ( formBuilding.name.trim() === "" ) {
        errorName.value = "El nombre es obligatorio";
        return;
    }
    hasSubmitted.value = true;
    try {
        formBuilding.post(`/buildings`, {
            onSuccess: (response) => {
                console.log("updateBuildings", response.props.residence.buildings);
                emit("updateBuildings", response.props.residence.buildings);
                hasSubmitted.value = false;
                formBuilding.reset();
                notify(
                    {
                        group: "success",
                        title: "Nuevo edificio",
                        text: "El edificio ha sido registrado",
                    },
                    4000
                );
                emitCloseModal();
            },
            onError: (err) => {
                console.log(err)
                if (err.apartments_per_floor) {
                    errorApart.value = "Debe agregar un valor numérico"
                }
                if (err.floors_number) {
                    errorFloors.value = "Debe agregar un valor numérico"
                }
            }
        });
    } catch (error) {
        console.error("Error al enviar:", error);
    }
};
</script>
