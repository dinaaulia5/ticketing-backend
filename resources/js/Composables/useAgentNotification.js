import { onMounted, onUnmounted } from "vue";
import { toast } from "vue-sonner";

export function useAgentNotification() {
    let channel = null;

    onMounted(() => {
        channel = window.Echo.channel("agent-recommendations");

        channel.listen(".recommendation.created", (event) => {
            console.log("Recommendation:", event);

            toast.success(event.title, {
                description: event.message,
            });
        });
    });

    onUnmounted(() => {
        if (channel) {
            window.Echo.leave("agent-recommendations");
        }
    });
}
