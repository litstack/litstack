<template>
    <div>
        <b-form-checkbox
            v-model="on"
            @change="
                val => {
                    this.toggle(val, true);
                }
            "
            switch
        />
    </div>
</template>
<script>
export default {
    name: 'PermissionsToggleAll',
    props: {
        item: {
            required: true,
            type: [Object, Array],
        },
        col: {
            type: Object,
        },
    },
    data() {
        return {
            on: false,
        };
    },
    methods: {
        toggle(on) {
            this.$bus.$emit('litPermissionsToggleAll', {
                on,
                group: this.group,
            });

            this.$bvToast.toast(
                this.__('permissions.messages.all_permission_updated', {
                    group: this.$te(`permissions.${this.group}`)
                        ? this.__(`permissions.${this.group}`).capitalize()
                        : this.group.capitalize(),
                }),
                {
                    variant: 'success',
                }
            );
        },
    },
    computed: {
        group() {
            return this.item.name
                .split(' ')
                .slice(1)
                .join(' ');
        },
    },
};
</script>
