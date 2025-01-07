<template>
    <div
        v-show="isModalOpen"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-30 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
    >
        <div
            v-show="isModalOpen"
            x-transition:enter="transition ease-out duration-150"
            x-transition:enter-start="opacity-0 transform translate-y-1/2"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0  transform translate-y-1/2"
            @keydown.escape="emitCloseModal"
            class="w-full px-6 py-4 overflow-hidden bg-white rounded-t-lg dark:bg-gray-800 sm:rounded-lg sm:m-4 sm:max-w-xl"
            role="dialog"
            id="modal"
        >
            <!-- Remove header if you don't want a close icon. Use modal body to place modal tile. -->
            <header class="flex justify-end">
                <button
                    class="inline-flex items-center justify-center w-6 h-6 text-gray-400 transition-colors duration-150 rounded dark:hover:text-gray-200 hover: hover:text-gray-700"
                    aria-label="close"
                    @click="emitCloseModal"
                >
                    <svg
                        class="w-4 h-4"
                        fill="currentColor"
                        viewBox="0 0 20 20"
                        role="img"
                        aria-hidden="true"
                    >
                        <path
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"
                            fill-rule="evenodd"
                        ></path>
                    </svg>
                </button>
            </header>
            <!-- Modal body -->
            <div class="mt-4 mb-6" v-if="visit">
                <!-- Modal title -->
                <p
                    class="mb-2 text-lg font-semibold text-gray-700 dark:text-gray-300"
                >
                    Visitante
                </p>

                <div
                    class="px-4 py-3 mb-8 rounded-lg shadow-md"
                    :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
                >
                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        Visitante: <span class="font-bold">{{ visit.visitor? `${visit.visitor.first_name} ${visit.visitor.last_name}` : '' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        Residente: <span class="font-bold">{{ visit.resident? `${visit.resident.first_name} ${visit.resident.last_name}` : '' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        Recurrente: <span class="font-bold">{{ visit.expiration_date ? 'Sí' : 'No' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6" v-if="visit.expiration_date">
                        Fecha de vencimiento: <span class="font-bold">{{ visit.expiration_date || '' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6" v-else>
                        Fecha de visita: <span class="font-bold">{{ visit.visit_date || '' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        Realizada: <span class="font-bold">{{ visit.visited ? 'Sí' : 'No' }}</span>
                    </p>

                    <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        observación: <span class="font-bold">{{ visit.remarks }}</span>
                    </p>

                    <div class="mt-6 flex justify-center items-center" v-if="visit.qr">
                        <img :src="`/storage/${visit.qr}`" alt="" width="200px">
                    </div>
                    <!-- <p class="text-sm text-gray-700 dark:text-gray-400 mt-6">
                        Documento: <span class="font-bold">{{ visit }}</span>
                    </p> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

const props = defineProps({
    isModalOpen: Boolean,
    visit: Object
});

const emit = defineEmits(["closeModal"]);
const emitCloseModal = () => {
    emit("closeModal");
};

</script>
