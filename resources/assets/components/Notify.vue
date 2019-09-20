<template>
    <notifications
        group="general"
        class="fjord-notifications"
        style="top: 53px;"
    >
        <template slot="body" slot-scope="props">
            <div
                :class="props.item.type"
                class="fjord-notifications__wrapper card no-fx"
            >
                <div class="d-flex">
                    <div class="fjord-notifications__icon align-self-center">
                        <fa-icon
                            icon="check-circle"
                            class="text-success"
                            v-if="props.item.type == 'success'"
                        />
                        <fa-icon
                            icon="info-circle"
                            class="text-info"
                            v-if="props.item.type == 'info'"
                        />
                        <fa-icon
                            icon="exclamation-circle"
                            class="text-warning"
                            v-if="props.item.type == 'warning'"
                        />
                        <fa-icon
                            icon="times-circle"
                            class="text-danger"
                            v-if="props.item.type == 'danger'"
                        />
                        <fa-icon
                            icon="question-circle"
                            class="text-light"
                            v-if="props.item.type == 'question'"
                        />
                    </div>
                    <div class="fjord-notifications__body">
                        <h6 class="title pb-1 mb-0">
                            {{ props.item.title }}
                        </h6>
                        <small>{{ props.item.text }}</small>
                    </div>
                </div>
                <button class="fjord-notifications__close" @click="props.close">
                    <i class="far fa-times-circle"></i>
                </button>
            </div>
        </template>
    </notifications>
</template>

<script>
export default {
    name: 'CLNotifications'
};
</script>

<style>
.fjord-notifications {
    top: 52px;
}
</style>
