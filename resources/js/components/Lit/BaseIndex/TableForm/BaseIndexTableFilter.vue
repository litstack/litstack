<template>
    <span>
        <b-dropdown
            v-for="(scopes, title) in filter"
            :key="`filter-${title}`"
            style="margin: 0 0.25rem;"
            :variant="filterVariant(scopes)"
        >
            <template #button-content>
                {{ title }}
            </template>

            <template v-if="!!scopes.fields">
                <b-dropdown-text
                    v-for="(field, key) in scopes.fields"
                    :key="`filter-${title}-${key}`"
                >
                    <lit-field
                        class="lit-index-filter-field"
                        :key="key"
                        :field="field"
                        :model-id="0"
                        :model="getModelFor(title)"
                        :save="false"
                        @changed="(value) => updateFilter(field, title, value)"
                    />
                </b-dropdown-text>
            </template>
            <b-dropdown-text
                v-else
                v-for="(item, scope) in scopes"
                :key="`filter-${title}-${scope}`"
            >
                <b-form-checkbox
                    @change="toggleFilter(scope)"
                    :checked="filterActive(scope)"
                >
                    {{ item }}
                </b-form-checkbox>
            </b-dropdown-text>
        </b-dropdown>
        <!-- <b-dropdown
			right
			:variant="filterVariant"
			:disabled="!filter"
			class="dropdown-md-square-mobile"
			no-caret
		>
			<template v-slot:button-content>
				<lit-fa-icon icon="filter" />
				<span class="d-none d-lg-inline-block">{{
					__('base.filter')
				}}</span>
			</template>

			<b-dropdown-group
				:header="key"
				v-for="(group, key) in filter"
				:key="key"
			>
				<b-dropdown-item-button
					v-for="(item, index) in group"
					@click="toggleFilter(index)"
					:key="item"
					:active="filterActive(index)"
				>
					{{ item }}
				</b-dropdown-item-button>
			</b-dropdown-group>
			<b-dropdown-divider></b-dropdown-divider>
			<b-dropdown-item-button @click="resetFilter">
				reset
			</b-dropdown-item-button>
		</b-dropdown> -->
    </span>
</template>

<script>
import { mapGetters } from 'vuex';
export default {
    name: 'BaseIndexTableFilter',
    props: {
        filter: {
            required: true,
            type: [Object, Array],
        },
        filterScopes: {
            type: Array,
            default() {
                return [];
            },
        },
    },
    data() {
        return {
            filter_scope: '',
            models: {},
        };
    },
    methods: {
        /**
         * Update filter.
         */
        updateFilter(field, title, value) {
            this.$emit('addFilter', {
                filter: field.filter,
                title: title,
                values: {
                    [field.id]: value,
                },
                attributeNames: {
                    [field.id]: field.title,
                },
            });
        },
        getModelFor(title) {
            if (title in this.models) {
                return this.models[title];
            }

            return (this.models[title] = this.crud({
                attributes: {},
                translatable: false,
                cast: true,
            }));
        },
        toggleFilter(filter) {
            if (this.filterActive(filter)) {
                this.$emit('removeFilter', filter);
            } else {
                this.$emit('addFilter', filter);
            }
        },
        resetFilter() {
            this.$emit('resetFilter', null);
        },
        filterActive(filter) {
            return this.filterScopes.includes(filter);
        },
        filterVariant(scopes) {
            for (let scope in scopes) {
                if (this.filterActive(scope)) {
                    return 'primary';
                }
            }

            return 'outline-secondary';
        },
    },
    computed: {},
};
</script>
<style lang="scss">
.lit-index-filter-field {
    padding-left: 0 !important;
    padding-right: 0 !important;

    > div {
        padding-bottom: 0 !important;
    }
}
</style>
