<?php

namespace Fjord\User\Controllers;

use Illuminate\Http\Request;
use Fjord\Support\IndexTable;
use Fjord\Support\Facades\Config;
use Fjord\Crud\Controllers\Concerns\ManagesCrudValidation;
use Fjord\Crud\Controllers\Concerns\ManagesCrudUpdateCreate;

class ProfileController
{
    use ManagesCrudValidation, ManagesCrudUpdateCreate;

    /**
     * Show profile update.
     *
     * @return void
     */
    public function show()
    {
        $config = Config::get('user.profile_settings')->get('form');

        return view('fjord::app')
            ->withComponent('fj-profile-settings')
            ->withProps([
                'model' => crud(fjord_user()),
                'config' => $config
            ]);
    }

    /**
     * Update profile.
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        $user = fjord_user() ?? abort(404);

        $user->update($request->all());
    }

    /**
     * Update modal field.
     *
     * @param Request $request
     * @param int $id
     * @param string $modal_id
     * @return CrudJs
     */
    public function updateModal(Request $request, $modal_id)
    {
        $modal = Config::get('user.profile_settings')
            ->form
            ->findField($modal_id) ?? abort(404);

        $request->validate(
            $modal->form->getRules($request),
            __f('validation'),
            $modal->getRegisteredFields()->mapWithKeys(function ($field) {
                return [$field->local_key => $field->title];
            })->toArray()
        );

        fjord_user()->update($this->filterRequestAttributes($request, $modal->getRegisteredFields()));

        return crud(fjord_user());
    }

    /**
     * Fetch index.
     *
     * @param Request $request
     * @return array
     */
    public function sessions(Request $request)
    {
        return IndexTable::query(fjord_user()->sessions()->getQuery())
            ->request($request)
            ->get();
    }
}
