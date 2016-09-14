<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ViewLoginRegisterTest extends TestCase
{
    use DatabaseMigrations;

    public function test_view_login_page()
    {
        $pages = ["/login", "/register", "/password/reset"];

        foreach ($pages as $page)
        {
            $this->visit($page);
        }
    }
}