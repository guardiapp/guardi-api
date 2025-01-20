<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Editar Apartamento
            </h2>
            <form @submit.prevent="submit">
                <div
                    class="grid grid-cols-1 md:grid-cols-3 gap-4 px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Columna del formulario -->
                    <div class="col-span-2 space-y-4">
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
                                    >Residencias</span
                                >
                                <select
                                    v-model="selectedResidence"
                                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
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

                        <!-- Input para Residencia -->
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
                                    class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
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
                                <input
                                    v-model="form.apartment"
                                    class="block w-full mt-1 text-sm form-input"
                                    :class="{
                                        'border-red-500': errors.apartment,
                                        ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                            !themeStore.dark,
                                        'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                            themeStore.dark,
                                    }"
                                    @input="handleInput('apartment')"
                                />
                                <p
                                    v-if="errors.apartment"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.apartment }}
                                </p>
                            </label>
                        </div>


                        <!-- Input para Documento -->
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
                                />
                                <p
                                    v-if="errors.document"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.document }}
                                </p>
                            </label>
                        </div>

                        <!-- Input para Documento -->
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
                                />
                                <p
                                    v-if="errors.phone"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.phone }}
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
                                />
                                <p
                                    v-if="errors.last_name"
                                    class="mt-1 text-xs text-red-600"
                                >
                                    {{ errors.last_name }}
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
                            <!-- Mostrar el avatar actual o previsualización -->
                            <img
                                v-if="avatarPreview"
                                :src="avatarPreview"
                                alt="Avatar preview"
                                class="w-full h-full object-cover"
                            />
                            <div
                                v-else
                                class="w-full h-full flex items-center justify-center text-gray-400"
                            >
                                Sin avatar
                            </div>
                            <input
                                type="file"
                                accept="image/*"
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                                @change="handleAvatar"
                            />
                        </div>

                        <!-- Botón para eliminar avatar -->
                        <button
                            v-if="avatarPreview"
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
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </MainLayout>
</template>

<script setup>
import { useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { computed, ref, reactive, watch } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { notify } from "notiwind";

const themeStore = useThemeStore();
const user = usePage().props.auth.user;

// Props iniciales
const { props } = usePage();
const apartment = props.apartment;
const managers = ref(props.managers || []);
const residences = ref(props.residences || []);
const buildings = ref(props.buildings || []);

const selectedManager = ref(null);
const selectedResidence = ref(null);

// Inicializar formulario
const form = useForm({
    _method: 'PUT',
    building_id: apartment.building_id,
    identifier: apartment.identifier,
    first_name: apartment.first_name,
    last_name: apartment.last_name,
    document: apartment.document,
    phone: apartment.phone,
    avatar: apartment.user.avatar,
    avatar_deleted: false,
});

const avatarPreview = ref(resident.user.avatar ? `/storage/${resident.user.avatar}` : null);

// Controladores de errores
const errors = reactive({
    building_id: "",
    apartment: "",
    first_name: "",
    last_name: "",
    document: "",
    phone: ""
});

watch(
    () => form.building_id,
    (newBuildingId) => {
        if (!newBuildingId) return;

        // Encuentra la residencia relacionada al building_id
        const relatedResidence = residences.value.find((residence) =>
            buildings.value.some(
                (building) =>
                    building.id === newBuildingId &&
                    building.residence_id === residence.id
            )
        );

        // Actualiza el selectedResidence si hay un cambio necesario
        if (
            relatedResidence &&
            selectedResidence.value !== relatedResidence.id
        ) {
            selectedResidence.value = relatedResidence.id;

            // Encuentra el manager relacionado con la residencia seleccionada
            const relatedManager = managers.value.find(
                (manager) => manager.id === relatedResidence.user_id
            );

            if (
                relatedManager &&
                selectedManager.value !== relatedManager.id
            ) {
                selectedManager.value = relatedManager.id;
            }
        }
    },
    { immediate: true } // Escuchar desde el inicio
);

const hasSubmitted = ref(false);

const isFormValid = computed(() =>
    form.email !== "" &&
    form.building_id != "" &&
    form.apartment != "" &&
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
            errors.building_id = form.building_id === "" ? "El edificio es obligatoria." : "";
            break;
        case "apartment":
            errors.apartment = form.apartment === "" ? "El apartamento es obligatorio." : "";
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
    validateField("apartment");
    validateField("first_name");
    validateField("last_name");
    validateField("document");
    validateField("phone");
};

// Observa los cambios en el manager seleccionado si es Admin
// Ver cambios en `selectedManager` y carga dinámica de residencias
watch(
    selectedManager,
    async (newManagerId) => {
        // Solo ejecutar la lógica si el usuario es Admin
        if (user.type !== "Admin") return;

        if (!newManagerId) {
            residences.value = [];
            buildings.value = [];
            selectedResidence.value = "";
            form.building_id = "";
            return;
        }

        try {
            // Obtener residencias asociadas al manager
            const response = await axios.get(`/managers/${newManagerId}/residences`);
            residences.value = response.data.residences;

            // Si ya hay una residencia seleccionada, filtrar edificios
            if (selectedResidence.value) {
                const associatedResidence = residences.value.find(
                    (residence) => residence.id === selectedResidence.value
                );

                if (associatedResidence) {
                    const buildingsResponse = await axios.get(
                        `/residences/${associatedResidence.id}/buildings`
                    );
                    buildings.value = buildingsResponse.data.buildings;
                } else {
                    buildings.value = [];
                }
            }
        } catch (error) {
            console.error("Error al cargar residencias:", error);
            residences.value = [];
            buildings.value = [];
        }
    },
    { immediate: true }
);

watch(
    selectedResidence,
    async (newResidenceId) => {
        if (!newResidenceId) {
            buildings.value = [];
            form.building_id = "";
            return;
        }

        try {
            const response = await axios.get(
                `/residences/${newResidenceId}/buildings`
            );
            buildings.value = response.data.buildings;
        } catch (error) {
            console.error("Error al cargar edificios:", error);
        }
    }
);


// Manejo del avatar
const handleAvatar = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file); // Actualiza la vista previa
        form.avatar_deleted = false; // Limpia la bandera de eliminación
    }
};

const removeAvatar = () => {
    form.avatar = null; // No enviar archivo en el formulario
    form.avatar_deleted = true; // Marcar como eliminado
    avatarPreview.value = null; // Limpiar previsualización
};

const submit = () => {
    hasSubmitted.value = true;
    validateAll();

    if (!isFormValid.value) return;

    // Incluir el nombre si es necesario (puedes eliminar esto si el backend no lo usa)
    form.name = form.first_name;

    form.post(`/residents/${resident.id}`, {
        // Incluye la opción `preserveState` si quieres mantener el estado del formulario en caso de éxito
        onSuccess: () => {
            form.reset('avatar'); // No resetees la vista previa del avatar
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Actualizado",
                text: "Vigilante actualizado correctamente",
            }, 4000);
        },
        onError: (errors) => {
            console.error(errors);
            notify({
                group: "error",
                title: "Error",
                text: "Error al actualizar el vigilante",
            }, 4000);
        },
    }, {
        headers: {
            "Content-Type": "multipart/form-data", // Inertia se encargará de este detalle automáticamente
        },
    });
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/residents");
};
</script>
