<template>
    <notifications group="general" class="fjord-notifications">
        <template slot="body" slot-scope="props">
            <div
                :class="props.item.type"
                class="fjord-notifications__wrapper card"
            >
                <div class="d-flex">
                    <div class="fjord-notifications__icon align-self-center">
                        <i
                            class="far fa-grin-alt text-success"
                            v-if="props.item.type == 'fjord-success'"
                        ></i>
                        <i
                            class="far fa-angry text-danger"
                            v-if="props.item.type == 'fjord-error'"
                        ></i>
                        <i
                            class="far fa-meh-rolling-eyes"
                            v-if="props.item.type == 'fjord-warning'"
                        ></i>
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
