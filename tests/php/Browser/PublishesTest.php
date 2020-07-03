<?php

namespace FjordTest\Browser;

use FjordApp\Config\Form\Collections\SettingsConfig;
use FjordApp\Config\Form\Pages\HomeConfig;
use FjordApp\Config\User\ProfileSettingsConfig;
use FjordApp\Config\User\UserConfig;
use FjordTest\FrontendTestCase;
use FjordTest\Traits\CreateFjordUsers;

class PublishesTest extends FrontendTestCase
{
    use CreateFjordUsers;

    public function setUp(): void
    {
        parent::setUp();
    }

    /** @test */
    public function test_form_settings_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config(SettingsConfig::class)->route_prefix
            );
            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Settings');
        });
    }

    /** @test */
    public function test_form_home_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config(HomeConfig::class)->route_prefix
            );

            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Home');
        });
    }

    /** @test */
    public function test_form_profile_settings_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config(ProfileSettingsConfig::class)->route_prefix.'/'.$this->admin->id
            );

            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Profile Settings');
        });
    }

    /** @test */
    public function test_form_users_config()
    {
        $this->skipIfChromedriverIsNotRunning();

        $this->browse(function ($browser) {
            $url = fjord()->url(
                fjord()->config(UserConfig::class)->route_prefix
            );

            $browser
                ->loginAs($this->admin, 'fjord')
                ->visit($url)
                ->assertSeeIn('h3', 'Users');
        });
    }
}
