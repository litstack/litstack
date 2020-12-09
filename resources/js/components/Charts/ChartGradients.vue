<template>
    <div>
        <svg
            style="width: 0; height: 0; position: absolute;"
            aria-hidden="true"
            focusable="false"
        >
            <linearGradient
                id="svg-gradient-white"
                x1="0%"
                y1="0%"
                x2="0%"
                y2="100%"
            >
                <stop
                    offset="0%"
                    style="stop-color: rgb(255, 255, 255); stop-opacity: 0.2;"
                />
                <stop
                    offset="100%"
                    style="stop-color: rgb(255, 255, 255); stop-opacity: 0;"
                />
            </linearGradient>
            <linearGradient
                id="svg-gradient-primary"
                x1="0%"
                y1="0%"
                x2="0%"
                y2="100%"
            >
                <stop
                    offset="0%"
                    style="stop-color: rgb(73, 81, 242); stop-opacity: 0.2;"
                />
                <stop
                    offset="100%"
                    style="stop-color: rgb(73, 81, 242); stop-opacity: 0;"
                />
            </linearGradient>
        </svg>
    </div>
</template>

<script>
export default {
    name: 'ChartGradients',
};
</script>
