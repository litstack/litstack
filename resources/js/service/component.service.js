import Vue from 'vue';
import kebabCase from 'lodash/kebabCase';

const requireComponent = require.context(
    // The relative path of the components folder
    '../components',
    // Whether or not to look in subfolders
    true,
    // The regular expression used to match base component filenames
    /[A-Z]\w+\.(vue)$/
);

requireComponent.keys().forEach(fileName => {
    // Get component config
    const componentConfig = requireComponent(fileName);

    // Get PascalCase name of component
    let componentName = kebabCase(componentConfig.default.name);

    // Register component globally
    Vue.component(
        `lit-${componentName}`,
        componentConfig.default || componentConfig
    );
});
