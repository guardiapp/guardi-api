<template>
    <div>
        <!-- Desktop sidebar -->
        <aside
            class="z-20 hidden h-screen w-64 overflow-y-auto md:block flex-shrink-0"
            :class="{
                'bg-white': !themeStore.dark,
                'bg-gray-800 ': themeStore.dark,
            }"
        >
            <div
                class="py-4"
                :class="{
                    'text-gray-500': !themeStore.dark,
                    'text-gray-400': themeStore.dark,
                }"
            >
                <Link
                    class="ml-6 text-lg font-bold"
                    href="/dashboard"
                    :class="{
                        'text-gray-800': !themeStore.dark,
                        'text-gray-200': themeStore.dark,
                    }"
                >
                    Visitas
                </Link>
                <ul class="mt-6">
                    <li
                        v-for="(menuItem, index) in menuItems"
                        :key="index"
                    >
                    <div
                        class="relative px-6 py-3"
                        v-if="menuItem.roles.includes(user.type)" >
                            <span
                                class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                                aria-hidden="true"
                                v-if="isActive(menuItem.link)"
                            ></span>
                            <Link
                                :href="menuItem.link"
                                class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
                                :class="linkClasses(menuItem.link)"
                            >
                                <svg
                                    class="w-5 h-5"
                                    aria-hidden="true"
                                    fill="none"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    viewBox="0 0 24 24"
                                    stroke="currentColor"
                                >
                                    <path
                                        d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                    ></path>
                                </svg>
                                <span class="ml-4">{{ menuItem.label }}</span>
                            </Link>
                        </div>
                    </li>

                    <!-- <li class="relative px-6 py-3">
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150" :class="{
              'hover:text-gray-800': !themeStore.dark,
              'hover:text-gray-200': themeStore.dark,
            }" href="forms.html">
              <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
              </svg>
              <span class="ml-4">Forms</span>
            </a>
          </li>
          <li class="relative px-6 py-3">
            <button
              class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150"
              :class="{
                'hover:text-gray-800': !themeStore.dark,
                'hover:text-gray-200': themeStore.dark,
              }" @click="themeStore.togglePagesMenu" aria-haspopup="true">
              <span class="inline-flex items-center">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                  <path
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                  </path>
                </svg>
                <span class="ml-4">Pages</span>
              </span>
              <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
            </button>
            <template v-if="themeStore.isPagesMenuOpen">
              <ul transition:enter="transition-all ease-in-out duration-300"
                transition:enter-start="opacity-25 max-h-0" transition:enter-end="opacity-100 max-h-xl"
                transition:leave="transition-all ease-in-out duration-300"
                transition:leave-start="opacity-100 max-h-xl" transition:leave-end="opacity-0 max-h-0"
                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium rounded-md shadow-inner" :class="{
                  'text-gray-500 bg-gray-50':
                    !themeStore.dark,
                  'text-gray-400 bg-gray-900':
                    themeStore.dark,
                }" aria-label="submenu">
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/login.html">Login</a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/create-account.html">
                    Create account
                  </a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/forgot-password.html">
                    Forgot password
                  </a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/404.html">404</a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/blank.html">Blank</a>
                </li>
              </ul>
            </template>
          </li> -->
                </ul>
                <!-- <div class="px-6 my-6">
          <button
            class="flex items-center justify-between w-full px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Create account
            <span class="ml-2" aria-hidden="true">+</span>
          </button>
        </div> -->
            </div>
        </aside>
        <!-- Mobile sidebar -->
        <!-- Backdrop -->
        <div
            v-if="themeStore.isSideMenuOpen"
            transition:enter="transition ease-in-out duration-150"
            transition:enter-start="opacity-0"
            transition:enter-end="opacity-100"
            transition:leave="transition ease-in-out duration-150"
            transition:leave-start="opacity-100"
            transition:leave-end="opacity-0"
            class="fixed inset-0 z-10 flex items-end bg-black bg-opacity-50 sm:items-center sm:justify-center"
        ></div>
        <aside
            class="fixed inset-y-0 z-20 flex-shrink-0 w-64 mt-16 overflow-y-auto md:hidden"
            :class="{
                'bg-white': !themeStore.dark,
                'bg-gray-800': themeStore.dark,
            }"
            v-show="themeStore.isSideMenuOpen"
            transition:enter="transition ease-in-out duration-150"
            transition:enter-start="opacity-0 transform -translate-x-20"
            transition:enter-end="opacity-100"
            transition:leave="transition ease-in-out duration-150"
            transition:leave-start="opacity-100"
            transition:leave-end="opacity-0 transform -translate-x-20"
            @click.away="themeStore.closeSideMenu"
            @keydown.escape="themeStore.closeSideMenu"
        >
            <div
                class="py-4"
                :class="{
                    'text-gray-500': !themeStore.dark,
                    'text-gray-400': themeStore.dark,
                }"
            >
                <a
                    class="ml-6 text-lg font-bold"
                    :class="{
                        'text-gray-800': !themeStore.dark,
                        'text-gray-200': themeStore.dark,
                    }"
                    href="#"
                >
                    Visitas
                </a>
                <ul class="mt-6">
                    <li class="relative px-6 py-3">
                        <span
                            class="absolute inset-y-0 left-0 w-1 bg-purple-600 rounded-tr-lg rounded-br-lg"
                            aria-hidden="true"
                        ></span>
                        <Link
                            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
                            :class="{
                                'text-gray-800  hover:text-gray-800':
                                    !themeStore.dark,
                                ' hover:text-gray-200 text-gray-100':
                                    themeStore.dark,
                            }"
                            href="/dashboard"
                        >
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"
                                ></path>
                            </svg>
                            <span class="ml-4">Inicio</span>
                        </Link>
                    </li>
                </ul>
                <ul>
                    <li class="relative px-6 py-3" v-if="user.type == 'Admin'">
                        <Link
                            href="/residences"
                            class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150"
                            :class="{
                                'text-gray-800 hover:text-gray-800':
                                    !themeStore.dark,
                                'hover:text-gray-200 text-gray-100':
                                    themeStore.dark,
                            }"
                        >
                            <svg
                                class="w-5 h-5"
                                aria-hidden="true"
                                fill="none"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                viewBox="0 0 24 24"
                                stroke="currentColor"
                            >
                                <path
                                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"
                                ></path>
                            </svg>
                            <span class="ml-4">Residencias</span>
                        </Link>
                    </li>

                    <!-- <li class="relative px-6 py-3">
            <a class="inline-flex items-center w-full text-sm font-semibold transition-colors duration-150" :class="{
              'hover:text-gray-800': !themeStore.dark,
              'hover:text-gray-200': themeStore.dark,
            }" href="forms.html">
              <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                <path
                  d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01">
                </path>
              </svg>
              <span class="ml-4">Forms</span>
            </a>
          </li>
          <li class="relative px-6 py-3">
            <button
              class="inline-flex items-center justify-between w-full text-sm font-semibold transition-colors duration-150"
              :class="{
                'hover:text-gray-800': !themeStore.dark,
                'hover:text-gray-200': themeStore.dark,
              }" @click="themeStore.togglePagesMenu" aria-haspopup="true">
              <span class="inline-flex items-center">
                <svg class="w-5 h-5" aria-hidden="true" fill="none" stroke-linecap="round" stroke-linejoin="round"
                  stroke-width="2" viewBox="0 0 24 24" stroke="currentColor">
                  <path
                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z">
                  </path>
                </svg>
                <span class="ml-4">Pages</span>
              </span>
              <svg class="w-4 h-4" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
            </button>
            <template v-if="themeStore.isPagesMenuOpen">
              <ul transition:enter="transition-all ease-in-out duration-300"
                transition:enter-start="opacity-25 max-h-0" transition:enter-end="opacity-100 max-h-xl"
                transition:leave="transition-all ease-in-out duration-300"
                transition:leave-start="opacity-100 max-h-xl" transition:leave-end="opacity-0 max-h-0"
                class="p-2 mt-2 space-y-2 overflow-hidden text-sm font-medium rounded-md shadow-inner" :class="{
                  'text-gray-500 bg-gray-50': !themeStore.dark,
                  'text-gray-400 bg-gray-900': themeStore.dark,
                }" aria-label="submenu">
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/login.html">Login</a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/create-account.html">
                    Create account
                  </a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/forgot-password.html">
                    Forgot password
                  </a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/404.html">404</a>
                </li>
                <li class="px-2 py-1 transition-colors duration-150" :class="{
                  'hover:text-gray-800': !themeStore.dark,
                  'hover:text-gray-200': themeStore.dark,
                }">
                  <a class="w-full" href="pages/blank.html">Blank</a>
                </li>
              </ul>
            </template>
          </li> -->
                </ul>
                <!-- <div class="px-6 my-6">
          <button
            class="flex items-center justify-between px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
            Create account
            <span class="ml-2" aria-hidden="true">+</span>
          </button>
        </div> -->
            </div>
        </aside>
    </div>
</template>

<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";

const user = usePage().props.auth.user;

const themeStore = useThemeStore();

//const route = usePage.props.route;

// Función para determinar si la ruta actual coincide
const isActive = (href) => route().current(href);

// Función para asignar clases dinámicas según el estado activo
const linkClasses = (href) =>
    isActive(href)
        ? {
              "text-gray-800 font-bold hover:text-gray-800": !themeStore.dark,
              "text-gray-100 font-bold hover:text-gray-200": themeStore.dark,
          }
        : {
              "text-gray-800 hover:text-gray-800": !themeStore.dark,
              "text-gray-100 hover:text-gray-200": themeStore.dark,
          };

const menuItems = [
    {
        label: "Inicio",
        link: "/dashboard",
        roles: ["Admin", "Manager", "Guard", "Resident"],
    },
    { label: "Administradores", link: "/managers", roles: ["Admin"] },
    { label: "Residencias", link: "/residences", roles: ["Admin", "Manager"] },
    { label: "Vigilantes", link: "/guards", roles: ["Admin", "Manager"] },
    { label: "Residentes", link: "/residences", roles: ["Admin", "Manager"] },
];
</script>
