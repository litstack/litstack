<?php

namespace Tests\Browser;

use Lit\Config\User\ProfileSettingsConfig;
use Lit\Config\User\UserConfig;
use Tests\FrontendTestCase;
use Tests\Traits\CreateLitUsers;

class PublishesTest extends FrontendTestCase
{
    use CreateLitUsers;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_form_profile_settings_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = lit()->url(
                lit()->config(ProfileSettingsConfig::class)->route_prefix.'/'.$this->admin->id
            );

            $browser
                ->loginAs($this->admin, 'lit')
                ->visit($url)
                ->assertSeeIn('h3', 'Profile Settings');
        });
    }

    /** @test */
    public function test_form_users_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = lit()->url(
                lit()->config(UserConfig::class)->route_prefix
            );

            $browser
                ->loginAs($this->admin, 'lit')
                ->visit($url)
                ->assertSeeIn('h3', 'Users');
        });
    }
}
