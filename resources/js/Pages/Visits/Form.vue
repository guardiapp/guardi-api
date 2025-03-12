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
                {{ props.visit ? 'Actualizar Visita' : 'Nueva Visita' }}
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
                                    @change="findVisitor()"
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

                        <!-- Input para Visitante -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Visitante</span
                                >
                                <select
                                    v-model="form.visitor_id"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                >
                                    <option
                                        v-for="iterable in visitors"
                                        :key="iterable.id"
                                        :value="iterable.id"
                                    >
                                        {{ `${iterable.id} - ${iterable.first_name} ${iterable.last_name}` }}
                                    </option>
                                </select>
                            </label>
                        </div>

                        <!-- Input para comentarios de la visita -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Comentarios</span
                                >
                                <textarea
                                    v-model="form.remarks"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.remarks,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('remarks')"
                                />
                                <p
                                    v-if="errors.remarks"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.remarks }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para with_stay (con estadia) boolean -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    class="mx-4"
                                    >Con estadía</span
                                >
                                <input
                                    v-model="form.with_stay"
                                    type="checkbox"
                                    :class="{
                                        'border-red-500': errors.with_stay,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('with_stay')"
                                />
                                <p
                                    v-if="errors.with_stay"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.with_stay }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para fecha de la visita -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Fecha</span
                                >
                                <input
                                    v-model="form.visit_date"
                                    type="date"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.visit_date,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('visit_date')"
                                />
                                <p
                                    v-if="errors.visit_date"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.visit_date }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para fecha de expiracion de la visita -->
                        <div class="text-sm my-5" v-if="form.with_stay">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Fecha de expiración</span
                                >
                                <input
                                    v-model="form.expiration_date"
                                    type="date"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.expiration_date,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('expiration_date')"
                                />
                                <p
                                    v-if="errors.expiration_date"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.expiration_date }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para hora de entrada del visitante -->
                        <div class="text-sm my-5">
                            <label class="block text-sm">
                                <span
                                    :class="
                                        themeStore.dark
                                            ? 'text-gray-400'
                                            : 'text-gray-700'
                                    "
                                    >Hora de entrada</span
                                >
                                <input
                                    type="time"
                                    v-model="form.entry_time"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.entry_time,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('entry_time')"
                                />
                                <p
                                    v-if="errors.entry_time"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.entry_time }}
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
    router.visit('/visits');
}

const isEmpty = (value) => !value || value == '' || value == null || value == undefined;

const themeStore = useThemeStore();
const residenceStore = useResidenceStore();

// Props recibidas
const { props } = usePage();
const apartaments = ref([]);
const visitors = ref([]);
const residences = ref(props.residences || []);
const buildings = ref(props.buildings || []);
const selectedManager = ref("");
const selectedResidence = ref("");
const residence = computed(() => residenceStore.selectedResidence);

// Configuración del formulario
const form = useForm({
    apartment_id:null,
    visitor_id:null,
    remarks: null,
    visit_date: null,
    expiration_date: null,
    entry_time: null,
    with_stay: null
});

onMounted(async () => {
    findApartaments();
    document.title = props.visit ?  'Actualizar visita' : "Crear visita";
    if(props.visit) {
        form.apartment_id = props.visit.apartment_id;
        await findVisitor();
        form.visitor_id = props.visit.visitor_id;
        form.remarks = props.visit.remarks;
        form.visit_date = props.visit.visit_date;
        form.expiration_date = props.visit.expiration_date;
        form.entry_time = props.visit.entry_time;
        form.with_stay = props.visit.with_stay == 1 ? true : false;
    }
});

// Estados y errores
const errors = reactive({
    apartment_id:"",
    visitor_id:"",
    remarks: "",
    visit_date: "",
    expiration_date: "",
    entry_time: "",
    with_stay: ""
});
const hasSubmitted = ref(false);

const isFormValid = computed(
    () => !isEmpty(form.apartment_id) && !isEmpty(form.visit_date) && !isEmpty(form.visitor_id) && !isEmpty(form.entry_time)

);

const validateField = (fieldName) => {
    switch (fieldName) {
        case "apartment_id":
            errors.apartment_id =
                isEmpty(form.apartment_id) ? "El apartamento es obligatorio." : "";
            break;

        case "visit_date":
            errors.visit_date = isEmpty(form.visit_date) ? "La fecha de la visita es requerida." : "";
            break;

        case "visitor_id":
            errors.visitor_id = isEmpty(form.visitor_id) ? 'El visitante es obligatorio' : '';
            break;

        case "entry_time":
            errors.entry_time = isEmpty(form.entry_time) ? 'La hora de entrada es obligatorio' : '';
            break;
    }
};

const validateAll = () => {
    validateField("apartment_id");
    validateField("visit_date");
    validateField("visitor_id");
    validateField("entry_time");
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

    form.with_stay = form.with_stay ? form.with_stay : false;
    form.expiration_date = form.expiration_date ?? null;

    if (!isFormValid.value) return;

    let endpoint = props.visit ? `/visits/` + props.visit?.id : `/visits`;
    await form[props.visit ? 'put' : 'post'](endpoint, {
        onSuccess: (response) => {
            console.log(response);
            if(!props.visit?.id) form.reset();
            hasSubmitted.value = false;
            notify(
                {
                    group: "success",
                    title: "Guardado",
                    text: props.visit ? "Visita Actualizada" : "Nueva visita creada",
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

const findVisitor = async () => {
    const response = await axios.get(`/visitors/find-all?apartment_id=${form.apartment_id ?? ''}`);
    visitors.value = response.data;
    form.visitor_id = null;
}

const handleInput = (fieldName) => {
    if (hasSubmitted.value) {
        validateField(fieldName);
    }
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/visits");
};

</script>
