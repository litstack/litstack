<template>
    <fj-container>
        <fj-header :title="'Dashboard'"></fj-header>
        <b-row>
            <b-col cols="4" v-for="(item, index) in cards" :key="index">
                <div class="card">
                    <div class="card-header">
                        <b>{{ item.title }}</b>
                    </div>
                    <div class="card-body">
                        <p>
                            {{ item.text }}
                        </p>
                        <div class="btn-group" role="group">
                            <a
                                v-for="link in item.links"
                                :href="link.target"
                                :class="link.class"
                                v-html="link.text"
                            >
                            </a>
                        </div>
                    </div>
                </div>
            </b-col>
        </b-row>
    </fj-container>
</template>

<script>
export default {
    name: 'Dashboard',
    props: {
        cards: {
            type: Array
        }
    }
};
</script>
