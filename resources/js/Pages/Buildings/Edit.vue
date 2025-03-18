<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residenceStore.selectedResidence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residence.id}`, label: residence.name },
                    { url: `/residences/${residence.id}/buildings`, label: 'Edificios' },
                    { label: 'Crear', isCurrent: true },
                ]"
            />

            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Editar Edificio
            </h2>
            <form @submit.prevent="submit">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Columna del formulario -->
                    <div class="col-span-3 space-y-4">

                        <!-- Checkbox para Activo -->
                        <div class="text-sm my-5">
                            <label class="flex items-center dark:text-gray-400">
                                <input
                                    v-model="form.active"
                                    type="checkbox"
                                    class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                />
                                <span class="ml-2">
                                    Activo
                                </span>
                            </label>
                        </div>

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
                                    v-model="form.residence_id"
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
import { computed, reactive, ref, watch, watchEffect, onMounted } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { notify } from "notiwind";
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
const themeStore = useThemeStore();
const residenceStore = useResidenceStore();

const user = usePage().props.auth.user;

// Props recibidas
const { props } = usePage();
const managers = props.managers || [];
const residences = ref(props.residences || []);
const building = props.building || [];
const selectedManager = ref(props.building.residence.manager[0].id);
const selectedResidence = ref("");
const residence = computed(() => residenceStore.selectedResidence);

// Configuración del formulario
const form = useForm({
    _method: 'PUT',
    residence_id: building.residence_id,
    name: building.name,
    floors_number: building.floors_number,
    apartments_per_floor: building.apartments_per_floor,
    information: building.information,
    active: building.active === 1 || building.active === true,
});

watchEffect(() => {
    if (residenceStore.selectedResidence) {
        console.log('watch effect:', residenceStore.selectedResidence.id);
        form.residence_id = residenceStore.selectedResidence.id;
    }
});

onMounted(() => {
    if (residenceStore.selectedResidence) {
        form.residence_id = residenceStore.selectedResidence.id;
    }

});

const avatar = ref(null);

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

    console.log('se ejecuto el watch', newManagerId);
    if (!newManagerId) {
        residences.value = [];
        selectedResidence.value = "";
        form.building_id = "";
        return;
    }

    try {
        const response = await axios.get(`/managers/${newManagerId}/residences`);
        console.log(response.data.residences);
        residences.value = response.data.residences;
        selectedResidence.value = "";
        form.building_id = "";
    } catch (error) {
        console.error("Error al cargar residencias:", error);
        residences.value = [];
    }
});

const submit = async () => {
    hasSubmitted.value = true;
    //validateAll();
    if (!isFormValid.value) return;

    form.post(`/buildings/${building.id}`, {
        onSuccess: (response) => {
            console.log(response)
            form.reset();
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Guardado",
                text: "Edificio actualizado exitosamente",
            }, 4000);
        },
        onError: (errors) => {
            if (errors) {
                notify({
                    group: "error",
                    title: "Error",
                    text: "No se pudo actualizar, revise los datos",
                }, 4000);
            }
        }
    });
};

const handleInput = (fieldName) => {
    if (hasSubmitted.value) {
        validateField(fieldName);
    }
};

// Cancelar y volver al listado
const cancel = () => {
    if (residenceStore.selectedResidence) {
        router.visit(`/residences/${residenceStore.selectedResidence.id}/buildings`);
    } else {
        router.visit("/buildings");
    }
};
</script>
