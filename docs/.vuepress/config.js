const meta = {
    title: 'Fjord | Laravel base CMS/Admin-Panel',
    description:
        'Fjord is a multilanguage admin-panel/cms scaffolding package that helps you creating CRUD in seconds via Artisan-commands. It also lets you manage the "static" content of each of your websites pages (Headlines, Text, Images), as well repetitive mixed contents you define. Generating content as well as passing it to your views and retrieving it is super simple.',
    url: 'https://fjord-cms.com'
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
    ['meta', { name: 'theme-color', content: '#1584ff' }],
    ['meta', { name: 'apple-mobile-web-app-capable', content: 'yes' }]
];

module.exports = {
    title: 'Fjord',
    description: 'description',
    head,
    plugins: {},
    themeConfig: {
        repo: 'aw-studio/fjord',
        editLinks: true,
        docsDir: 'docs',
        nav: [{ text: 'Home', link: '/' }],
        sidebar: {
            '/': [
                {
                    title: 'Get started',
                    collapsable: false,
                    children: [
                        ['guide/getting-started/installation', 'Installation'],
                        ['guide/getting-started/configuration', 'Configuration']
                    ]
                },
                {
                    title: 'CRUD',
                    collapsable: false,
                    children: [
                        ['guide/crud/create-crud', 'Create CRUD-Models'],
                        ['guide/crud/crud-config', 'CRUD Config'],
                        ['guide/crud/crud-collections', 'CRUD Collections']
                    ]
                },
                {
                    title: 'Formfields',
                    collapsable: true,
                    children: [
                        ['guide/formfields/input', 'Input'],
                        ['guide/formfields/textarea', 'Textarea'],
                        ['guide/formfields/wysiwyg', 'WYSIWYG'],
                        ['guide/formfields/select', 'Select'],
                        ['guide/formfields/image', 'Image'],
                        ['guide/formfields/date-time', 'Date/Time'],
                        ['guide/formfields/boolean', 'Boolean'],
                        ['guide/formfields/range', 'Range'],
                        ['guide/formfields/block', 'Block'],
                        ['guide/formfields/has-many', 'HasMany'],
                        ['guide/formfields/relation', 'Relation']
                    ]
                }
            ]
        }
    }
};
