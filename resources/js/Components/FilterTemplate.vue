<template>
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <!-- Fila de filtros -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <!-- Renderizar dinámicamente inputs habilitados -->
            <template v-for="(isEnabled, key) in filtersEnabled" :key="key">
                <label v-if="isEnabled" class="flex-1 min-w-[150px]">
                    <span class="text-gray-700 dark:text-gray-400">
                        {{ fieldLabels[key] }}
                    </span>

                    <!-- Manejo especial de fecha y fecha con hora -->
                    <input
                        v-if="key === 'expiration_date' || key === 'visit_date'"
                        type="date"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        v-model="filters[key]"
                        @blur="emitFilters"
                    />

                    <!-- Campos estándar -->
                    <input
                        v-else-if="key !== 'active'"
                        :type="key === 'email' ? 'email' : 'text'"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-input"
                        v-model="filters[key]"
                        @blur="emitFilters"
                        @keyup.enter="emitFilters"
                        :placeholder="`Filtrar por ${fieldLabels[key].toLowerCase()}`"
                    />

                    <!-- Select para el campo Activo -->
                    <select
                        v-else
                        v-model="filters.active"
                        class="block w-full mt-1 text-sm dark:border-gray-600 dark:bg-gray-700 focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:text-gray-300 dark:focus:shadow-outline-gray form-select"
                        @change="emitFilters"
                    >
                        <option :value="null">Todos</option>
                        <option :value="1">Sí</option>
                        <option :value="0">No</option>
                    </select>
                </label>
            </template>

            <!-- Botón de limpiar filtros -->
            <div class="min-w-[150px] mt-6">
                <Link
                    :href="currentUrl"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple"
                >
                    Limpiar Filtros
                </Link>
            </div>
        </div>
    </div>
</template>


<script setup>
import { reactive } from "vue";
import { Link } from "@inertiajs/vue3";

// Props
const props = defineProps({
    initialFilters: {
        type: Object,
        default: () => ({}),
    },
    filtersEnabled: {
        type: Object,
        default: () => ({}),
    },
    fieldLabels: {
        type: Object,
        default: () => ({
            name: "Nombre",
            address: "Dirección",
            manager: "Administrador",
            document: "Documento",
            email: "Correo Electrónico",
            active: "Activo",
            identifier: "Identificador",
            visitor_name: "Visitante",
            resident_name: "Residente",
            building_name: "Edificio",
            apartment: "Apartamento",
            expiration_date: "Fecha de vencimiento",
            visit_date: "Fecha y hora de visita"
        }),
    },
    currentUrl: {
        type: String,
        default: null,
    },
});

// Reactive filters
const filters = reactive({ ...props.initialFilters });

// Emit filtered results
const emit = defineEmits(["update:filters"]);
const emitFilters = () => {
    emit("update:filters", { ...filters });
};
</script>
