<template>
  <div class="min-w-0 p-4 rounded-lg shadow-xs"
      :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
  >
    <!-- Título dinámico -->
    <h4 class="mb-4 font-semibold"
    :class="themeStore.dark ? 'text-gray-300' : 'text-gray-800'">
      {{ title }}
    </h4>

    <!-- Contenedor centrado -->
    <div class="flex justify-center">
      <canvas :id="chartId"></canvas>
    </div>

    <!-- Leyenda dinámica -->
    <div class="flex justify-center mt-4 space-x-3 text-sm"
    :class="themeStore.dark ? 'text-gray-400' : 'text-gray-600'"
    >
      <div
        v-for="(label, index) in labels"
        :key="index"
        class="flex items-center"
      >
        <span
          class="inline-block w-3 h-3 mr-1 rounded-full"
          :style="{ backgroundColor: chartColors[index] }"
        ></span>
        <span>{{ label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed } from "vue";
import Chart from "chart.js/auto";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

// Genera colores aleatorios
function generateRandomColors(length = 3) {
  return Array.from({ length }, () =>
    `#${Math.floor(Math.random() * 16777215).toString(16).padStart(6, "0")}`
  );
}

// Props
const props = defineProps({
  chartId: {
    type: String,
    default: "pieChart",
  },
  title: {
    type: String,
    default: "Chart Title",
  },
  labels: {
    type: Array,
    required: true,
  },
  data: {
    type: Array,
    required: true,
  },
  colors: {
    type: Array,
    default: () => [], // Se inicializa como un array vacío
  },
});

// Computed para manejar colores predeterminados
const chartColors = computed(() => {
  return props.colors.length > 0
    ? props.colors
    : generateRandomColors(props.labels.length || props.data.length);
});

// Referencia para la instancia del gráfico
const chartInstance = ref(null);

// Crear el gráfico
const createChart = () => {
  const ctx = document.getElementById(props.chartId).getContext("2d");
  chartInstance.value = new Chart(ctx, {
    type: "doughnut",
    data: {
      labels: props.labels,
      datasets: [
        {
          data: props.data,
          backgroundColor: chartColors.value, // Usamos el computed
        },
      ],
    },
    options: {
      responsive: false,
      cutout: "80%",
      plugins: {
        legend: {
          display: false,
        },
      },
    },
  });
};

// Destruir el gráfico
const destroyChart = () => {
  if (chartInstance.value) {
    chartInstance.value.destroy();
  }
};

// Hooks
onMounted(() => {
  createChart();
});

onUnmounted(() => {
  destroyChart();
});
</script>


<style scoped>
/* Estilo opcional para el canvas */
canvas {
  max-width: 100%;
  height: auto;
}
</style>
