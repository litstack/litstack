<template>
    <div class="d-flex justify-content-end align-content-center">
        <div class="btn-group btn-group-sm" role="group">
            <button
                class="btn"
                :class="[
                    {
                        'btn-primary': lng == language,
                        'btn-secondary': lng != language
                    }
                ]"
                v-for="lng in config.languages"
                @click="switchLanguage(lng)"
            >
                {{ lng }}
            </button>
        </div>
    </div>
</template>

<script>

export default {
    name: 'PageLanguage',
    props: {
        config: {
            type: Object,
            required: true
        },
        language: {
            type: String
        },
        payload: {
            type: Object
        }
    },
    data() {
        return {
            lng: this.language
        };
    },
    methods: {
        switchLanguage(language) {
            this.lng = language;
            this.$nextTick(() => {
                console.log(this.payload);
            });
        },
        activeLanguage(lng) {
            return lng == this.language;
        }
    },
    watch: {
        lng(val) {
            this.$emit('input', val);
        }
    }
};
</script>

<style lang="css"></style>
