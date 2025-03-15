<template>
    <MainLayout>
        <div class="container px-6 mx-auto grid">
            <BreadcrumbTemplate
                :homeLink="{ url: '/', label: 'Inicio' }"
                :crumbs="[
                    { label: 'Residencias', isCurrent: false },
                    { label: 'Gestion administradores', isCurrent: true },
                ]"
            />

            <div class="flex items-center justify-between my-6">
                <h2
                    class="text-2xl font-semibold"
                    :class="themeStore.dark ? 'text-gray-200' : 'text-gray-700'"
                >
                    Residencias
                </h2>
            </div>
            <div class="titles">
                <h3>Administradores disponibles para asignar</h3>
                <h3>Administradores asignados</h3>
            </div>
            <div class="manage-managers">
                <ul ref="todoList" class="kanban-column">
                    <!--h4>Administradores disponibles para asignar</h4-->
                    <li
                        v-for="manager in notAssigned"
                        :key="manager"
                        class="kanban-item"
                    >
                        {{ manager.name }}
                    </li>
                </ul>

                <ul ref="doneList" class="kanban-column">
                    <!--h4>Administradores asignados</h4-->
                    <li
                        v-for="manager in assigned"
                        :key="manager"
                        class="kanban-item"
                    >
                        {{ manager.name }}
                    </li>
                </ul>
            </div>
            <div class="buttons">
                <button
                    type="button"
                    @click="cancel"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-gray-600 border border-transparent rounded-lg active:bg-gray-600 hover:bg-gray-700 focus:outline-none focus:shadow-outline-gray"
                >
                    Cancelar
                </button>
                <button
                    @click="submit"
                    type="submit"
                    class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Guardar
                </button>
            </div>
        </div>
    </MainLayout>
</template>

<script setup>
import { useDragAndDrop } from "@formkit/drag-and-drop/vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import { onMounted } from "vue";
import { useThemeStore } from "@/stores/themeStore";
import { useResidenceStore } from "@/stores/residenceStore";
import { Link, usePage, router } from "@inertiajs/vue3";
import { ref, computed, watch } from "vue";
import Swal from "sweetalert2";
import { notify } from "notiwind";
import axios from "axios";

const themeStore = useThemeStore();
const residenceStore = useResidenceStore();
const { props } = usePage();
const user = usePage().props.auth.user;

const residenceId = props.residenceId;
const managersAssigned = ref([]);
const managersNotAssigned = ref([]);

const [todoList, notAssigned] = useDragAndDrop(managersNotAssigned, {
    group: "todoList",
});
const [doneList, assigned] = useDragAndDrop(managersAssigned, {
    group: "todoList",
});

const findManagersAssigned = async () => {
    const res = await axios.get(
        `/manager-residences/${residenceId}/manager-by-residence`
    );
    res.data.forEach((value) => {
        managersAssigned.value = managersAssigned.value.concat(value.managers);
    });
};

const findManagersNotAssigned = async () => {
    const res = await axios.get(`/manager-residences/${residenceId}/not-in`);
    managersNotAssigned.value = res.data;
};

const submit = async () => {
    console.log(assigned.value);
    const payload = assigned.value.map((manager) => {
        return {
            manager_id: manager.id,
            residence_id:residenceId,
            state:"ACTIVE"
        };
    });
    const res = await axios.post(`/manager-residences/create-bulk`, { payload });
    console.log(res);
    if (res.data.success) {
        Swal.fire({
            toast:true,
            title:"Exito",
            text:"Relaciones generadas con exito.",
            icon:"success",
            position:"top-end",
            showConfirmButton: false,
            timer: 1500
        })
    }else{
        Swal.fire({
            toast:true,
            title:"Error",
            text: "Error: " + res.data.message,
            icon:"error",
            position:"top-end",
            showConfirmButton: false,
            timer: 1500
        })
    }

};

const cancel = () => {
    router.visit("/residences");
}

onMounted(() => {
    findManagersNotAssigned();
    findManagersAssigned();
});
</script>

<style>
.titles {
    display: grid;
    grid-template-rows: 1fr;
    grid-template-columns: 1fr 1fr;
    margin:2em;
}
.buttons {
    padding:2em;
    gap:1em;
    display:grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: 1fr;
}
.manage-managers {
    display: grid;
    grid-template-rows: 1fr;
    grid-template-columns: 1fr 1fr;
    gap:1em;
}
.kanban-column {
    background-color: #fafafa;
    display: block;
    padding: 1em;
    border: 1px solid #CEF;
}
.kanban-item {
    padding:.5em;
}
</style>
