<template>
    <div class="container">
        <ul class="fjord-navigation__parent">
            <slot />
        </ul>
    </div>
</template>

<script>
export default {
    name: 'Navigation',
    data() {
        return {};
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
        let location = window.location.pathname
            .replace('/admin/', '')
            .replace('/', '-')
            .replace('/', '-');

        console.log(location);

        let item = $(`.nav-${location}`);

        item.addClass('fjord-navigation__is-active');
        item.find($('ul')).toggle();

        $('.fjord-navigation__has-children').on('click', function() {
            $(this)
                .closest('li')
                .find('ul')
                .slideToggle(100);
        });
    }
};
</script>

<style lang="scss" scoped></style>
