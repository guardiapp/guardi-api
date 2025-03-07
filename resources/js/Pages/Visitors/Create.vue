<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                v-if="residenceStore.selectedResidence"
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { url: '/residences', label: 'Residencias' },
                    {
                        url: `/residences/${residence.id}`,
                        label: residence.name,
                    },
                    {
                        url: `/residences/${residence.id}/buildings`,
                        label: 'apartments',
                    },
                    { label: 'Crear', isCurrent: true },
                ]"
            />
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                {{ props.visitor ? 'Actualizar Visitante' : 'Nuevo Visitante' }}
            </h2>
            <form @submit.prevent="submit">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Columna del formulario -->
                    <div class="col-span-3 space-y-4">
                        <!-- Input para Apartamento -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Apartamento</span
                                >
                                <select
                                    v-model="form.apartment_id"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                >
                                    <option
                                        v-for="iterable in apartaments"
                                        :key="iterable.id"
                                        :value="iterable.id"
                                    >
                                        {{ iterable.identifier }}
                                    </option>
                                </select>
                            </label>
                        </div>

                        <!-- Input para Documento del visitante -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Documento</span
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

                        <!-- Input para Documento del Nombre -->
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

                        <!-- Input para Apellido del visitante -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Apellido</span
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

                        <!-- Input para Si esta activo o no -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    class="mx-4"
                                    >Activo</span
                                >
                                <input
                                    v-model="form.active"
                                    type="checkbox"
                                    :class="{
                                        'border-red-500': errors.active,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('active')"
                                />
                                <p
                                    v-if="errors.active"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.active }}
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
import BreadcrumbTemplate from "@/Components/BreadcrumbTemplate.vue";
import { computed, reactive, ref, watch, onMounted } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { notify } from "notiwind";

const user = usePage().props.auth.user;

if (user.type != "Admin" && user.type != "Manager") {
    // esta vista solo puede renderizarse en caso de ser uno de estos dos usuarios
    router.visit('/visitors');
}

const themeStore = useThemeStore();
const residenceStore = useResidenceStore();

// Props recibidas
const { props } = usePage();
const apartaments = ref([]);
const residences = ref(props.residences || []);
const buildings = ref(props.buildings || []);
const selectedManager = ref("");
const selectedResidence = ref("");
const residence = computed(() => residenceStore.selectedResidence);

// Configuración del formulario
const form = useForm({
    apartment_id: "",
    document: "",
    first_name: "",
    last_name: "",
    active: "",
});

onMounted(() => {
    findApartaments();
    document.title = props.visitor ?  'Actualizar visitante' : "Crear visitante";
    if(props.visitor) {
        form.first_name = props.visitor.first_name;
        form.last_name = props.visitor.last_name;
        form.apartment_id = props.visitor.apartment_id;
        form.active = props.visitor.active == 1 ? true : false;
        form.document = props.visitor.document;
    }
});

// Estados y errores
const errors = reactive({
    first_name: "",
    last_name: "",
    document: "",
    apartment_id: "",
});
const hasSubmitted = ref(false);

const isFormValid = computed(
    () =>
        form.first_name.trim() !== "" &&
        form.last_name.trim() !== "" &&
        form.apartment_id != "" &&
        form.document.trim() !== ""
);

const validateField = (fieldName) => {
    switch (fieldName) {
        case "apartment_id":
            errors.apartment_id =
                form.apartment_id === "" ? "El apartamento es obligatorio." : "";
            break;
        case "first_name":
            errors.first_name =
                form.first_name.trim() === ""
                    ? "El nombre es obligatorio."
                    : "";
            break;
        case "last_name":
            errors.last_name =
                form.last_name.trim() === ""
                    ? "El apellido es obligatorio."
                    : "";
            break;
        case "document":
            errors.document =
                form.document.trim() === ""
                    ? "El documento de identidad es obligatorio."
                    : "";
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
        const response = await axios.get(
            `/managers/${newManagerId}/residences`
        );
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
    console.log("selecteResidence:", newResidenceId);
    if (!newResidenceId) {
        buildings.value = [];
        form.building_id = "";
        return;
    }
    try {
        const response = await axios.get(
            `/residences/${newResidenceId}/buildings/list`
        );
        console.log("response:", response);
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
    form.active = form.active ? form.active : false;
    let endpoint = props.visitor ? `/visitors/` + props.visitor?.id : `/visitors`;
    await form[props.visitor ? 'put' : 'post'](endpoint, {
        onSuccess: (response) => {
            console.log(response);
            if(!props.visitor?.id) form.reset();
            hasSubmitted.value = false;
            notify(
                {
                    group: "success",
                    title: "Guardado",
                    text: props.visitor ? "Visitante Actualizado" : "Nuevo visitante creado",
                },
                4000
            );
        },
        onError: (errors) => {
            console.log(errors);
        },
    });
};

const findApartaments = async () => {
    const response = await axios.get(`/apartaments/findAll?managerUserId=${user.type == 'Manager' ? user.id : ''}`);
    apartaments.value = response.data;
};

const handleInput = (fieldName) => {
    if (hasSubmitted.value) {
        validateField(fieldName);
    }
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/visitors");
};

</script>
