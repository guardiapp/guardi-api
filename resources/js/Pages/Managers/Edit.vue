<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Editar Administrador
            </h2>

            <form @submit.prevent="submit">
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
                                    type="email"
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

                    </div>

                    <!-- Columna del avatar -->
                    <div class="flex flex-col items-center justify-center space-y-4">

                        <div
                            class="relative w-32 h-32 overflow-hidden rounded-full border-2 border-dashed border-gray-300"
                            :class="themeStore.dark ? 'border-gray-600' : 'border-gray-300'"
                        >
                            <img
                                v-if="avatarPreview"
                                :src="avatarPreview"
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
                    Residencias
                </h2>
                <button
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    @click="openResidenceModal"
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
                    v-if="transformedResidences.length > 0"
                    :columns="['Nombre', 'Eliminar']"
                    :data="transformedResidences"
                    :rows-per-page="5"
                    :show-pagination="false"
                >
                    <template #column-actions="{ row }">
                        <div class="flex items-center space-x-4 text-sm">
                            <button
                                @click="handleDeleteResidence(row.actions.id)"
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
        <CreateResidence
            :is-modal-open="isResidenceModalOpen"
            @close-modal="isResidenceModalOpen = false"
            :manager="form"
        />
    </MainLayout>
</template>

<script setup>
import TableTemplate from "@/Components/TableTemplate.vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { computed, reactive, ref } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useGlobalFunctions } from "@/composables/useGlobalFunctions";
import CreateResidence from "@/Components/modals/CreateResidence.vue";
import { notify } from "notiwind";

const { deleteResidence } = useGlobalFunctions();

const themeStore = useThemeStore();

// Props recibidas
const { props } = usePage();
const manager = props.manager;

// Transform data for the table
const transformedResidences = manager.residences.map((residence) => ({
    name: residence.name,
    actions: { id: residence.id },
}));

// Configuración del formulario
const form = useForm({
    _method: 'PUT',
    name: manager.name || "",
    email: manager.email || "",
    avatar: manager.avatar,
    avatar_deleted: false,
});

const avatarPreview = ref(manager.avatar ? `/storage/${manager.avatar}` : null);

const hasSubmitted = ref(false);

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




// Validar si el formulario es válido (ambos campos llenos)
const isFormValid = computed(
    () => form.name.trim() !== "" && form.email.trim() !== ""
);

// Manejar errores
const errors = reactive({
    name: "",
    email: "",
});

// Manejar el envío del formulario
const submit = () => {
    hasSubmitted.value = true;

    errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
    errors.email = form.email.trim() === "" ? "El correo electónico es obligatorio." : "";

    if (!isFormValid.value) return;

    form.post(`/managers/${manager.id}`, {
        onSuccess: () => {
            form.reset('avatar');
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Actualizado",
                text: "Manager actualizado correctamente",
            }, 4000);
        },
        onError: (err) => {
            console.error(err);
            if (err.email) {
                notify({
                    group: "error",
                    title: "Error de email",
                    text: err.email,
                }, 4000);
                errors.email = "El correo electrónico ya esta en uso"
            }
        },
    }, {
        headers: {
            "Content-Type": "multipart/form-data",
        },
    });
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/managers");
};

const isResidenceModalOpen = ref(false);

const openResidenceModal = () => {
    isResidenceModalOpen.value = true;
};

const handleDeleteResidence = (id) => {
    deleteResidence(id, () => {
        console.log("Se recargaron los datos correctamente.");
    });
};

const handleInput = (fieldName) => {
    if (hasSubmitted.value) {
        validateField(fieldName);
    }
};

const validateField = (fieldName) => {
    switch (fieldName) {
        case "email":
            errors.email = form.email === "" ? "El correo electrónico es obligatorio." : "";
            break;
        case "name":
            errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
            break;
    }
};
</script>
