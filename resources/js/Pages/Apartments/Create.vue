<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residenceStore.selectedResidence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    { url: `/residences/${residence.id}`, label: residence.name },
                    { url: `/residences/${residence.id}/buildings`, label: 'apartments' },
                    { label: 'Crear', isCurrent: true },
                ]"
            />
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Nuevo Apartamento
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

                        <!-- Input para Edificio -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Edificio</span
                                >
                                <select
                                    v-model="form.building_id"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.building_id,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('building_id')"
                                    >
                                    <option
                                        v-for="building in buildings"
                                        :key="building.id"
                                        :value="building.id"
                                    >
                                        {{ building.name }}
                                    </option>
                                </select>
                                <p
                                    v-if="errors.building_id"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.building_id }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Identificador -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Identificador</span
                                >
                                <input
                                    v-model="form.identifier"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.identifier,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('identifier')"
                                />
                                <p
                                    v-if="errors.identifier"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.identifier }}
                                </p>
                            </label>
                        </div>
                    </div>
                </div>

                <h3 class="my-6 text-1xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >Datos del Residente</h3>


                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >

                    <!-- Columna del formulario -->
                    <div class="col-span-2 space-y-4">

                        <!-- Input para correo -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Correo electrónico</span
                                >
                                <input
                                    v-model="form.email"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.email,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('email')"
                                />
                                <p
                                    v-if="errors.email"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.email }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para documento -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Documento de identidad</span
                                >
                                <input
                                    v-model="form.document"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.document,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('document')"
                                />
                                <p
                                    v-if="errors.document"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.document }}
                                </p>
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
                                    v-model="form.first_name"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.first_name,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('first_name')"
                                />
                                <p
                                    v-if="errors.first_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.first_name }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Apellido -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Apellidos</span
                                >
                                <input
                                    v-model="form.last_name"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.last_name,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('last_name')"
                                />
                                <p
                                    v-if="errors.last_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.last_name }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para teléfono -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Teléfono</span
                                >
                                <input
                                    v-model="form.phone"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.phone,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('phone')"
                                />
                                <p
                                    v-if="errors.phone"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.phone }}
                                </p>
                            </label>
                        </div>


                    </div>

                    <!-- Columna del avatar -->
                    <div class="flex flex-col items-center justify-center space-y-4">

                        <div
                            class="relative w-32 h-32 overflow-hidden rounded-full border-2 border-dashed border-gray-300"
                            :class="themeStore.dark ? 'border-gray-600' : 'border-gray-300'"
                        >
                            <img
                                v-if="avatar"
                                :src="avatar"
                                alt="Avatar preview"
                                class="w-full h-full object-cover"
                            />
                            <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                Sin avatar
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="handleAvatar"
                            />
                        </div>

                        <button
                            v-if="avatar"
                            type="button"
                            @click="removeAvatar"
                            class="px-3 py-1 text-sm font-medium leading-5 text-white bg-red-600 rounded-lg hover:bg-red-700 focus:outline-none focus:shadow-outline-red"
                        >
                            Eliminar Avatar
                        </button>
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
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import { computed, reactive, ref, watch, onMounted } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { notify } from "notiwind";
const themeStore = useThemeStore();
const residenceStore = useResidenceStore();

const user = usePage().props.auth.user;

// Props recibidas
const { props } = usePage();
const managers = props.managers || [];
const residences = ref(props.residences || []);
const buildings = ref(props.buildings || []);
const selectedManager = ref("");
const selectedResidence = ref("");
const residence = computed(() => residenceStore.selectedResidence);

// Configuración del formulario
const form = useForm({
    email: "",
    name:"",
    building_id: "",
    identifier: "",
    first_name: "",
    last_name: "",
    document: "",
    phone: "",
    avatar: null
});

onMounted(() => {
    if (residenceStore.selectedResidence) {
        selectedManager.value = residenceStore.selectedResidence.user_id;
        selectedResidence.value = residenceStore.selectedResidence.id;
    }
});


// // Carga inicial de residencias
// if (props.residences) {
//     residences.value = props.residences;
// }

// // Carga inicial de residencias
// if (props.buildings) {
//     buildings.value = props.buildings;
// }

const avatar = ref(null); // Para manejar la vista previa del avatar

// Estados y errores
const errors = reactive({
    email: "",
    building_id: "",
    identifier: "",
    first_name: "",
    last_name: "",
    document: "",
    phone: ""
});

// Manejo del avatar
const handleAvatar = (e) => {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = () => {
            avatar.value = reader.result; // Vista previa del archivo cargado
            form.avatar = file; // Almacena en el formulario para envío
        };
        reader.readAsDataURL(file);
    }
};

const removeAvatar = () => {
    avatar.value = null;
    form.avatar = null; // Limpia el campo
};

const hasSubmitted = ref(false);

const isFormValid = computed(() =>
    form.email !== "" &&
    form.building_id != "" &&
    form.identifier != "" &&
    form.first_name.trim() !== "" &&
    form.last_name.trim() !== "" &&
    form.phone.trim() !== "" &&
    form.document.trim() !== ""
);

const validateField = (fieldName) => {
    switch (fieldName) {
        case "email":
            errors.email = form.email === "" ? "El correo electrónico es obligatorio." : "";
            break;
        case "building_id":
            errors.building_id = form.building_id === "" ? "El edificio es obligatorio." : "";
            break;
        case "identifier":
            errors.identifier = form.identifier === "" ? "El identificador es obligatorio." : "";
            break;
        case "first_name":
            errors.first_name = form.first_name.trim() === "" ? "El nombre es obligatorio." : "";
            break;
        case "last_name":
            errors.last_name = form.last_name.trim() === "" ? "El apellido es obligatorio." : "";
            break;
        case "document":
            errors.document = form.document.trim() === "" ? "El documento de identidad es obligatorio." : "";
            break;
        case "phone":
            errors.phone = form.phone.trim() === "" ? "El teléfono es obligatorio." : "";
            break;
    }
};

const validateAll = () => {
    validateField("email");
    validateField("building_id");
    validateField("identifier");
    validateField("first_name");
    validateField("last_name");
    validateField("document");
    validateField("phone");
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
        selectedResidence.value = residenceStore.selectedResidence
            ? residenceStore.selectedResidence.id
            : "";
        buildings.value = [];
        form.building_id = "";
    } catch (error) {
        console.error("Error al cargar residencias:", error);
        residences.value = [];
        buildings.value = [];
    }
});


// Observador para la residencia seleccionada
watch(selectedResidence, async (newResidenceId) => {
    console.log('selecteResidence:', newResidenceId);
    if (!newResidenceId) {
        buildings.value = [];
        form.building_id = "";
        return;
    }
    try {
        const response = await axios.get(`/residences/${newResidenceId}/buildings/list`);
        console.log('response:', response);
        buildings.value = response.data.buildings;
        form.building_id = "";
    } catch (error) {
        console.error("Error al cargar edificios:", error);
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
    if (residenceStore.selectedResidence) {
        router.visit(`/residences/${residenceStore.selectedResidence.id}/apartments`);
    } else {
        router.visit("/apartments");
    }
};
</script>
