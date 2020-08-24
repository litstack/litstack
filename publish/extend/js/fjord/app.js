import Lit from 'lit';
import LitPermissions from 'lit-permissions';

require('./service/component.service');

const store = {};

// Use permission package
Lit.use(LitPermissions);

new Lit({
	store,
});
