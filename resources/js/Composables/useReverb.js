window.Echo.channel("agent-recommendations").listen(
    ".recommendation.created",
    (event) => {
        console.log(event);
    },
);
