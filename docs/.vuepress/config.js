const meta = {
    title: 'Fjord | Laravel Content-Administration',
    description:
        'Fjord is a multilanguage admin-panel scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.',
    url: 'https://www.fjord-admin.com'
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
    [
        'link',
        {
            rel: 'icon',
            type: 'image/png',
            sizes: '32x32',
            href: '/favicons/favicon-32x32.png'
        }
    ],
    [
        'link',
        {
            rel: 'icon',
            type: 'image/png',
            sizes: '16x16',
            href: '/favicons/favicon-16x16.png'
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
        logo: '/logo.svg',
        repo: 'aw-studio/fjord',
        editLinks: true,
        docsDir: 'docs',
        nav: [
            {
                text: 'Documentation',
                link: '/docs/getting-started/installation'
            },
            { text: '🕹 Demo', link: 'https://demo.fjord-admin.com/admin' },
            { text: 'Discord', link: 'https://discord.gg/u4qpb5P' }
        ],
        sidebar: {
            '/': [
                {
                    title: 'Get started',
                    collapsable: false,
                    children: [
                        //['docs/getting-started/introduction', 'Introduction'],
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
                        ['docs/crud/models', 'Models'],
                        ['docs/crud/forms', 'Forms'],
                        ['docs/crud/config-index', 'Index Config'],
                        ['docs/crud/config-show', 'Show Config'],
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
                    title: 'Charts',
                    collapsable: false,
                    children: [['docs/charts/basics', 'Basics']]
                },
                {
                    title: 'Frontend',
                    collapsable: false,
                    children: [
                        ['docs/frontend/javascript', 'Javascript'],
                        ['docs/frontend/vue', 'Vue'],
                        ['docs/frontend/components', 'Vue Components']
                    ]
                }
                /*
                {
                    title: 'Package Development',
                    collapsable: true,
                    children: [['docs/package/basics', 'Basics']]
                }
                */
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
