<template>
    <notifications group="general" class="aw-notifications">
        <template slot="body" slot-scope="props">
            <div
                :class="props.item.type"
                class="aw-notifications__wrapper card"
            >
                <div class="d-flex">
                    <div class="aw-notifications__icon align-self-center">
                        <i
                            class="far fa-grin-alt text-success"
                            v-if="props.item.type == 'aw-success'"
                        ></i>
                        <i
                            class="far fa-angry text-danger"
                            v-if="props.item.type == 'aw-error'"
                        ></i>
                        <i
                            class="far fa-meh-rolling-eyes"
                            v-if="props.item.type == 'aw-warning'"
                        ></i>
                    </div>
                    <div class="aw-notifications__body">
                        <h6 class="title pb-1 mb-0">
                            {{ props.item.title }}
                        </h6>
                        <small>{{ props.item.text }}</small>
                    </div>
                </div>
                <button class="aw-notifications__close" @click="props.close">
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
