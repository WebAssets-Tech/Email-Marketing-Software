<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\SubscriptionPlan;
use App\Models\User;
use Carbon\Carbon;

class UpdateDefaultPlansAndCleanDescriptions extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        try {
            // 1. Clean existing dummy text in subscription_plans table
            $plans = DB::table('subscription_plans')->get();
            foreach ($plans as $plan) {
                $desc = strtolower(strip_tags($plan->description ?? ''));
                if (empty($desc) || str_contains($desc, 'lorem') || str_contains($desc, 'dummy text')) {
                    $name = strtolower($plan->name ?? '');
                    if (str_contains($name, 'free') || (float) $plan->price <= 0) {
                        $clean = '<p>Free starter plan to kickstart your email broadcasts, test SMTP relays, and discover core features.</p>';
                    } elseif (str_contains($name, 'year') || (int) $plan->duration >= 12) {
                        $clean = '<p>Enterprise-grade volume, maximum inbox deliverability, and dedicated team support at best savings.</p>';
                    } elseif (str_contains($name, 'pro')) {
                        $clean = '<p>Advanced throughput with multi-relay SMTP balancing and team sub-agent collaboration tools.</p>';
                    } else {
                        $clean = '<p>Perfect for growing businesses running regular campaigns with reliable high-speed email delivery.</p>';
                    }

                    DB::table('subscription_plans')->where('id', $plan->id)->update([
                        'description' => $clean,
                    ]);
                }
            }

            // 2. Ensure at least one Free Tier Plan exists
            $freeCount = DB::table('subscription_plans')
                ->where('price', '<=', 0)
                ->orWhere('name', 'like', '%free%')
                ->count();

            if ($freeCount == 0) {
                DB::table('subscription_plans')->insert([
                    'name' => 'free',
                    'duration' => '1',
                    'emails' => '1000',
                    'sms' => '100',
                    'agent_limit' => '5',
                    'description' => '<p>Free starter plan to kickstart your email broadcasts, test SMTP relays, and discover core features.</p>',
                    'price' => 0,
                    'status' => 1,
                    'display' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // 3. For any customer user without an EmailSMSLimitRate record, initialize their free tier
            $freePlan = DB::table('subscription_plans')
                ->where('price', '<=', 0)
                ->where('status', 1)
                ->first();

            $emails = $freePlan ? (int) $freePlan->emails : 1000;
            $sms = $freePlan ? (int) $freePlan->sms : 100;
            $duration = $freePlan ? (int) $freePlan->duration : 1;
            $agent = $freePlan && isset($freePlan->agent_limit) ? (int) $freePlan->agent_limit : 5;

            $usersWithoutLimit = DB::table('users')
                ->where('user_type', '!=', 'Admin')
                ->whereNotExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('email_s_m_s_limit_rates')
                        ->whereRaw('email_s_m_s_limit_rates.owner_id = users.id');
                })
                ->get();

            foreach ($usersWithoutLimit as $u) {
                DB::table('email_s_m_s_limit_rates')->insert([
                    'owner_id' => $u->id,
                    'email' => (string) $emails,
                    'sms' => (string) $sms,
                    'agent' => (string) $agent,
                    'from' => Carbon::now(),
                    'to' => Carbon::now()->addMonths($duration),
                    'status' => 1,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        } catch (\Throwable $th) {
            // Silently handle if tables not migrated yet
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        // No-op
    }
}
