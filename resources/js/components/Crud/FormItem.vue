<template>
    <div :class="`pb-4 fjord-form fj-form-item-${field.id}`">
        <h5 class="mb-0">
            <label :for="field.id">{{ field.title }}</label>
            <b-badge v-if="field.translatable" variant="primary">
                <small>{{ language }}</small>
            </b-badge>
        </h5>
        <div
            :class="{
                'input-group': field.type != ('relation' || 'image' || 'block')
            }"
        >
            <slot />
        </div>
        <div
            class="d-flex justify-content-between"
            v-if="(hint && !noHint) || field.max || field.min || field.maxFiles"
        >
            <small
                class="form-text text-muted"
                v-html="hint && !noHint ? hint : ''"
            />

            <small class="form-text text-muted" v-if="!noMinMax">
                <template v-if="field.max && !field.min">
                    {{ length }}/{{ field.max }}
                </template>
                <template v-if="field.max && field.min">{{ value }}</template>
                <template v-if="field.maxFiles">
                    {{ value.length }}/{{ field.maxFiles }}
                </template>
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
        value: {},
        noHint: {
            type: Boolean,
            default() {
                return false;
            }
        },
        noMinMax: {
            type: Boolean,
            default() {
                return false;
            }
        }
    },
    computed: {
        ...mapGetters(['language']),
        length() {
            return this.value ? this.value.length : 0;
        },
        hint() {
            return this.field.hint || '';
        }
    }
};
</script>
