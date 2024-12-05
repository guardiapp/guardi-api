<script setup>
import GuestLayout from "@/Layouts/GuestLayout.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import TextInput from "@/Components/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/Layouts/AuthLayout.vue";

defineProps({
    status: {
        type: String,
    },
});

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <AuthLayout>
        <div class="flex items-center justify-center p-6 sm:p-12 md:w-1/2">
            <div class="w-full">
                <h1
                    class="mb-4 text-xl font-semibold text-gray-700 dark:text-gray-200"
                >
                    Olvido de contraseña
                </h1>
                <div
                    v-if="status"
                    class="mb-4 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    {{ status }}
                </div>
                <form @submit.prevent="submit">
                    <label class="block text-sm">
                        <span class="text-gray-700 dark:text-gray-400"
                            >Correo electrónico</span
                        >
                        <input
                            class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                            placeholder="Jane Doe"
                            id="email"
                            type="email"
                            v-model="form.email"
                            required
                            autofocus
                            autocomplete="Correo electrónico"
                        />
                        <span
                            v-show="form.errors.email"
                            class="text-xs text-red-600 dark:text-red-400"
                        >
                            {{ form.errors.email }}
                        </span>
                    </label>

                    <!-- You should use a button here, as the anchor is only used for the example  -->
                    <button
                        class="block w-full px-4 py-2 mt-4 text-sm font-medium leading-5 text-center text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Recuperar contraseña
                    </button>
                </form>
            </div>
        </div>
    </AuthLayout>
</template>
