<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

defineProps({
    mustVerifyEmail: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const user = usePage().props.auth.user;

const form = useForm({
    name: user.name,
    email: user.email,
    type: user.type,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Información del Perfil
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Actualice la información de su perfil de cuenta y dirección del
                correo electrónico.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <!-- Input para Nombre -->
            <div class="text-sm">
                <label class="block text-sm">
                    <span
                        :class="
                            themeStore.dark ? 'text-gray-400' : 'text-gray-700'
                        "
                        >Nombre</span
                    >
                    <input
                        v-model="form.name"
                        id="name"
                        type="text"
                        class="block w-full mt-1 text-sm form-input"
                        :class="{
                            'border-red-500': form.errors.name,
                            ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                !themeStore.dark,
                            'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                themeStore.dark,
                        }"
                        placeholder="Cambie el nombre"
                    />
                    <p
                        v-if="form.errors.name"
                        class="mt-1 text-xs text-red-600"
                    >
                        {{ form.errors.name }}
                    </p>
                </label>
            </div>

            <!-- Input para Correo electrónico -->
            <div class="mt-4 text-sm">
                <label class="block text-sm">
                    <span
                        :class="
                            themeStore.dark ? 'text-gray-400' : 'text-gray-700'
                        "
                        >Correo electrónico</span
                    >
                    <input
                        v-model="form.email"
                        id="email"
                        class="block w-full mt-1 text-sm form-input"
                        :class="{
                            'border-red-500': form.errors.email,
                            'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                !themeStore.dark,
                            'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                themeStore.dark,
                        }"
                        placeholder="Correo electrónico"
                    />
                    <p
                        v-if="form.errors.email"
                        class="mt-1 text-xs text-red-600"
                    >
                        {{ form.errors.email }}
                    </p>
                </label>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    Su dirección de correo electrónico no ha sido verificada.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:text-gray-400 dark:hover:text-gray-100 dark:focus:ring-offset-gray-800"
                    >
                        Haga click aqui para re enviar el correo de
                        verificación.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600 dark:text-green-400"
                >
                    Un nuevo enlace de verificación ha sido enviado a su
                    dirreción de correo electrónico.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton
                    :disabled="form.processing"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                    >Guardar</PrimaryButton
                >

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600 dark:text-gray-400"
                    >
                        Guardado.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
