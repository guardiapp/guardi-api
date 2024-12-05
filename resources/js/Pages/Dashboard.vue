<template>
  <MainLayout>
    <div class="container px-6 mx-auto grid">
      <h2 class="my-6 text-2xl font-semibold"
      :class="{
        'text-gray-700': !themeStore.dark,
        'text-gray-200': themeStore.dark,
        }"
      >
        Dashboard
      </h2>
      <!-- CTA -->
      <a class="flex items-center justify-between p-4 mb-8 text-sm font-semibold text-purple-100 bg-purple-600 rounded-lg shadow-md focus:outline-none focus:shadow-outline-purple"
        href="https://github.com/estevanmaito/windmill-dashboard">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
            </path>
          </svg>
          <span>Star this project on GitHub</span>
        </div>
        <span>View more &RightArrow;</span>
      </a>
      <!-- Cards -->
      <div class="grid gap-6 mb-8 md:grid-cols-2 xl:grid-cols-4">
        <!-- Card -->
        <CardTemplate v-for="(item, index) in cardItems" :key="index" :title="item.title" :value="item.value"
          :iconBgClass="item.iconBgClass">
          <template #icon>
            <component :is="getIconComponent(item.iconKey)" class="w-5 h-5" />
          </template>
        </CardTemplate>
      </div>

      <TableTemplate :columns="['Client', 'Amount', 'Status', 'Date']" :data="tableData" :rows-per-page="5">
        <template #column-client="{ value }">
          <div class="flex items-center text-sm">
            <div class="relative hidden w-8 h-8 mr-3 rounded-full md:block">
              <img class="object-cover w-full h-full rounded-full" :src="value.avatar" alt="Avatar" />
              <div class="absolute inset-0 rounded-full shadow-inner" aria-hidden="true"></div>
            </div>
            <div>
              <p class="font-semibold">{{ value.name }}</p>
              <p class="text-xs"
              :class="{
                'text-gray-600': !themeStore.dark,
                'text-gray-400': themeStore.dark,
              }"
              >
                {{ value.role }}
              </p>
            </div>
          </div>
        </template>

        <template #column-status="{ value }">
          <div class="px-4 py-3 text-xs">
            <span class="px-2 py-1 font-semibold leading-tight rounded-full"
            :class="getStatusClass(value.text)"
            >
              {{ value.text }}
            </span>
          </div>
        </template>
      </TableTemplate>

      <!-- Charts -->
      <h2 class="my-6 text-2xl font-semibold"
      :class="{
                'text-gray-700': !themeStore.dark,
                'text-gray-200': themeStore.dark,
              }"

      >
        Charts
      </h2>
      <div class="grid gap-6 mb-8 md:grid-cols-2">
        <ChartPie chartId="revenueChart" title="Revenue" :labels="['Shirts', 'Shoes', 'Bags']" :data="[50, 30, 20]" />
        <ChartLine chartId="trafficLineChart" title="Traffic" :labels="[
          'January',
          'February',
          'March',
          'April',
          'May',
          'June',
          'July',
        ]" :datasets="[
                      {
                        label: 'Organic',
                        data: [43, 48, 40, 54, 67, 73, 70],
                        fill: false,
                      },
                      {
                        label: 'Paid',
                        data: [24, 50, 64, 74, 52, 51, 65],
                        fill: false,
                      },
                    ]" :legend="['Organic', 'Paid']" :colors="['#0694a2', '#7e3af2']" />
      </div>
    </div>
  </MainLayout>
</template>

<!-- <script>
import { onMounted } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import CardTemplate from "@/Components/CardTemplate.vue";
import UsersIcon from "@/Components/Icons/UsersIcon.vue";
import MoneyIcon from "@/Components/Icons/MoneyIcon.vue";
import ShoppingCartIcon from "@/Components/Icons/ShoppingCartIcon.vue";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import ChartPie from "@/Components/ChartPie.vue";
import ChartLine from "@/Components/ChartLine.vue";
import TableTemplate from "@/Components/TableTemplate.vue";

import { useThemeStore } from "@/stores/themeStore";

const themeStore = useThemeStore();

