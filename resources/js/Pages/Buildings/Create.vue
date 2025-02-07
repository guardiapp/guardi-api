<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Nuevo Edificio
            </h2>
            <form @submit.prevent="submit">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Columna del formulario -->
                    <div class="col-span-3 space-y-4">

                        <!-- Input para Administrador -->
                        <div class="text-sm my-5" v-if="managers.length">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Administrador</span
                                >
                                <select
                                    v-model="selectedManager"
                                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                >
                                    <option
                                        v-for="manager in managers"
                                        :key="manager.id"
                                        :value="manager.id"
                                    >
                                        {{ manager.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

                        <!-- Input para Residencia -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Residencia</span
                                >
                                <select
                                    v-model="selectedResidence"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    >
                                    <option
                                        v-for="residence in residences"
                                        :key="residence.id"
                                        :value="residence.id"
                                    >
                                        {{ residence.name }}
                                    </option>
                                </select>
                            </label>
                        </div>

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
                                    v-model="form.name"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.name,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('name')"
                                />
                                <p
                                    v-if="errors.name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.name }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Cantidad de pisos -->
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
                                    v-model="form.floors_number"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.floors_number,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('floors_number')"
                                />
                                <p
                                    v-if="errors.floors_number"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.floors_number }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Apartamentos por piso -->
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
                                    v-model="form.apartments_per_floor"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.apartments_per_floor,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('apartments_per_floor')"
                                />
                                <p
                                    v-if="errors.apartments_per_floor"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.apartments_per_floor }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Información -->
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
                                    v-model="form.information"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.information,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('information')"
                                />
                                <p
                                    v-if="errors.information"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.information }}
                                </p>
                            </label>
                        </div>
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
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </MainLayout>
</template>

<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { computed, reactive, ref, watch, watchEffect } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { notify } from "notiwind";
const themeStore = useThemeStore();
const residenceStore = useResidenceStore();

const user = usePage().props.auth.user;

// Props recibidas
const { props } = usePage();
const managers = props.managers || [];
const residences = ref([]);
const buildings = ref([]);
const selectedManager = ref("");
const selectedResidence = ref("");

// Carga inicial de residencias
if (props.residences) {
    residences.value = props.residences;
}

// Carga inicial de residencias
if (props.buildings) {
    buildings.value = props.buildings;
}
watch(
    () => residenceStore.selectedResidence,
    (newResidence) => {
        if (newResidence) {
            console.log("Nueva residencia seleccionada:", newResidence);
            selectedResidence.value = newResidence.id;
        }
    },
    { immediate: true } // 🔥 Ejecuta una vez al inicio
);

// Configuración del formulario
const form = useForm({
    residence_id: "",
    name: "",
    floors_number: "",
    apartments_per_floor: "",
    information: "",
    active: true
});

const avatar = ref(null); // Para manejar la vista previa del avatar

// Estados y errores
const errors = reactive({
    residence_id: "",
    name: "",
});

const hasSubmitted = ref(false);

const isFormValid = computed(() =>
    form.residence_id != "" &&
    form.name !== ""
);

const validateField = (fieldName) => {
    switch (fieldName) {
        case "residence_id":
            errors.residence_id = form.building_id === "" ? "La residencia es obligatoria." : "";
            break;
        case "name":
            errors.name = form.name === "" ? "El nombre es obligatorio." : "";
            break;
    }
};

const validateAll = () => {
    validateField("residence_id");
    validateField("name");
};

watch(selectedManager, async (newManagerId) => {
    if (!newManagerId) {
        residences.value = [];
        buildings.value = [];
        selectedResidence.value = "";
        form.building_id = "";
        return;
    }

    try {
        const response = await axios.get(`/managers/${newManagerId}/residences`);
        residences.value = response.data.residences;
        selectedResidence.value = "";
        buildings.value = [];
        form.building_id = "";
    } catch (error) {
        console.error("Error al cargar residencias:", error);
        residences.value = [];
        buildings.value = [];
    }
});

const submit = async () => {
    hasSubmitted.value = true;
    validateAll();

    if (!isFormValid.value) return;

    form.name = form.email;

    await form.post(`/apartments`, {
        onSuccess: (response) => {
            console.log(response)
            form.reset();
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Guardado",
                text: "Nuevo apartamento creado",
            }, 4000);
        },
        onError: (errors) => {
            if (errors.email) {
                notify({
                    group: "error",
                    title: "Error",
                    text: errors.email,
                }, 4000);
            } else {
                notify({
                    group: "error",
                    title: "Error",
                    text: "No se pudo crear, revise los datos",
                }, 4000);
            }
        },
    });
};

const handleInput = (fieldName) => {
    if (hasSubmitted.value) {
        validateField(fieldName);
    }
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/apartments");
};
</script>
