<template>
    <div class="container">
        <ul class="fjord-navigation__parent">
            <slot />
        </ul>
    </div>
</template>

<script>
import { mapGetters } from 'vuex';

export default {
    name: 'Navigation',
    data() {
        return {};
    },
    computed: {
        ...mapGetters(['baseURL'])
    },
    methods: {
        navClasses(item) {
            if (!item.hasOwnProperty('children')) {
                return `nav-${item.link.replace('/', '_')} `;
            }
            let classes = _.map(item.children, 'link');
            let string = '';
            for (var i = 0; i < classes.length; i++) {
                string += `nav-${classes[i].replace('/', '_')} `;
            }
            return string;
        }
    },
    mounted() {
        let timer;

        let location = String(window.location.pathname)
            .replace(this.baseURL, '')
            .replace('/', '-')
            .replace('/', '-');

        console.log(location);

        let item = $(`.nav-${location}`);

        item.addClass('fjord-navigation__is-active');
        //item.find($('ul')).toggle();

        $('.fjord-navigation__has-children').on('mouseenter', function() {
            clearInterval(timer);
            $(this)
                .closest('li')
                .addClass('active-parent')
                .find('ul')
                .slideDown(100)
                .addClass('is-open');
        });

        $('body').on('mouseleave', '.active-parent', function() {
            $('.fjord-navigation__parent .is-open')
                .removeClass('is-open')
                .slideUp(100);
        });

        $('.fjord-navigation__parent').on('mouseleave', function() {
            StartTimer();
        });

        function StartTimer() {
            timer = setInterval(function() {
                $('.fjord-navigation__parent .is-open')
                    .removeClass('is-open')
                    .slideUp(100);
                clearInterval(timer);
            }, 300);
        }
    }
};
</script>

<style lang="scss" scoped></style>
