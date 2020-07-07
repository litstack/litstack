export default {
    /**
     * Resolve depencies.
     *
     * @param  {array}     dependencies
     * @return {undefined}
     */
    resolveDependecies(dependencies) {
        if (!dependencies || !Array.isArray(dependencies)) {
            return;
        }

        let fulfilled = true;
        for (let i in dependencies) {
            let dependency = dependencies[i];

            let conditionMethod = this.getDependencyConditionMethod(dependency);

            if (!conditionMethod) {
                continue;
            }

            let conditionFulfilled = conditionMethod(dependency);

            if (dependency.condition.startsWith('or') && !conditionFulfilled) {
                continue;
            }

            fulfilled = conditionFulfilled;
        }

        this.fulfillsConditions = fulfilled;
    },

    /**
     * Get dependency condition method.
     */
    getDependencyConditionMethod(dependency) {
        return this[
            'fulfills' + dependency.condition.replace('or', '').capitalize()
        ];
    },

    /**
     * Determinces if when condition is fulfilled.
     */
    fulfillsWhen(dependency) {
        return this.model[dependency.attribute] == dependency.value;
    },

    /**
     * Determinces if whenContains condition is fulfilled.
     */
    fulfillsWhenContains(dependency) {
        let value = this.model[dependency.attribute];

        if (!value) {
            return;
        }

        if (typeof value == 'string' || typeof value == 'array') {
            return value.includes(dependency.value);
        }
    }
};
