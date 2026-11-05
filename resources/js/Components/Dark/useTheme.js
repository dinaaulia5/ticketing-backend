import { ref, watch } from "vue";

const theme = ref(localStorage.getItem("vite-ui-theme") || "system");

const applyTheme = () => {
    const root = document.documentElement;

    root.classList.remove("light", "dark");

    if (theme.value === "system") {
        const systemTheme = window.matchMedia("(prefers-color-scheme: dark)")
            .matches
            ? "dark"
            : "light";

        root.classList.add(systemTheme);
    } else {
        root.classList.add(theme.value);
    }
};

const setTheme = (newTheme) => {
    theme.value = newTheme;
    localStorage.setItem("vite-ui-theme", newTheme);
    applyTheme();
};

const toggleTheme = () => {
    setTheme(theme.value === "dark" ? "light" : "dark");
};

applyTheme();

watch(theme, applyTheme);

export function useTheme() {
    return {
        theme,
        setTheme,
        toggleTheme,
    };
}
