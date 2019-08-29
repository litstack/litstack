
import EloquentModel from './model'
import TranslatableMixin from './mixins/translatable.mixin'

// Mixins and Extends
const EXTENDS = TranslatableMixin(EloquentModel)
//const EXTENDS = TranslatableMixin(EloquentModel)

export default class TranslatableModel extends EXTENDS
{

}
