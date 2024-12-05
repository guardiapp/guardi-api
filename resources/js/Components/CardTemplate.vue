<template>
    <div
        class="flex items-center p-4 rounded-lg shadow-xs"
        :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
    >
        <!-- Slot para el ícono -->
        <div :class="computedIconClass" class="p-3 mr-4 rounded-full">
            <slot name="icon" />
        </div>
        <!-- Contenido del texto -->
        <div>
            <p
                class="mb-2 text-sm font-medium"
                :class="dark ? 'text-gray-400' : 'text-gray-600'"
            >
                {{ title }}
            </p>
            <p
                class="text-lg font-semibold"
                :class="dark ? 'text-gray-200' : 'text-gray-700'"
            >
                {{ value }}
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed } from "vue";
import { useThemeStore } from "@/stores/themeStore";

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: String,
        required: true,
    },
    iconBgClass: {
        type: String,
        default: "bg-orange-100 text-orange-500",
    },
});

// Accede al estado del tema oscuro desde el store
const themeStore = useThemeStore();
const dark = computed(() => themeStore.dark);

// Ajusta las clases del ícono dependiendo del tema
const computedIconClass = computed(() => {
    return dark.value
        ? props.iconBgClass
              .replace("bg-orange-100", "bg-orange-500")
              .replace("text-orange-500", "text-orange-100")
        : props.iconBgClass;
});
</script>
