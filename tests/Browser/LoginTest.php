<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;

class LoginTest extends DuskTestCase
{
    /**
     * Test lecturer can login and logout successfully.
     */
    public function test_lecturer_login_and_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->type('nip', '123456')
                ->type('email', 'ahmad.fauzi@telkomuniversity.ac.id')
                ->press('Masuk')
                ->assertPathIs('/dashboard')
                ->assertSee('Dr. Ahmad Fauzi')
                
                // Now test logout
                ->press('.sidebar-logout-btn')
                ->assertPathIs('/');
        });
    }

    /**
     * Test admin can login and logout successfully.
     */
    public function test_admin_login_and_logout(): void
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/admin/login')
                ->type('email', 'admin@telkomuniversity.ac.id')
                ->type('password', 'password')
                ->press('Masuk')
                ->assertPathIs('/admin')
                ->assertSee('Admin TULT')
                
                // Now test logout
                ->press('.sidebar-logout-btn')
                ->assertPathIs('/admin/login');
        });
    }
}
