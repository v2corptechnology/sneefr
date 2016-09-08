<?php

class ViewStaticPagesTest extends TestCase
{
    public function test_can_view_help()
    {
        $this->visit('/help')
            ->see(trans('help.header_big'))
            ->see('FAQ');
    }

    public function test_can_view_terms()
    {
        $this->visit('/terms')
            ->see(trans('terms.heading'))
            ->see(trans('terms.sections.3.heading'));
    }

    public function test_can_view_privacy()
    {
        $this->visit('/privacy')
            ->see(trans('privacy.heading'))
            ->see(trans('privacy.sections.3.heading'));
    }

    public function test_can_view_more()
    {
        $this->visit('/more')
            ->assertResponseOk();
    }

    public function test_can_view_pricing()
    {
        $this->visit('/pricing')
            ->see(trans('pricing.header.heading'))
            ->see(trans('pricing.table.items.unlimited_ads.heading'));
    }
}
