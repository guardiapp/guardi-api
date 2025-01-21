<template>
    <div
        v-show="isModalOpen"
        class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50"
    >
        <div
            class="w-full max-w-2xl px-6 py-4 overflow-hidden bg-white rounded-lg dark:bg-gray-800"
            role="dialog"
            id="modal"
        >
            <!-- Header del Modal -->
            <header class="flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-100">
                    {{ title }}
                </h3>
                <button
                    @click="emitCloseModal"
                    class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="h-6 w-6">
                        <path
                            stroke="currentColor"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </header>

            <!-- Cuerpo del Modal -->
            <div
                class="mt-4 flex gap-6"
                v-if="selectedRow"
            >
                <!-- Columna Izquierda: Mostrar si hay avatar o qr -->
                <div
                    v-if="selectedRow.avatar || selectedRow.qr"
                    class="flex-shrink-0 space-y-4 w-50"
                >
                    <!-- Mostrar Avatar -->
                    <div v-if="selectedRow.avatar" class="w-40 h-40 rounded-full overflow-hidden mx-auto">
                        <img
                            :src="`/storage/${selectedRow.avatar}`"
                            alt="Avatar"
                            class="object-cover w-full h-full"
                        />
                    </div>
                    <!-- Mostrar QR -->
                    <div v-if="selectedRow.qr" class="w-50 h-50 overflow-hidden mx-auto">
                        <img
                            :src="`/storage/${selectedRow.qr}`"
                            alt="QR Code"
                            class="object-cover w-full h-full"
                        />
                    </div>
                </div>

                <!-- Columna Derecha: Detalles de Texto -->
                <div class="flex-1 space-y-4">
                    <!-- Mostrar Campos -->
                    <div
                        v-for="(label, key) in fieldsToShow"
                        :key="key"
                        class="text-sm text-gray-700 dark:text-gray-400"
                    >
                        <p>
                            <span>{{ label }}: </span>
                            <span class="font-bold">{{ selectedRow[key] || "No disponible" }}</span>
                        </p>
                    </div>

                    <!-- Imágenes adicionales -->
                    <div v-if="extraImages.length > 0" class="space-y-2">
                        <h4 class="text-sm font-bold text-gray-800 dark:text-gray-100">Imágenes adicionales:</h4>
                        <img
                            v-for="(image, index) in extraImages"
                            :key="index"
                            :src="image"
                            alt="Extra"
                            class="w-full rounded-lg"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useThemeStore } from "@/stores/themeStore";

// Props
const props = defineProps({
    isModalOpen: { type: Boolean, default: false },
    selectedRow: { type: Object, required: false, default: () => ({}) },
    fieldsToShow: {
        type: Object,
        default: () => ({}),
    },
    title: { type: String, default: "Detalles" },
});

// Emits
const emit = defineEmits(["close"]);

// Theme Store
const themeStore = useThemeStore();

// Emit cerrar modal
const emitCloseModal = () => emit("close");

// Computed para filtrar imágenes adicionales excluyendo avatar y qr
const extraImages = computed(() => {
    if (!props.selectedRow || Object.keys(props.selectedRow).length === 0) return [];
    return Object.keys(props.selectedRow)
        .filter(
            (key) =>
                key.toLowerCase().includes("image") &&
                !["avatar", "qr"].includes(key.toLowerCase()) &&
                props.selectedRow[key]
        )
        .map((key) => props.selectedRow[key]);
});
</script>