export default {
  components: {
    MainLayout,
    CardTemplate,
    UsersIcon,
    MoneyIcon,
    ShoppingCartIcon,
    MessageIcon,
    ChartPie,
    ChartLine,
    TableTemplate,
    themeStore
  },
  methods: {
    getIconComponent(icon) {
      const icons = {
        "users-icon": "UsersIcon",
        "money-icon": "MoneyIcon",
        "shopping-cart-icon": "ShoppingCartIcon",
        "message-icon": "MessageIcon",
      };
      return icons[icon];
    },
  },
  data() {
    return {
      cardItems: [
        {
          title: "Total Clients",
          value: "6389",
          iconBgClass:
            "bg-orange-100 text-orange-500 dark:bg-orange-500 dark:text-orange-100",
          icon: "users-icon",
        },
        {
          title: "Account balance",
          value: "$ 46,760.89",
          iconBgClass:
            "text-green-500 bg-green-100 dark:text-green-100 dark:bg-green-500",
          icon: "money-icon",
        },
        {
          title: "New sales",
          value: "376",
          iconBgClass:
            "text-blue-500 bg-blue-100 dark:text-blue-100 dark:bg-blue-500",
          icon: "shopping-cart-icon",
        },
        {
          title: "Pending contacts",
          value: "35",
          iconBgClass:
            "text-teal-500 bg-teal-100 dark:text-teal-100 dark:bg-teal-500",
          icon: "message-icon",
        },
      ],
      tableData: [
        {
          client: {
            avatar: "https://via.placeholder.com/100",
            name: "John Doe",
            role: "Manager",
          },
          amount: "$1000",
          status: {
            text: "Approved",
            class: "text-green-700 bg-green-100",
          },
          date: "2024-11-27",
        },
        {
          client: {
            avatar: "https://via.placeholder.com/100",
            name: "Jane Doe",
            role: "Developer",
          },
          amount: "$800",
          status: {
            text: "Pending",
            class: "text-yellow-700 bg-yellow-100",
          },
          date: "2024-11-26",
        },
      ],
    };
  },
};
</script> -->





<script setup>
import { computed, reactive } from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import CardTemplate from "@/Components/CardTemplate.vue";
import UsersIcon from "@/Components/Icons/UsersIcon.vue";
import MoneyIcon from "@/Components/Icons/MoneyIcon.vue";
import ShoppingCartIcon from "@/Components/Icons/ShoppingCartIcon.vue";
import MessageIcon from "@/Components/Icons/MessageIcon.vue";
import ChartPie from "@/Components/ChartPie.vue";
import ChartLine from "@/Components/ChartLine.vue";
import TableTemplate from "@/Components/TableTemplate.vue";
import { useThemeStore } from "@/stores/themeStore";

const themeStore = useThemeStore();

// Lista de íconos (relaciona las claves con los componentes)
const icons = {
  "users-icon": UsersIcon,
  "money-icon": MoneyIcon,
  "shopping-cart-icon": ShoppingCartIcon,
  "message-icon": MessageIcon,
};

const getIconComponent = (iconKey) => {
  return icons[iconKey];
};

// Datos reactivos
const cardItems = reactive([
  {
    title: "Total Clients",
    value: "6389",
    iconKey: "users-icon",
    iconBgClass: computed(() =>
      themeStore.dark
        ? "text-orange-100 bg-orange-500"
        : "text-orange-500 bg-orange-100"
    ),
  },
  {
    title: "Account balance",
    value: "$46,760.89",
    iconKey: "money-icon",
    iconBgClass: computed(() =>
      themeStore.dark
        ? "text-green-100 bg-green-500"
        : "text-green-500 bg-green-100"
    ),
  },
  {
    title: "New sales",
    value: "376",
    iconKey: "shopping-cart-icon",
    iconBgClass: computed(() =>
      themeStore.dark
        ? "text-blue-100 bg-blue-500"
        : "text-blue-500 bg-blue-100"
    ),
  },
  {
    title: "Pending contacts",
    value: "35",
    iconKey: "message-icon",
    iconBgClass: computed(() =>
      themeStore.dark
        ? "text-teal-100 bg-teal-500"
        : "text-teal-500 bg-teal-100"
    ),
  },
]);

const tableData = reactive([
  {
    client: {
      avatar: "https://via.placeholder.com/100",
      name: "John Doe",
      role: "Manager",
    },
    amount: "$1000",
    status: {
      text: "Approved",
      class: "text-green-700 bg-green-100",
    },
    date: "2024-11-27",
  },
  {
    client: {
      avatar: "https://via.placeholder.com/100",
      name: "Jane Doe",
      role: "Developer",
    },
    amount: "$800",
    status: {
      text: "Pending",
      class: "text-yellow-700 bg-yellow-100",
    },
    date: "2024-11-26",
  },
]);

const getStatusClass = (statusText) => {
  const isDark = themeStore.dark;
  const classes = {
    Approved: isDark
      ? "text-green-100 bg-green-700"
      : "text-green-700 bg-green-100",
    Pending: isDark
      ? "text-white bg-orange-600"
      : "text-orange-700 bg-orange-100",
    Denied: isDark
      ? "text-red-100 bg-red-700"
      : "text-red-700 bg-red-100",
    Expired: isDark
      ? "text-gray-100 bg-gray-700"
      : "text-gray-700 bg-gray-100",
  };

  return classes[statusText] || "";
};
</script>
