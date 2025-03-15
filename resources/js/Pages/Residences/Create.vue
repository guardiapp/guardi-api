<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Nueva Residencia
            </h2>

            <form @submit.prevent="submit">
                <div
                    class="px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Input para Administradores -->
                    <!--div class="text-sm my-5" v-if="managers.length">
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
                                v-model="form.user_id"
                                class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                                @input="clearError('user_id')"
                            >
                                <option v-for="manager in managers" :key="manager.id" :value="manager.id">
                                    {{ manager.name }}
                                </option>
                            </select>
                            <p
                                v-if="errors.user_id"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.user_id }}
                            </p>
                        </label>
                    </div-->


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
                                placeholder="Nombre de la residencia"
                                @input="clearError('name')"
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
                    <div class="text-sm my-5">
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
                                @input="clearError('address')"
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
const themeStore = useThemeStore();
import { notify } from "notiwind";

const { props } = usePage();

// Si es Admin se recibirán los managers
const managers = props.managers || [];

// Configuración del formulario
const form = useForm({
    name: "",
    address: "",
    //user_id: managers.length ? null : props.auth.user.id,
});

//Validar si el formulario es válido (ambos campos llenos)
const isFormValid = computed(
    () => form.name.trim() !== "" && form.address.trim() !== "" //&& form.user_id !== null
);

// Manejar errores
const errors = reactive({
    //user_id: "",
    name: "",
    address: "",
});


const hasSubmitted = ref(false)

// Manejar el envío del formulario
const submit = () => {
    //errors.user_id = form.user_id === null ? "El Manager es obligatorio." : "";
    errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
    errors.address =
        form.address.trim() === "" ? "La dirección es obligatoria." : "";

    if (!isFormValid.value) return;

    hasSubmitted.value = true

    form.post(`/residences`, {
        onSuccess: () => {
            form.reset();
            notify(
                {
                    group: "success",
                    title: "Guardado",
                    text: "La nueva residencia ha sido agregada",
                },
                4000
            );
            hasSubmitted.value = false;
        },
        onError: (errorResponse) => {
            if (errorResponse.name) errors.name = errorResponse.name
            if (errorResponse.address) errors.address = errorResponse.address
        }
    });
};

const clearError = (error) => {
    if (error == 'user_id') errors.user_id = '';
    if (error == 'name') errors.name = '';
    if (error == 'address') errors.address = '';
}

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/residences");
};
</script>
