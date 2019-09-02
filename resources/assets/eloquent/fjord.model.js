
import EloquentModel from './model'
import TranslatableMixin from './mixins/translatable.mixin'
import FormMixin from './mixins/form.mixin'

// Mixins and Extends
const EXTENDS = FormMixin(TranslatableMixin(EloquentModel))
//const EXTENDS = TranslatableMixin(EloquentModel)

export default class FjordModel extends EXTENDS
{

}
