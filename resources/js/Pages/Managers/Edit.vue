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
                    class="px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <!-- Input para Nombre -->
                    <div class="text-sm">
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
                                placeholder="Nombre del administrador del condominio"
                            />
                            <p
                                v-if="errors.name"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.name }}
                            </p>
                        </label>
                    </div>

                    <!-- Input para Correo electrónico -->
                    <div class="mt-4 text-sm">
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
                                    'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                        !themeStore.dark,
                                    'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                        themeStore.dark,
                                }"
                                placeholder="Correo"
                            />
                            <p
                                v-if="errors.email"
                                class="mt-1 text-xs text-red-600"
                            >
                                {{ errors.email }}
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
                        :disabled="!isFormValid"
                        class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        Guardar
                    </button>
                </div>
            </form>
        </div>

        <div class="container px-6 mx-auto grid">
            <h2
                class="my-6 text-2xl font-semibold"
                :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
            >
                Residencias
            </h2>

            <div
                class="px-4 py-3 mb-8 rounded-lg shadow-md"
                :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
            >
            <TableTemplate
                :columns="['Nombre', 'Eliminar']"
                :data="transformedResidences"
                :rows-per-page="5"
            >
                <template #column-actions="{ row }">
                    <div class="flex items-center space-x-4 text-sm">
                        <button
                            @click="deleteResidence(row.actions.id)"
                            class="flex items-center justify-between px-2 py-2 text-sm font-medium leading-5 rounded-lg focus:outline-none focus:shadow-outline-gray"
                            :class="themeStore.dark ? 'text-gray-400' : 'text-purple-600'"
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
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import TableTemplate from "@/Components/TableTemplate.vue";
import { useForm, usePage, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import { computed, reactive } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useGlobalFunctions } from "@/composables/useGlobalFunctions";
import {useToast} from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-sugar.css';

const $toast = useToast();


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
    name: manager.name || "",
    email: manager.email || "",
});

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
    errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
    errors.email =
        form.email.trim() === ""
            ? "El correo electónico es obligatorio."
            : "";

    if (!isFormValid.value) return;

    form.put(`/managers/${manager.id}`, {
        onSuccess: () => {
            form.reset();
            //$toast.success('You did it!');
            //router.visit("/managers");
        },
    });
};

// Cancelar y volver al listado
const cancel = () => {
    router.visit("/managers");
};
</script>
