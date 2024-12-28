<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Nuevo Administrador
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
import { computed, reactive, ref } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { notify } from "notiwind";
const themeStore = useThemeStore();

const user = usePage().props.auth.user;

// Props recibidas
const { props } = usePage();

// Configuración del formulario
const form = useForm({
    email: "",
    name: "",
    avatar: null
});

const avatar = ref(null); // Para manejar la vista previa del avatar

// Estados y errores
const errors = reactive({
    email: "",
    name: "",
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
    form.email.trim() !== "" &&
    form.name.trim() !== ""
);

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

const validateAll = () => {
    validateField("email");
    validateField("name");
};

const submit = () => {
    hasSubmitted.value = true;
    validateAll();

    if (!isFormValid.value) return;

    form.post(`/managers`, {
        onSuccess: () => {
            form.reset();
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Guardado",
                text: "Nuevo Administrador creado",
            }, 4000);
        },
        onError: (errors) => {
            if (errors.email) {
                notify({
                    group: "error",
                    title: "Error",
                    text: errors.email,
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
    router.visit("/managers");
};
</script>
