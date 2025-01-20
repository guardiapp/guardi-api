<template>
    <div class="w-full overflow-hidden rounded-lg shadow-xs"
        :class="{'without-top-radius': withoutTopRadius}"
    >
        <!-- Tabla -->
        <div v-if="data.length > 0">
            <div class="w-full overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="text-xs font-semibold tracking-wide text-left uppercase border-b"
                            :class="
                                themeStore.dark
                                    ? 'border-gray-700 text-gray-400 bg-gray-800'
                                    : 'text-gray-500 bg-gray-50'
                            "
                        >
                            <th
                                v-for="(column, index) in columns"
                                :key="index"
                                class="px-4 py-3"
                            >
                                {{ column }}
                            </th>
                        </tr>
                    </thead>
                    <tbody
                        class="divide-y"
                        :class="
                            themeStore.dark
                                ? 'divide-gray-700 bg-gray-800'
                                : 'bg-white'
                        "
                    >
                        <tr
                            v-for="(row, rowIndex) in data"
                            :key="rowIndex"
                            :class="
                                themeStore.dark
                                    ? 'text-gray-400'
                                    : 'text-gray-700'
                            "
                        >
                            <td
                                v-for="(value, colIndex) in row"
                                :key="colIndex"
                                class="px-4 py-3"
                            >
                                <!-- Custom render for specific columns -->
                                <slot
                                    :name="`column-${colIndex}`"
                                    :row="row"
                                    :value="value"
                                    :index="rowIndex"
                                >
                                    <!-- Default rendering -->
                                    <div
                                        class="px-4 py-3 text-sm overflow-hidden text-ellipsis whitespace-nowrap max-w-xs"
                                    >
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
                v-if="showPagination"
                class="grid px-4 py-3 text-xs font-semibold tracking-wide uppercase border-t sm:grid-cols-9"
                :class="
                    themeStore.dark
                        ? 'border-gray-700 bg-gray-50 text-gray-400 bg-gray-800'
                        : 'text-gray-500 bg-gray-50'
                "
            >
                <span class="flex items-center col-span-3">
                    Mostrando del {{ from }} al {{ to }} de {{ total }} registros
                </span>

                <!-- filas por pagina -->
                <span class="col-span-2">
                    <!-- <div class="flex justify-center items-center">
                    <label class="text-xs" for="rowsPerPage">Filas:</label>
                    <select
                        id="rowsPerPage"
                        v-model="localRowsPerPage"
                        class="mx-1 py-0.5 text-xs"
                        :class="{
                            'focus:border-purple-400 focus:outline-none focus:shadow-outline-purple':
                                !themeStore.dark,
                            'border-gray-600 bg-gray-700 text-gray-300 focus:shadow-outline-gray ':
                                themeStore.dark,
                        }"
                        @change="changeRowsPerPage"
                    >
                        <option
                            v-for="value in rowsPerPageOptions"
                            :key="value"
                            :value="value"
                        >
                            {{ value }}
                        </option>
                    </select>
                </div> -->
                </span>

                <!-- Paginacion -->
                <span class="flex col-span-4 mt-2 sm:mt-auto sm:justify-end">
                    <nav class="relative flex justify-end">
                        <template v-for="link in links" :key="link.label">
                            <div
                                v-if="link.url === null || link.active"
                                v-html="
                                    link.label
                                        .replaceAll('Previous', '')
                                        .replaceAll('Next', '')
                                "
                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                                :class="{
                                    'text-white bg-purple-600 border border-purple-600':
                                        link.active == true,
                                }"
                            ></div>
                            <Link
                                v-else
                                :href="link.url ?? ''"
                                v-html="
                                    link.label
                                        .replaceAll('Previous', '')
                                        .replaceAll('Next', '')
                                "
                                class="px-3 py-1 rounded-md focus:outline-none focus:shadow-outline-purple"
                                :class="{
                                    'text-white bg-purple-600 border border-purple-600':
                                        link.active == true,
                                }"
                            />
                        </template>
                    </nav>
                </span>
            </div>
        </div>
        <div class="my-4 mx-auto" v-else>
            <p
                class="text-center my-5 py-5"
                :class="
                    themeStore.dark
                        ? 'border-gray-700 text-gray-400 bg-gray-800'
                        : 'text-gray-500 bg-gray-50'
                "
            >
                No se han agregado o encontrado registros con esta especificación
            </p>
        </div>
    </div>
</template>

<script setup>
import { computed, ref } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { Link } from "@inertiajs/vue3";
const themeStore = useThemeStore();

// Props
const props = defineProps({
    columns: {
        type: Array,
        required: true,
    },
    data: {
        type: Array,
        required: true,
    },
    showPagination: {
        type: Boolean,
        default: true,
    },
    rowsPerPage: {
        type: Number,
        default: 5,
    },
    rowsPerPageOptions: {
        type: Array,
        default: () => [5, 10, 20, 50],
    },
    links: {
        type: Array,
        default: () => [],
    },
    from: {
        type: Number,
        default: null,
    },
    to: {
        type: Number,
        default: null,
    },
    total: {
        type: Number,
        default: null,
    },
    currentPage: {
        typeof: Number,
        default: null,
    },
    withoutTopRadius: {
        type: Boolean,
        default: false,
    }
});
</script>


<style lang="css" scoped>
.without-top-radius {
    border-top-left-radius: 0 !important;
    border-top-right-radius: 0 !important;
}
</style>
