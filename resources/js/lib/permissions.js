import { usePage } from "@inertiajs/vue3";

export function can(permission) {
    const page = usePage();
    const auth = page.props.auth ?? {};

    if (
        auth.roles?.includes("Super Admin") ||
        auth.roles?.includes("super-admin")
    ) {
        return true;
    }

    const permissions = auth.permissions ?? [];

    return permissions.includes(permission);
}
