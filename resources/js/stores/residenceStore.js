import { defineStore } from "pinia";
import { ref, computed, watch } from "vue";

export const useResidenceStore = defineStore("residenceStore", () => {
    const selectedResidence = ref(JSON.parse(localStorage.getItem("selectedResidence")) || null);

    const selectedResidenceId = computed(() => selectedResidence.value?.id ?? null);

    const setSelectedResidence = (residence) => {
        selectedResidence.value = residence;
    };

    const clearSelectedResidence = () => {
        selectedResidence.value = null;
    };

    // Watch for changes in selectedResidence and update localStorage
    watch(selectedResidence, (newValue) => {
        if (newValue) {
            localStorage.setItem("selectedResidence", JSON.stringify(newValue));
        } else {
            localStorage.removeItem("selectedResidence");
        }
    });

    return {
        selectedResidence,
        selectedResidenceId,
        setSelectedResidence,
        clearSelectedResidence,
    };
});
