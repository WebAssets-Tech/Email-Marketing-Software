<?php

namespace Database\Seeders;

use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionSeeder extends Seeder {
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        if (env('ACTIVE_THEME') != 'moniz') {
            $plan = new SubscriptionPlan();
            $plan->name = 'free';
            $plan->duration = 1;
            $plan->emails = 1000;
            $plan->sms = 100;
            $plan->agent_limit = 5;
            $plan->description = '<p>Free starter plan to kickstart your email broadcasts, test SMTP relays, and discover core features.</p>';
            $plan->price = 0;
            $plan->status = 1;
            $plan->display = 1;
            $plan->save();
        }

        $plan = new SubscriptionPlan();
        $plan->name = 'monthly';
        $plan->duration = 1;
        $plan->emails = 10000;
        $plan->sms = 500;
        $plan->agent_limit = 10;
        $plan->description = '<p>Perfect for growing businesses running regular campaigns with reliable high-speed email delivery.</p>';
        $plan->price = 29;
        $plan->status = 1;
        $plan->display = 1;
        $plan->save();

        $plan = new SubscriptionPlan();
        $plan->name = 'pro';
        $plan->duration = 6;
        $plan->emails = 50000;
        $plan->sms = 2000;
        $plan->agent_limit = 15;
        $plan->description = '<p>Advanced throughput with multi-relay SMTP balancing and team sub-agent collaboration tools.</p>';
        $plan->price = 69;
        $plan->status = 1;
        $plan->display = 1;
        $plan->save();

        $plan = new SubscriptionPlan();
        $plan->name = 'yearly';
        $plan->duration = 12;
        $plan->emails = 250000;
        $plan->sms = 10000;
        $plan->agent_limit = 25;
        $plan->description = '<p>Enterprise-grade volume, maximum inbox deliverability, and dedicated team support at best savings.</p>';
        $plan->price = 149;
        $plan->status = 1;
        $plan->display = 1;
        $plan->save();
        //END
    }
}
