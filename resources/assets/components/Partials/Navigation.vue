<template>
    <div :class="config.layout == 'horizontal' ? 'container' : ''">
        <h6 v-if="config.layout == 'vertical'" class="p-3 mb-0 text-secondary">
            Navigation
        </h6>

        <ul class="fj-navigation__parent">
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
        ...mapGetters(['baseURL', 'config'])
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
        const VERTICAL = $('#fjord-app').hasClass('vertical');
        const HORIZONTAL = !VERTICAL;

        let location = String(window.location.pathname).replace(
            this.baseURL,
            ''
        );

        if (
            location.split('/').pop() == 'edit' ||
            location.split('/').pop() == 'create'
        ) {
            location = location.split('/').shift();
        } else {
            location = location.replace('/', '-').replace('/', '-');
        }

        // let location = String(window.location.pathname)
        //     .replace(this.baseURL, '')
        //     .replace('/', '-')
        //     .replace('/', '-');

        // console.log(location);

        let item = $(`.nav-${location}`);

        item.addClass('fj-navigation__is-active');

        if (VERTICAL) {
            item.find('ul')
                .show()
                .addClass('is-open');

            $('.fj-navigation__has-children').on('click', function() {
                $(this)
                    .closest('li')
                    .toggleClass('active-parent')
                    .find('ul')
                    .slideToggle(100)
                    .toggleClass('is-open');
            });
        }

        if (HORIZONTAL) {
            $('.fj-navigation__has-children').on('mouseenter', function() {
                clearInterval(timer);
                $(this)
                    .closest('li')
                    .addClass('active-parent')
                    .find('ul')
                    .slideDown(100)
                    .addClass('is-open');
            });

            $('body').on('mouseleave', '.active-parent', function() {
                $('.fj-navigation__parent .is-open')
                    .removeClass('is-open')
                    .slideUp(100);
            });

            $('.fj-navigation__parent').on('mouseleave', function() {
                StartTimer();
            });
        }

        function StartTimer() {
            timer = setInterval(function() {
                $('.fj-navigation__parent .is-open')
                    .removeClass('is-open')
                    .slideUp(100);
                clearInterval(timer);
            }, 300);
        }
    }
};
</script>
