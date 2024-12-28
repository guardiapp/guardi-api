<template>
    <body :class="{ 'theme-dark': themeStore.dark }">
        <NotificationMessages/>
        <div
            class="flex h-screen"
            :class="{
                'overflow-hidden': isSideMenuOpen,
                'bg-gray-50': !themeStore.dark,
                'bg-gray-900 ': themeStore.dark,
            }"
        >
            <SidebarTemplate />
            <div class="flex flex-col flex-1 w-full">
                <HeaderTemplate />
                <main class="h-full overflow-y-auto">
                    <slot />
                </main>
            </div>
        </div>
    </body>
</template>

<script setup>
import SidebarTemplate from "@/Components/SidebarTemplate.vue";
import HeaderTemplate from "@/Components/HeaderTemplate.vue";
import { onMounted, watch } from "vue";
import { usePage } from "@inertiajs/vue3";
import { useThemeStore } from "@/stores/themeStore";
import NotificationMessages from "@/Components/NotificationMessages.vue";

//import { useNotiwind } from 'notiwind';

//const { addNotification } = useNotiwind();

import { notify } from "notiwind";

// onMounted(() => {
//     notify(
//         {
//             group: "foo",
//             title: "Success",
//             text: "Your account was registered!",
//         },
//         4000
//     );
// });

const page = usePage();

// Obtener el store de tema
const themeStore = useThemeStore();

// Sincronizar el estado del tema con localStorage al cargar
themeStore.syncThemeWithLocalStorage();

// function testNotification() {
//   addNotification({
//     message: 'Test notification works!',
//     type: 'success', // Options: success, error, info, warning
//   });
// }

// Observar cambios en las props de la página.
// watch(
//   () => page.props.flash.success, // Observar el mensaje de éxito
//   (newValue) => {
//     if (newValue) {
//       notify({
//         group: "success",
//         title: "Success",
//         text: newValue,
//       });
//     }
//   }
// );
</script>
