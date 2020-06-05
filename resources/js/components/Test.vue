<template>
    <fj-col :width="width">
        <b-card>
            <h5>
                {{ money }} €
                <small class="text-secondary">verdient geil!</small>
            </h5>
        </b-card>
    </fj-col>
</template>

<script>
let obj = {
    user: 'abbc'
};
export default {
    name: 'TestChart',
    props: {
        money: {},
        width: {
            default() {
                return 12;
            }
        }
    }
};
</script>
