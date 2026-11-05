import { saveToken, removeToken } from "@/Utils/auth";
import api from "./api";

const authService = {
    /**
     * Login
     */
    async login(credentials) {
        const response = await api.post("/login", credentials);

        const { token, user } = response.data;

        // Simpan token
        saveToken(token);

        // Simpan data user
        localStorage.setItem("user", JSON.stringify(user));

        return response.data;
    },
    /**
     * Logout
     */
    async logout() {
        await api.post("/logout");

        removeToken();
        localStorage.removeItem("user");
    },
};

export default authService;
