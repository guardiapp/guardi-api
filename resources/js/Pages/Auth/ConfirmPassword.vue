<script setup>
import { Head, useForm } from '@inertiajs/vue3';

import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

const form = useForm({
    password: '',
});

const submit = () => {
    form.post(route('password.confirm'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <AuthLayout>
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600"
                >
                    {{ status }}
                </div>
                <h1
                    class="mb-4 text-xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Confirmar Contraseña
                </h1>
                <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                    Esta es una zona segura de la aplicación. Confirme su contraseña antes de continuar.
                </div>

                <form @submit.prevent="submit">
                    <label class="block text-sm">
                        <span :class="themeStore.dark ? 'text-gray-400' : 'text-gray-700'"
                            >Contraseña</span
                        >
                        <input
                            id="password"
                            type="password"
                            v-model="form.password"
                            required
                            autofocus
                            placeholder="Ingrese contraseña actual"
                            class="block w-full mt-1 text-sm form-input"
                            :class="themeStore.dark ? 'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray' : 'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple'"
                        />
                        <span
                            v-show="form.errors.password"
                            class="text-xs"
                            :class="themeStore.dark ? 'text-red-400' : ' text-red-600'"
                        >
                            {{ form.errors.password }}
                        </span>
                    </label>

                    <!-- You should use a button here, as the anchor is only used for the example  -->
                    <button
                        :class="{ 'opacity-25': form.processing }"
                        class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                        :disabled="form.processing"
                    >
                        Confirmar
                    </button>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>
