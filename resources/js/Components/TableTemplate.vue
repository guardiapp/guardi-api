<template>
  <div class="w-full overflow-hidden rounded-lg shadow-xs">
    <!-- Tabla -->
    <div class="w-full overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr
            class="text-xs font-semibold tracking-wide text-left uppercase border-b"
            :class="themeStore.dark ? 'border-gray-700 text-gray-400 bg-gray-800' : 'text-gray-500 bg-gray-50'"
            >
            <th v-for="(column, index) in columns" :key="index" class="px-4 py-3">
              {{ column }}
            </th>
          </tr>
        </thead>
        <tbody class="divide-y"
        :class="themeStore.dark ? 'divide-gray-700 bg-gray-800' : 'bg-white'"
        >
          <tr v-for="(row, rowIndex) in paginatedData" :key="rowIndex"
            :class="themeStore.dark ? 'text-gray-400' : 'text-gray-700'"
          >
            <td v-for="(value, colIndex) in row" :key="colIndex" class="px-4 py-3">
              <!-- Custom render for specific columns -->
              <slot :name="`column-${colIndex}`" :row="row" :value="value" :index="rowIndex">
                <!-- Default rendering -->
                  <div class="px-4 py-3 text-sm">
                    {{ value }}
                  </div>
              </slot>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div
      class="grid px-4 py-3 text-xs font-semibold tracking-wide uppercase border-t sm:grid-cols-9"
        :class="themeStore.dark ? 'border-gray-700 bg-gray-50 text-gray-400 bg-gray-800' : 'text-gray-500 bg-gray-50'"

      >
      <span class="flex items-center col-span-3">
        Mostrando del {{ startIndex + 1 }} al  {{ endIndex }} de
        {{ data.length }} registros
      </span>
      <span class="col-span-2"></span>
      <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
        <nav aria-label="Table navigation">
          <ul class="inline-flex items-center">
            <li>
              <button class="px-3 py-1 rounded-md rounded-l-lg focus:outline-none focus:shadow-outline-purple"
                aria-label="Previous" :disabled="currentPage === 1" @click="prevPage">
                <svg aria-hidden="true" class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                  <path
                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                    clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
              </button>
            </li>
            <li v-for="page in totalPages" :key="page">
              <button class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple" :class="{
                'text-white bg-purple-600 border border-purple-600':
                  page === currentPage,
              }" @click="goToPage(page)">
                {{ page }}
              </button>
            </li>
            <li>
              <button class="px-3 py-1 rounded-md rounded-r-lg focus:outline-none focus:shadow-outline-purple"
                aria-label="Next" :disabled="currentPage === totalPages" @click="nextPage">
                <svg class="w-4 h-4 fill-current" aria-hidden="true" viewBox="0 0 20 20">
                  <path
                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                    clip-rule="evenodd" fill-rule="evenodd"></path>
                </svg>
              </button>
            </li>
          </ul>
        </nav>
      </span>
    </div>
  </div>
</template>

<script setup>
import { computed, ref } from 'vue';
import { useThemeStore } from "@/stores/themeStore";
const themeStore = useThemeStore();

// Props
const props = defineProps({
  columns: {
    type: Array,
    required: true, // Encabezados de la tabla
  },
  data: {
    type: Array,
    required: true, // Datos de la tabla
  },
  rowsPerPage: {
    type: Number,
    default: 10, // Filas por página
  },
});

// Estado
const currentPage = ref(1); // Página actual

// Computed
const totalPages = computed(() => Math.ceil(props.data.length / props.rowsPerPage));
const startIndex = computed(() => (currentPage.value - 1) * props.rowsPerPage);
const endIndex = computed(() =>
  Math.min(startIndex.value + props.rowsPerPage, props.data.length)
);
const paginatedData = computed(() =>
  props.data.slice(startIndex.value, endIndex.value)
);

// Métodos
const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};
const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};
const goToPage = (page) => {
  currentPage.value = page;
};
</script>

