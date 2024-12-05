<script setup>
import { useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

const form = useForm({
    name: "",
    email: "",
    password: "",
    password_confirmation: "",
});

const submit = () => {
    form.post(route("register"), {
        onFinish: () => form.reset("password", "password_confirmation"),
    });
};
</script>

<template>
    <AuthLayout>
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
                <h1
                    class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
                >
                    Crear cuenta
                </h1>

                <form @submit.prevent="submit">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400"
                            >Nombre</span
                        >
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Jane Doe"
                            id="name"
                            type="text"
                            v-model="form.name"
                            required
                            autofocus
                            autocomplete="nombre"
                        />
                        <span
                            v-show="form.errors.name"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.name }}
                        </span>
                    </label>
                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400"
                            >Correo electrónico</span
                        >
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="correo@ejemplo.com"
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autocomplete="email"
                        />
                        <span
                            v-show="form.errors.email"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.email }}
                        </span>
                    </label>
                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400"
                            >Contraseña</span
                        >
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="***************"
                            type="password"
                            id="password"
                            v-model="form.password"
                            required
                            autocomplete="password"
                        />
                        <span
                            v-show="form.errors.password"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.password }}
                        </span>
                    </label>
                    <label class="block mt-4 text-sm">
                        <span class="text-gray-700 dark:text-gray-400">
                            Confirmar contraseña
                        </span>
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="***************"
                            type="password"
                            id="password_confirmation"
                            v-model="form.password_confirmation"
                            required
                            autocomplete="new-password"
                        />
                        <span
                            v-show="form.errors.password_confirmation"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.password_confirmation }}
                        </span>
                    </label>

                    <!-- <div class="flex mt-6 text-sm">
                    <label class="flex items-center dark:text-gray-400">
                        <input
                            type="checkbox"
                            class="text-purple-600 form-checkbox focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray"
                        />
                        <span class="ml-2">
                            I agree to the
                            <span class="underline">privacy policy</span>
                        </span>
                    </label>
                </div> -->

                    <!-- You should use a button here, as the anchor is only used for the example  -->
                    <button
                        class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                    >
                        Registrar
                    </button>
                </form>

                <p class="mt-4">
                    <a
                        class="text-sm font-medium text-purple-600 dark:text-purple-400 hover:underline"
                        :href="route('login')"
                    >
                        ¿Tiene cuenta? Acceder
                    </a>
                </p>
            </div>
        </div>
    </AuthLayout>
</template>
