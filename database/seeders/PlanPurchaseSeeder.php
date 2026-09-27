<?php

namespace Database\Seeders;

use App\Models\EmailSMSLimitRate;
use App\Models\PlanPurchased;
use App\Models\UserSentLimitPlan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanPurchaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $freePlanData = [
            'plan_name' => 'Free',
            'status' => 1,
        ];
        DB::transaction(function () use ($now, $freePlanData) {
            EmailSMSLimitRate::create([
                'owner_id' => 1,
                'email' => 100,
                'sms' => 100,
                'from' => $now,
                'to' => $now->addDay(),
                'status' => true,
            ]);

            PlanPurchased::create(array_merge([
                'user_id' => 2,
                'plan_id' => 1,
                'price' => 0,
                'invoice' => '20252438',
                'gateway' => 'free',
            ], $freePlanData));

            UserSentLimitPlan::create(array_merge([
                'owner_id' => 2,
                'limit' => 1000,
                'from' => $now->subMonths(1),
                'to' => $now->addMonths(1),
            ], $freePlanData));

            EmailSMSLimitRate::create([
                'owner_id' => 2,
                'email' => 1000,
                'sms' => 1000,
                'from' => $now->subMonths(1),
                'to' => $now->addMonths(1),
                'status' => 1,
            ]);
        });
    }
}
