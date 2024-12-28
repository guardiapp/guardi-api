import axios from "axios";
import Swal from "sweetalert2";
import { notify } from "notiwind";
import { useThemeStore } from "@/stores/themeStore";
import { router } from "@inertiajs/vue3";

export function useGlobalFunctions() {
    const themeStore = useThemeStore();

    const deleteResidence = async (id) => {
        const result = await Swal.fire({
            customClass: {
                popup: themeStore.dark
                    ? "bg-gray-900 text-white"
                    : "bg-white text-gray-900",
            },
            text: "¿Desea eliminar esta residencia?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#7e3af2",
            cancelButtonColor: "#4c4f52",
            confirmButtonText: "Si, eliminar",
            cancelButtonText: "Cancelar",
        });

        if (result.isConfirmed) {
            try {
                await axios.post(`/residences/${id}`, { _method: "DELETE" });

                notify(
                    {
                        group: "info",
                        title: "Cambio realizado",
                        text: "La residencia ha sido eliminada.",
                    },
                    4000
                );

                // Fuerza la recarga del navegador
                window.location.reload();
            } catch (error) {
                console.error("Error al eliminar:", error.response || error);
                notify(
                    {
                        group: "error",
                        title: "Error",
                        text: "No se pudo eliminar la residencia.",
                    },
                    4000
                );
            }
        }
    };

    return { deleteResidence };
}
