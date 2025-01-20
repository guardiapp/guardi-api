<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
import { ref } from "vue";
import { notify } from "notiwind";
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
    _method: 'PATCH',
    name: user.name,
    email: user.email,
    type: user.type,
    avatar: user.avatar || null,
    avatar_deleted: false,
});

const formatType = (type) => {
    if (type == "Admin") return "Super Administrador";
    if (type == "Manager") return "Administrador";
    if (type == "Guard") return "Vigilante";
    if (type == "Resident") return "Residente";
    return null;
};

const hasSubmitted = ref(false);

const updateProfile = () => {
    hasSubmitted.value = true;

    form.post(route("profile.update"),  {
        onSuccess: () => {
            if (form.avatar && !form.avatar_deleted) {
                const newAvatarUrl = URL.createObjectURL(form.avatar);
                themeStore.setAvatar(newAvatarUrl);
            } else if (form.avatar_deleted) {
                themeStore.clearAvatar();
            }
            form.reset('avatar');
            hasSubmitted.value = false;
            notify({
                group: "success",
                title: "Actualizado",
                text: "Usuario actualizado correctamente",
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

const avatarPreview = ref(user.avatar ? `/storage/${user.avatar}` : null);

const handleAvatar = (event) => {
    const file = event.target.files[0];
    if (file) {
        form.avatar = file;
        avatarPreview.value = URL.createObjectURL(file);
        form.avatar_deleted = false;
    }
};

const removeAvatar = () => {
    form.avatar = null; // No enviar archivo en el formulario
    form.avatar_deleted = true; // Marcar como eliminado
    avatarPreview.value = null; // Limpiar previsualización
};
</script>

<template>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Información del Perfil
        </h2>

        <p
            v-if="user.type == 'Admin'"
            class="mt-1 text-sm text-gray-600 dark:text-gray-400"
        >
            Actualice la información de su perfil de cuenta, imagen y dirección del
            correo electrónico.
        </p>
        <p v-else class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Información de su perfil de cuenta. Puede actualizar su nombre e imagen.
        </p>
    </header>

    <form @submit.prevent="updateProfile">
        <div
            class="grid grid-cols-1 md:grid-cols-3 gap-4 py-3 mb-8 rounded-lg"
        >
            <div class="col-span-2 space-y-4">
                <p
                    class="mt-5 text-sm text-gray-600 dark:text-gray-400 font-bold"
                >
                    Rol:
                    <span
                        class="text-gray-600 dark:text-gray-400 font-medium"
                        >{{ formatType(user.type) }}</span
                    >
                </p>
                <div>
                    <p
                        class="mt-5 text-sm text-gray-600 dark:text-gray-400 font-bold"
                        v-if="user.type != 'Admin'"
                    >
                        Correo electrónico:
                        <span
                            class="text-gray-600 dark:text-gray-400 font-medium"
                            >{{ user.email }}</span
                        >
                    </p>
                </div>

                <!-- Input para Nombre -->
                <div class="text-sm">
                    <label class="block text-sm">
                        <span
                            :class="
                                themeStore.dark
                                    ? 'text-gray-400'
                                    : 'text-gray-700'
                            "
                            >Nombre
                        </span>
                        <input
                            v-model="form.name"
                            id="name"
                            type="text"
                            class="block  mt-1 text-sm form-input"
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
                <div class="mt-4 text-sm" v-if="user.type == 'Admin'">
                    <label class="block text-sm">
                        <span
                            :class="
                                themeStore.dark
                                    ? 'text-gray-400'
                                    : 'text-gray-700'
                            "
                            >Correo electrónico
                        </span>
                        <input
                            v-model="form.email"
                            id="email"
                            class="block mt-1 text-sm form-input"
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
                        Su dirección de correo electrónico no ha sido
                        verificada.
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
            </div>

            <div class="flex flex-col items-center justify-center space-y-4 mt-6">
                <div
                    class="relative w-32 h-32 overflow-hidden rounded-full border-2 border-dashed border-gray-300"
                    :class="
                        themeStore.dark ? 'border-gray-600' : 'border-gray-300'
                    "
                >
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
        <div class="flex items-center gap-4">
            <PrimaryButton
                :disabled="form.processing"
                class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                >Guardar
            </PrimaryButton>

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
</template>
