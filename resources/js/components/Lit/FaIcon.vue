<template>
    <i :class="classes"></i>
</template>

<script>
export default {
    name: 'FaIcon',
    props: {
        icon: { type: [String, Array], required: true },
        size: { type: String },
        rotate: { type: String },
        flip: { type: String },
        spin: {
            type: Boolean,
            default: () => false,
        },
        pulse: {
            type: Boolean,
            default: () => false,
        },
    },
    computed: {
        classes() {
            let classes = [];
            if (Array.isArray(this.icon)) {
                classes.push(this.icon[0]);
                classes.push(`fa-${this.icon[1]}`);
            } else {
                classes.push(`fas`);
                classes.push(`fa-${this.icon}`);
            }

            if (this.size) {
                classes.push(`fa-${this.size}`);
            }

            if (this.flip) {
                classes.push(`fa-flip-${this.size}`);
            }

            if (this.rotate) {
                classes.push(`fa-rotate-${this.size}`);
            }

            if (this.spin) {
                classes.push(`fa-spin`);
            }

            if (this.pulse) {
                classes.push(`fa-pulse`);
            }

            return classes;
        },
    },
};
</script>
