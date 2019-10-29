<template>
    <div class="pb-4 fjord-form">
        <h5 class="mb-0">
            <label :for="field.id">{{ field.title }}</label>
            <b-badge v-if="field.translatable" variant="primary">
                <small>{{ lng }}</small>
            </b-badge>
        </h5>
        <div
            :class="{
                'input-group': field.type != ('relation' || 'image' || 'block')
            }"
        >
            <slot />
        </div>
        <div class="d-flex justify-content-between">
            <small
                v-if="model"
                class="form-text text-muted"
                v-html="
                    _format(field.hint || '', {
                        value: model[`${field.id}Model`]
                    })
                "
            />
            <small class="form-text text-muted">
                <template v-if="field.max && !field.min"
                    >{{ length }}/{{ field.max }}</template
                >
                <template v-if="field.max && field.min"
                    >{{ value }}</template
                >
                <template v-if="field.maxFiles"
                    >{{ value.length }}/{{ field.maxFiles }}</template
                >
            </small>
        </div>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'FormItem',
    props: {
        field: {
            type: [Object, Array],
            required: true
        },
        model: {},
        value: {}
    },
    computed: {
        ...mapGetters(['lng']),
        length() {
            return this.value ? this.value.length : 0;
        }
    }
};
</script>
