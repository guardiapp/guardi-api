<template>
  <div class="min-w-0 p-4 rounded-lg shadow-xs"
  :class="themeStore.dark ? 'bg-gray-800' : 'bg-white'"
  >
    <h4 class="mb-4 font-semibold"
    :class="themeStore.dark ? 'text-gray-300' : 'text-gray-800'"
    >
      {{ title }}
    </h4>
    <canvas :id="chartId"></canvas>
    <div class="flex justify-center mt-4 space-x-3 text-sm"
    :class="themeStore.dark ? 'text-gray-400' : 'text-gray-600'"
    >
      <!-- Chart legend -->
      <div v-for="(label, index) in legend" :key="index" class="flex items-center">
        <span class="inline-block w-3 h-3 mr-1 rounded-full" :style="{ backgroundColor: colors[index] }"></span>
        <span>{{ label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onBeforeUnmount } from "vue";
import Chart from "chart.js/auto";
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

// Props
const props = defineProps({
  chartId: {
    type: String,
    default: "lineChart",
  },
  title: {
    type: String,
    default: "Line Chart",
  },
  labels: {
    type: Array,
    required: true, // Etiquetas para el eje X
  },
  datasets: {
    type: Array,
    required: true, // Conjunto de datos (datos + configuraciones como color)
  },
  legend: {
    type: Array,
    required: true, // Etiquetas para la leyenda
  },
  colors: {
    type: Array,
    required: true, // Colores para la leyenda
  },
});

// Estado
let chartInstance = null;

// Métodos
const createChart = () => {
  const ctx = document.getElementById(props.chartId).getContext("2d");
  chartInstance = new Chart(ctx, {
    type: "line",
    data: {
      labels: props.labels,
      datasets: props.datasets.map((dataset, index) => ({
        ...dataset,
        backgroundColor: props.colors[index], // Usa colores dinámicos
        borderColor: props.colors[index], // Usa colores dinámicos
        tension: 0.4,
      })),
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false, // Ocultar leyenda nativa
        },
      },
      scales: {
        x: {
          title: {
            display: true,
            text: "Month",
          },
        },
        y: {
          title: {
            display: true,
            text: "Value",
          },
        },
      },
    },
  });
};

const destroyChart = () => {
  if (chartInstance) {
    chartInstance.destroy(); // Libera la memoria al desmontar el componente
  }
};

// Hooks
onMounted(() => {
  createChart();
});

onBeforeUnmount(() => {
  destroyChart();
});
</script>

<style scoped>
canvas {
  max-width: 100%;
  height: auto;
}
</style>
