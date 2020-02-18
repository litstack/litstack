<template>
    <fj-base-container>
        <fj-base-header
            :title="formConfig.names.title.singular"
            :back="formConfig.back_route"
            :back-text="formConfig.back_text">

            <div
                slot="navigation"
                class="indent sm"
                v-if="nearItems">

                <b-button
                    :href="`${baseURL}${formConfig.names.table}/${nearItems.previous}/edit`"
                    :disabled="!nearItems.previous"
                    variant="transparent"
                    size="sm">
                    <fa-icon icon="arrow-left"/>
                </b-button>
                <b-button
                    :href="`${baseURL}${formConfig.names.table}/${nearItems.next}/edit`"
                    :disabled="!nearItems.next"
                    variant="transparent"
                    size="sm">
                    <fa-icon icon="arrow-right"/>
                </b-button>

            </div>

            <div slot="actions" class="indent sm">

                <components
                    v-for="(component, key) in actions"
                    :key="key"
                    :is="component"
                    :config="formConfig"/>

            </div>
        </fj-base-header>

    </fj-base-container>
</template>

<script>
export default {
}
</script>
