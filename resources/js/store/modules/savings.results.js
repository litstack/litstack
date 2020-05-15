export default class ResultsHandler {
    /**
     * Create new ResultsHandler instance.
     *
     * @param {*} results
     *
     * @return {Proxy}
     */
    constructor(results) {
        this.results = results;
    }

    /**
     * Determine if there are any errors in the results.
     *
     * @return {Boolean}
     */
    _hasErrors() {
        for (let i in this.results) {
            if (this.results[i] instanceof Error) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine if there are any succeeded results.
     *
     * @return {Boolean}
     */
    _hasSucceeded() {
        for (let i in this.results) {
            if (this.results[i] instanceof Error) {
                continue;
            }
            return true;
        }
        return false;
    }

    /**
     * Get failed results.
     *
     * @return {Array}
     */
    getFailed() {
        let errors = [];
        for (let i in this.results) {
            let result = this.results[i];
            if (result instanceof Error) {
                errors.push(result);
            }
        }
        return errors;
    }

    /**
     * Get succeeded results.
     *
     * @return {Array}
     */
    getSucceeded() {
        let succeeded = [];
        for (let i in this.results) {
            let result = this.results[i];
            if (result instanceof Error) {
                continue;
            }
            succeeded.push(result);
        }
        return succeeded;
    }

    /**
     * Find result by method and url.
     *
     * @param {Object} query
     */
    find(method, url) {
        return this._find(method, url, this.results);
    }

    /**
     * Find failed result by method and url.
     *
     * @param {Object} query
     */
    findFailed(method, url) {
        return this._find(method, url, this.getFailed());
    }

    /**
     * Find succeeded result by method and url.
     *
     * @param {Object} query
     */
    findSucceeded(method, url) {
        return this._find(method, url, this.getSucceeded());
    }

    /**
     * Find succeeded result by method and url.
     *
     * @param {Object} query
     */
    hasFailed(method, url) {
        if (!method && !url) {
            return this._hasErrors();
        }
        return this.findFailed(method, url) != null;
    }

    /**
     * Find succeeded result by method and url.
     *
     * @param {Object} query
     */
    hasSucceeded(method, url) {
        if (!method && !url) {
            return this._hasSucceeded();
        }
        return this.findSucceeded(method, url) != null;
    }

    /**
     * Find result by method and url for given results.
     *
     * @param {Object} query
     */
    _find(method, url, results) {
        for (let i in results) {
            let result = results[i];

            if (url != result.config.url) {
                continue;
            }
            if (method.toLowerCase() != result.config.method) {
                continue;
            }

            return result;
        }
    }
}
