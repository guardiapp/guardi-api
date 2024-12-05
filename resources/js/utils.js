import { ref, watchEffect } from "vue";

// Estado reactivo para el modo oscuro
const dark = ref(getThemeFromLocalStorage());

function getThemeFromLocalStorage() {
  try {
    if (window.localStorage.getItem("dark")) {
      return JSON.parse(window.localStorage.getItem("dark"));
    }
    return (
      !!window.matchMedia &&
      window.matchMedia("(prefers-color-scheme: dark)").matches
    );
  } catch (error) {
    console.warn("Error accessing localStorage:", error);
    return false;
  }
}

function setThemeToLocalStorage(value) {
  try {
    window.localStorage.setItem("dark", value);
  } catch (error) {
    console.warn("Error saving to localStorage:", error);
  }
}

function toggleTheme() {
  dark.value = !dark.value;
  setThemeToLocalStorage(dark.value);
}

// Sincroniza automáticamente el atributo `class` en el HTML
watchEffect(() => {
  document.documentElement.classList.toggle("theme-dark", dark.value);
});

export { dark, toggleTheme };
