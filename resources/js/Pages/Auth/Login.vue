<script setup>
import { Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";
import { useThemeStore } from "@/stores/themeStore";
import { onMounted } from "vue";
const themeStore = useThemeStore();

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

// onMounted(()=>{
//     window.location.reload()
// });

const form = useForm({
    email: "",
    password: "",
    remember: false,
});

const submit = () => {
    form.post(route("login"), {
        onFinish: () => form.reset("password"),
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
                    Acceso
                </h1>
                <form @submit.prevent="submit">
                    <label class="block text-sm">
                        <span :class="themeStore.dark ? 'text-gray-400' : 'text-gray-700'"
                            >Correo electrónico</span
                        >
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="correo elecrónico"
                            class="block w-full mt-1 text-sm form-input"
                            :class="themeStore.dark ? 'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray' : 'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple'"
                            placeholder="correo@ejemplo.com"
                        />
                        <span
                            v-show="form.errors.email"
                            class="text-xs"
                            :class="themeStore.dark ? 'text-red-400' : ' text-red-600'"
                        >
                            {{ form.errors.email }}
                        </span>
                    </label>
                    <label class="block mt-4 text-sm">
                        <span :class="themeStore.dark ? 'text-gray-400' : ' text-gray-700'"
                            >Contraseña</span
                        >
                        <input
                            id="password"
                            v-model="form.password"
                            required
                            autocomplete="contraseña actual"
                            class="block w-full mt-1 text-sm form-input"
                            :class="themeStore.dark ? 'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray' : 'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple'"
                            placeholder="***************"
                            type="password"
                        />
                        <span
                            v-show="form.errors.password"
                            class="text-xs"
                            :class="themeStore.dark ? 'text-gray-400' : ' text-gray-600'"
                        >
                            {{ form.errors.password }}
                        </span>
                    </label>

                    <!-- You should use a button here, as the anchor is only used for the example  -->
                    <button
                        :class="{ 'opacity-25': form.processing }"
                        class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                        href="../index.html"
                        :disabled="form.processing"
                    >
                        Acceder
                    </button>
                </form>

                <!-- <hr class="my-8" /> -->
                <p class="mt-4">
                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="text-sm font-medium hover:underline"
                        :class="themeStore.dark ? 'text-purple-400' : 'text-purple-600'"
                    >
                        ¿Olvidaste tu contraseña?
                    </Link>
                </p>
            </div>
        </div>
    </AuthLayout>
</template>
