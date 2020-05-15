import Fjord from 'fjord';
import FjordPermissions from 'fjord-permissions';

require('./service/component.service');

const store = {};

// Use permission package
Fjord.use(FjordPermissions);

new Fjord({
    store
});
