import { defineStore } from "pinia";
import { ref, computed } from "vue";

export const useResidenceStore = defineStore("residenceStore", () => {
    const selectedResidence = ref(null);

    const selectedResidenceId = computed(() => selectedResidence.value?.id ?? null);

    const setSelectedResidence = (residence) => {
        selectedResidence.value = residence;
    };

    const clearSelectedResidence = () => {
        selectedResidence.value = null;
    };

    return {
        selectedResidence,
        selectedResidenceId,
        setSelectedResidence,
        clearSelectedResidence,
    };
});
