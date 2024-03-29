<?php

class Test
{
    public function saveTest()
    {
        $campaign = $api->getCampaign($campaign_id);
        $db_test = new Test();
        $db_test->name = $campaign->{CampaignFields::NAME};
        $db_test->status = $campaign->{CampaignFields::EFFECTIVE_STATUS};
        $db_test->budget = $campaign->{CampaignFields::LIFETIME_BUDGET};
        $db_test->save();

        $campaign->{CampaignFields::LIFETIME_BUDGET} = 'a';
        $campaign->{CampaignFields::LIFETIME_BUDGET} = 'b';
    }
}

class Test
{
    public function saveTest()
    {
        $user = [];
        $tests = $api->getTests();
        foreach ($tests as $key => $test) {
            $db_test[] = [
                'test' => $test->{'Id'},
                'another_test' => $user->another_test,
                'user_id' => $user->user_id,
                'blah' => $user->blah,
                'name' => $test->{'Name'},
                'status' => strtoupper($test->{'Status'}),
                'budget' => $test->{'BudgetType'} === 'DailyBudgetStandard' ? $test->{'DailyBudget'} : 0,
                'updated_at' => now(),
            ];
        }
    }
}