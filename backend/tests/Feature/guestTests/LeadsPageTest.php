<?php

namespace Tests\Feature\guestTests;

use App\Models\Lead;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Tests\TestsUtility;

class LeadsPageTest extends TestCase
{
    use RefreshDatabase;

    // public TestsUtility $utility;
    // public array $leadRequestAttributes;

    // protected function setUp() : void
    // {
        // parent::setUp();
        // $this->utility = new TestsUtility();
        // $this->leadRequestAttributes = $this->utility->leadRequestAttributes();
    // }

    public function test_guest_user_can_get_leads()
    {
        Lead::factory()->count(25)->create(['approved' => 1]);
        Lead::factory()->count(3)->create(['approved' => 0]);
        $this->assertDatabaseCount('leads', 28);

        $resp = $this->getJson('api/leads');

        $resp->assertOk();
        $this->assertCount(25, json_decode($resp->getContent())->leads);
    }

    public function test_guest_user_dont_get_all_leads_attributes()
    {
        Lead::factory()->count(10)->create(['approved' => 1]);

        $resp = $this->getJson('api/leads');

        $resp->assertOk();
        $leads = json_decode($resp->getContent())->leads;
        $this->assertCount(10, $leads);
        foreach ($leads as $lead) {
            $this->assertObjectHasAttribute('name', $lead);
            $this->assertObjectNotHasAttribute('surname', $lead);
            $this->assertObjectNotHasAttribute('email', $lead);
            $this->assertObjectNotHasAttribute('phone', $lead);
        }
    }

//    public function test_guest_user_view_purchase_button()
//    {
//        //
//    }
//    public function test_guest_user_can_view_Bonifico_payment_form()
//    {
//        //
//    }
//    public function test_guest_user_cant_view_purchase_button_if_lead_is_not_available_anymore()
//    {
//        //
//    }
//    public function test_()
//    {
//        //
//    }

}
