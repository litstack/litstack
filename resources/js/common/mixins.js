import VueI18n from 'vue-i18n';

export default {
    // Vue mixins that can be used in templates

    // formats string with values
    // example params:
    // string: 'Search {name}',
    // values: {name: "Employees"}
    _format(string, values) {
        let messages = { f: { s: string } };
        const formatter = new VueI18n({ locale: 'f', messages });
        console.log(string, values);
        return formatter._t.apply(
            formatter,
            ['s', formatter.locale, formatter._getMessages(), this].concat(
                values
            )
        );
    }
};
