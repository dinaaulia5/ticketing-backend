import { ref, onMounted } from "vue";

const theme = ref("light");

export function useTheme() {
    const setTheme = (value) => {
        theme.value = value;

        localStorage.setItem("theme", value);

        if (value === "dark") {
            document.documentElement.classList.add("dark");
        } else {
            document.documentElement.classList.remove("dark");
        }
    };

    const toggleTheme = () => {
        setTheme(theme.value === "dark" ? "light" : "dark");
    };

    onMounted(() => {
        const saved = localStorage.getItem("theme") || "light";
        setTheme(saved);
    });

    return {
        theme,
        toggleTheme,
    };
}
