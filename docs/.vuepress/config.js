const meta = {
    title: 'Fjord | Laravel Content-Administration',
    description:
        'Fjord is a multilanguage admin-panel/cms scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.',
    url: 'https://laravel-fjord.com'
};

let head = [
    [
        'link',
        {
            href:
                '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,600|Roboto Mono',
            rel: 'stylesheet',
            type: 'text/css'
        }
    ],
    ['link', { rel: 'icon', href: `/vue-logo.png` }],
    ['meta', { name: 'theme-color', content: '#4951f2' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }]
];

module.exports = {
    base: '/',
    title: 'Fjord',
    description: 'description',
    head,
    plugins: [
        [
            '@vuepress/google-analytics',
            {
                ga: 'UA-77559383-19'
            }
        ],
        [
            'vuepress-plugin-clean-urls',
            {
                normalSuffix: '/',
                indexSuffix: '/',
                notFoundPath: '/404.html'
            }
        ]
    ],
    themeConfig: {
        repo: 'aw-studio/fjord',
        editLinks: true,
        docsDir: 'docs',
        nav: [
            {
                text: 'Documentation',
                link: '/docs/getting-started/introduction.html'
            },
            { text: '🕹 Demo', link: 'https://playground.fjord-admin.com' }
        ],
        sidebar: {
            '/': [
                {
                    title: 'Get started',
                    collapsable: false,
                    children: [
                        ['docs/getting-started/introduction', 'Introduction'],
                        ['docs/getting-started/installation', 'Installation'],
                        ['docs/getting-started/configuration', 'Configuration']
                    ]
                },
                {
                    title: 'Basics',
                    collapsable: false,
                    children: [
                        ['docs/basics/navigation', 'Navigation'],
                        ['docs/basics/localization', 'Localization'],
                        ['docs/basics/vue', 'Extend With Vue'],
                        ['docs/basics/helpers', 'Helpers']
                    ]
                },
                {
                    title: 'CRUD',
                    collapsable: false,
                    children: [
                        ['docs/crud/create-crud', 'Models'],
                        ['docs/crud/create-forms', 'Forms'],
                        ['docs/crud/config-index', 'Index Config'],
                        ['docs/crud/config-form', 'Form Config'],
                        ['docs/crud/config-table', 'Table']
                    ]
                },
                {
                    title: 'Fields',
                    collapsable: true,
                    children: [
                        ['docs/fields/introduction', 'Introduction'],
                        ['docs/fields/validation', 'Validation'],
                        ['docs/fields/input', 'Input'],
                        ['docs/fields/textarea', 'Textarea'],
                        ['docs/fields/wysiwyg', 'WYSIWYG'],
                        ['docs/fields/boolean', 'Boolean'],
                        ['docs/fields/checkboxes', 'Checkboxes'],
                        ['docs/fields/range', 'Range'],
                        ['docs/fields/select', 'Select'],
                        ['docs/fields/date-time', 'Date/Time'],
                        ['docs/fields/image', 'Image'],
                        ['docs/fields/icon', 'Icon'],
                        ['docs/fields/code', 'Code Editor'],
                        ['docs/fields/password', 'Password'],
                        ['docs/fields/relation', 'Relation'],
                        ['docs/fields/one_relation', 'oneRelation'],
                        ['docs/fields/many_relation', 'manyRelation'],
                        ['docs/fields/block', 'Block'],
                        ['docs/fields/modal', 'Modal'],
                        ['docs/fields/component', 'Component']
                    ]
                },
                {
                    title: 'Frontend',
                    collapsable: false,
                    children: [
                        ['docs/frontend/javascript', 'Javascript'],
                        ['docs/frontend/vue', 'Vue'],
                        ['docs/frontend/components', 'Vue Components']
                    ]
                },
                {
                    title: 'Package Development',
                    collapsable: true,
                    children: [['docs/package/basics', 'Basics']]
                }
                /*
                {
                    title: 'Digging Deeper',
                    collapsable: false,
                    children: [
                        ['docs/deeper/package', 'Package Development'],
                        ['docs/deeper/lifecycle', 'Lifecycle']
                    ]
                }
                */
            ]
        }
    }
};
