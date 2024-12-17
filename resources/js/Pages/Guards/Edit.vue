<template>
  <MainLayout>
    <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold"
        :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
      >
        Editar Residencia
      </h2>

      <form @submit.prevent="submit">
        <div class="px-4 py-3 mb-8 rounded-lg shadow-md"
        :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
        >
          <!-- Input para Nombre -->
          <div class="text-sm">
            <label class="block text-sm">
              <span :class="themeStore.dark ? 'text-gray-400' : 'text-gray-700'"
              >Nombre</span>
              <input
                v-model="form.name"
                class="block w-full mt-1 text-sm form-input"
                :class="{ 'border-red-500': errors.name,
                    ' focus:border-purple-400 focus:outline-none focus:shadow-outline-purple': !themeStore.dark,
                    'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ': themeStore.dark
                }"
                placeholder="Nombre de la residencia"
              />
              <p v-if="errors.name" class="mt-1 text-xs text-red-600">
                {{ errors.name }}
              </p>
            </label>
          </div>

          <!-- Input para Dirección -->
          <div class="mt-4 text-sm">
            <label class="block text-sm">
                <span :class="themeStore.dark ? 'text-gray-400' : 'text-gray-700'">Dirección</span>
              <input
                v-model="form.address"
                class="block w-full mt-1 text-sm form-input"
                :class="{
                    'border-red-500': errors.address,
                    'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple': !themeStore.dark,
                    'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ': themeStore.dark
                }"
                placeholder="Dirección de la residencia"
              />
              <p v-if="errors.address" class="mt-1 text-xs text-red-600">
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
            :disabled="!isFormValid"
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
import { computed, reactive } from "vue";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

// Props recibidas
const { props } = usePage();
const residence = props.residence;

// Configuración del formulario
const form = useForm({
  name: residence.name || "",
  address: residence.address || "",
});

// Validar si el formulario es válido (ambos campos llenos)
const isFormValid = computed(() => form.name.trim() !== "" && form.address.trim() !== "");

// Manejar errores
const errors = reactive({
  name: "",
  address: "",
});

// Manejar el envío del formulario
const submit = () => {
  errors.name = form.name.trim() === "" ? "El nombre es obligatorio." : "";
  errors.address = form.address.trim() === "" ? "La dirección es obligatoria." : "";

  if (!isFormValid.value) return;

  form.put(`/residences/${residence.id}`, {
    onSuccess: () => {
      form.reset();
      router.visit("/residences"); // Redirige al listado
    },
  });
};

// Cancelar y volver al listado
const cancel = () => {
  router.visit("/residences");
};
</script>
