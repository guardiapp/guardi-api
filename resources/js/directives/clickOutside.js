export default {
    beforeMount(el, binding) {
        el.clickOutsideHandler = (event) => {
        if (!(el === event.target || el.contains(event.target))) {
            binding.value(event); // Llama a la función pasada en el binding
        }
        };
        document.addEventListener("click", el.clickOutsideHandler);
    },
    unmounted(el) {
        document.removeEventListener("click", el.clickOutsideHandler);
    },
};
