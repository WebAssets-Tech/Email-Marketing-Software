<?php

use Carbon\Carbon;
use App\Models\Job;
use App\Models\Seo;
use App\Models\Sms;
use App\Models\Blog;
use App\Models\Page;
use App\Models\User;
use App\Models\Agent;
use App\Models\Queue;
use App\Models\ApiKey;
use App\Models\Coupon;
use App\Models\SmsLog;
use App\Models\Country;
use App\Models\MailLog;
use App\Models\Campaign;
use App\Models\Currency;
use App\Models\Frontend;
use App\Models\Language;
use App\Models\FailedJob;
use App\Models\EmailGroup;
use App\Models\SmsBuilder;
use App\Models\SmtpServer;
use NumberFormatter as NF;
use App\Models\CampaignLog;
use App\Models\VoiceServer;
use Illuminate\Support\Str;
use App\Models\BouncedEmail;
use App\Models\ChatProvider;
use App\Models\EmailContact;
use App\Models\EmailService;
use App\Models\EmailTracker;
use App\Models\QueueMonitor;
use App\Utils\JsonExtractor;
use App\Models\CampaignEmail;
use App\Models\PlanPurchased;
use App\Models\ScheduleEmail;
use App\Models\SupportTicket;
use App\Models\ChatGPTMessage;
use App\Models\EmailListGroup;
use App\Models\FrontendModule;
use App\Models\UserSentRecord;
use App\Models\FrontendFeature;
use App\Models\ImportantNotice;
use App\Models\InfobipScenario;
use App\Models\TemplateBuilder;
use App\Services\Avatar\Avatar;
use App\Models\SubscriptionPlan;
use App\Models\UserNotification;
use App\Models\EmailSMSLimitRate;
use App\Models\OrganizationSetup;
use App\Models\UserSentLimitPlan;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

function menu()
{
    $menu = [
        'dashboard' => [
            'icon' => 'home',
            'route_name' => 'dashboard',
            'active_route_name' => 'dashboard',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Dashboard',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 1,
        ], // dashboard
        'marketplace' => [
            'icon' => 'shopping-bag',
            'route_name' => 'marketplace.index',
            'active_route_name' => 'marketplace',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Marketplace',
            'marketplace' => env('MARKETPLACE') == 'YES' ? 'YES' : 'NO',
            'counter_badge' => marketplace_today_new_buyer_count(),
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 2,
        ], // marketplace
        'agents' => [
            'icon' => 'users',
            'route_name' => 'agents.index',
            'active_route_name' => 'agents',
            'permission' => 'AdminCustomer',
            'params' => [],
            'title' => 'Agents',
            'marketplace' => env('MARKETPLACE') == 'YES' ? 'YES' : 'NO',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 2,
        ], // marketplace
        'coupon' => [
            'icon' => 'percent',
            'route_name' => 'coupon.index',
            'active_route_name' => 'coupon',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Coupons',
            'marketplace' => env('MARKETPLACE') == 'YES' ? 'YES' : 'NO',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 2,
        ], // marketplace
        'setup' => [
            'icon' => 'grid',
            'route_name' => null,
            'active_route_name' => 'setup',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Setup',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 10,
            'sub_menu' => [
                'organization' => [
                    'icon' => 'tool',
                    'route_name' => 'org.index',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Organization',
                ],
                'application_smtp' => [
                    'icon' => 'wind',
                    'route_name' => 'system.smtp.configure',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Application SMTP',
                ],
                'language' => [
                    'icon' => 'type',
                    'route_name' => 'language.index',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => 'NO',
                    'title' => 'Language',
                ],
                'currency' => [
                    'icon' => 'dollar-sign',
                    'route_name' => 'currencies.index',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Currency',
                ],
                'seo' => [
                    'icon' => 'bar-chart',
                    'route_name' => 'seo.index',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'SEO',
                ],
                'chat_provider' => [
                    'icon' => 'message-square',
                    'route_name' => 'chat.provider',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Chat Provider',
                ],
                'server_status' => [
                    'icon' => 'hard-drive',
                    'route_name' => 'server.status',
                    'active_route_name' => 'setup',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Server Status',
                ],
            ], // setup
        ],
        'themes' => [
            'icon' => 'sun',
            'route_name' => null,
            'active_route_name' => 'themes',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Themes',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 9,
            'sub_menu' => [
                'mailwave' => [
                    'icon' => 'align-left',
                    'route_name' => 'frontend.index',
                    'active_route_name' => 'themes',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'MailWave',
                ],
                'argon' => [
                    'icon' => 'align-left',
                    'route_name' => 'frontend.index',
                    'active_route_name' => 'themes',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Argon',
                ],
                'neon' => [
                    'icon' => 'align-left',
                    'route_name' => 'frontend.setup',
                    'active_route_name' => 'themes',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Neon',
                ],
            ],
        ], // themes
        'email' => [
            'icon' => 'mail',
            'route_name' => null,
            'permission' => 'Admin',
            'active_route_name' => 'email',
            'params' => [],
            'title' => 'Email',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 5,
            'sub_menu' => [
                'servers' => [
                    'icon' => 'server',
                    'route_name' => 'smtp.index',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Servers',
                ], // servers
                'mail_details' => [
                    'icon' => 'mail',
                    'route_name' => 'mail.activity.index',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'YES',
                    'title' => 'Mail Details',
                ], // mail_details
                'check_bounce' => [
                    'icon' => 'mail',
                    'route_name' => 'bounce.check',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Check Bounce',
                ], // check_bounce
                'bounced_emails' => [
                    'icon' => 'mail',
                    'route_name' => 'bounce.emails',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Bounced Emails',
                ], // check_bounce
            ],
        ], // email
        'email_c' => [
            'icon' => 'mail',
            'route_name' => null,
            'permission' => 'Customer',
            'active_route_name' => 'email',
            'params' => [],
            'title' => 'Email',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 5,
            'sub_menu' => [
                'servers' => [
                    'icon' => 'server',
                    'route_name' => 'smtp.index',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Servers',
                ], // servers
                'check_bounce' => [
                    'icon' => 'mail',
                    'route_name' => 'bounce.check',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Check Bounce',
                ], // check_bounce
                'bounced_emails' => [
                    'icon' => 'mail',
                    'route_name' => 'bounce.emails',
                    'active_route_name' => 'email',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Bounced Emails',
                ], // check_bounce
            ],
        ], // email customer
        'sms' => [
            'icon' => 'smartphone',
            'route_name' => null,
            'active_route_name' => 'sms',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'SMS',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 6,
            'sub_menu' => [
                'configure' => [
                    'icon' => 'message-circle',
                    'route_name' => 'sms.index',
                    'active_route_name' => 'sms',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Configure',
                ],
                'sms_logs' => [
                    'icon' => 'message-circle',
                    'route_name' => 'log.sms',
                    'active_route_name' => 'sms',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Logs',
                ],
                'infoBip_log' => [
                    'icon' => 'message-circle',
                    'route_name' => 'log.sms.infobip',
                    'active_route_name' => 'sms',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'YES',
                    'title' => 'InfoBip Log',
                ],
            ],
        ], // sms
        'voice' => [
            'icon' => 'voicemail',
            'route_name' => null,
            'active_route_name' => 'voice',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Voice',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => 'YES',
            'position' => 16,
            'sub_menu' => [
                'voice_configure' => [
                    'icon' => 'server',
                    'route_name' => 'twilio.voice.index',
                    'active_route_name' => 'voice',
                    'params' => [],
                    'title' => 'Configure',
                ],
            ],
        ], // voice
        'builder' => [
            'icon' => 'git-pull-request',
            'route_name' => null,
            'active_route_name' => 'builder',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Builder',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 7,
            'sub_menu' => [
                'create_email' => [
                    'icon' => 'align-left',
                    'route_name' => 'template.builder.originate',
                    'active_route_name' => 'builder',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Create Email Template',
                ],
                'all_email_template' => [
                    'icon' => 'align-left',
                    'route_name' => 'templates.index',
                    'active_route_name' => 'builder',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'All Email Template',
                ],
                'create_sms' => [
                    'icon' => 'align-left',
                    'route_name' => 'builder.sms.create',
                    'active_route_name' => 'builder',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Create SMS Body',
                ],
                'all_sms_template' => [
                    'icon' => 'align-left',
                    'route_name' => 'builder.sms.templates',
                    'active_route_name' => 'builder',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'All SMS Body',
                ],
            ],
        ], // builder
        'contacts' => [
            'icon' => 'hash',
            'route_name' => null,
            'active_route_name' => 'contacts',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Contacts',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 3,
            'sub_menu' => [
                'all_contacts' => [
                    'icon' => 'align-left',
                    'route_name' => 'email.contacts.index',
                    'active_route_name' => 'contacts',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'All Contacts',
                ],
                'email_contacts' => [
                    'icon' => 'align-left',
                    'route_name' => 'email.contacts.list',
                    'active_route_name' => 'contacts',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Emails',
                ],
                'phone_contacts' => [
                    'icon' => 'align-left',
                    'route_name' => 'phone.contacts.list',
                    'active_route_name' => 'contacts',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Phone Numbers',
                ],
                'bulk_import' => [
                    'icon' => 'align-left',
                    'route_name' => 'email.contacts.bulk.csv',
                    'active_route_name' => 'contacts',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Bulk Export Import',
                ],
            ],
        ], // contacts
        'groups' => [
            'icon' => 'users',
            'route_name' => null,
            'active_route_name' => 'groups',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Groups',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 4,
            'sub_menu' => [
                'add_new_group' => [
                    'icon' => 'align-left',
                    'route_name' => 'group.create',
                    'active_route_name' => 'groups',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Add New',
                ],
                'all_groups' => [
                    'icon' => 'align-left',
                    'route_name' => 'group.index',
                    'active_route_name' => 'groups',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'All Groups',
                ],
            ],
        ], // groups
        'campaigns' => [
            'icon' => 'share-2',
            'route_name' => null,
            'active_route_name' => 'campaigns',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Campaigns',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 8,
            'sub_menu' => [
                'create_campaign' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.create',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Add New',
                ],
                'campaign_email' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.type',
                    'active_route_name' => 'campaigns',
                    'params' => ['email'],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Email',
                ],
                'campaign_sms' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.type',
                    'active_route_name' => 'campaigns',
                    'params' => ['sms'],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'SMS',
                ],
                'all_campaigns' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.index',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'All Campaigns',
                ],
                'campaign_schedules' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.schedule.emails',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Schedules',
                ],
                'campaign_schedules__sms' => [
                    'icon' => 'align-left',
                    'route_name' => 'campaign.schedule.sms',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Schedules SMS',
                ],
                'campaign_tracker' => [
                    'icon' => 'align-left',
                    'route_name' => 'tracker.emails.index',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Tracker',
                ],
                'autoresponder' => [
                    'icon' => 'align-left',
                    'route_name' => 'autoresponder.index',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Autoresponder',
                ],
                'campaign_los' => [
                    'icon' => 'align-left',
                    'route_name' => 'logs.campaign.index',
                    'active_route_name' => 'campaigns',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
                    'title' => 'Logs',
                ],
            ],
        ], // campaigns
        'subscription' => [
            'icon' => 'grid',
            'route_name' => null,
            'active_route_name' => 'subscription',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Subscription',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 11,
            'sub_menu' => [
                'all_subscription' => [
                    'icon' => 'align-left',
                    'route_name' => 'subscription.index',
                    'active_route_name' => 'subscription',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Plans',
                ],
            ],
        ], // subscription
        'customer_subscription' => [
            'icon' => 'users',
            'route_name' => 'purchased.plan',
            'active_route_name' => 'customer_subscription',
            'permission' => 'Customer',
            'params' => [],
            'title' => 'Subscription',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 12,
        ], // customer subscription
        'user_management' => [
            'icon' => 'users',
            'route_name' => 'limit.index',
            'active_route_name' => 'user_management',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'User Management',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 13,
        ], // user management
        'blog' => [
            'icon' => 'book',
            'route_name' => 'dashboard.blog.index',
            'active_route_name' => 'blog',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Blogs',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 14,
        ], // user management
        'page' => [
            'icon' => 'book',
            'route_name' => 'page.index',
            'active_route_name' => 'page',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Pages',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 14,
        ], // Page management
        'contact' => [
            'icon' => 'book',
            'route_name' => 'contact.index',
            'active_route_name' => 'contact',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Contact Us',
            'marketplace' => 'YES',
            'counter_badge' => new_contact_count(),
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 14,
        ], // Contact management
        'notes' => [
            'icon' => 'book',
            'route_name' => 'notes.index',
            'active_route_name' => 'notes',
            'permission' => 'AdminCustomer',
            'params' => [],
            'title' => 'Notes',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'NO' : 'NO',
            'position' => 15,
        ], // notes
        'payment setup' => [
            'icon' => 'credit-card',
            'route_name' => null,
            'active_route_name' => 'payment_gateways',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Payment Setup',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 16,
            'sub_menu' => [
                'paypal' => [
                    'icon' => 'align-left',
                    'route_name' => 'payment.setup.paypal',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Paypal',
                ],
                'stripe' => [
                    'icon' => 'align-left',
                    'route_name' => 'payment.setup.stripe',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Stripe',
                ],
                'khalti' => [
                    'icon' => 'align-left',
                    'route_name' => 'payment.setup.khalti',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('khalti') == 1 ? 'NO' : 'YES',
                    'title' => 'Khalti',
                ],
                'flutterwave' => [
                    'icon' => 'align-left',
                    'route_name' => 'dashboard.flutterwave.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('flutterwave') == 1 ? 'NO' : 'YES',
                    'title' => 'Flutterwave',
                ],
                'instamojo' => [
                    'icon' => 'align-left',
                    'route_name' => 'dashboard.instamojo.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('instamojo') == 1 ? 'NO' : 'YES',
                    'title' => 'Instamojo',
                ],
                'paystack' => [
                    'icon' => 'align-left',
                    'route_name' => 'paystack.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('paystack') == 1 ? 'NO' : 'YES',
                    'title' => 'Paystack',
                ],
                // paytm update
                'paytm' => [
                    'icon' => 'align-left',
                    'route_name' => 'paytm.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('paytm') == 1 ? 'NO' : 'YES',
                    'title' => 'Paytm',
                ],
                'razorpay' => [
                    'icon' => 'align-left',
                    'route_name' => 'razorpay.payment.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('razorpay') == 1 ? 'NO' : 'YES',
                    'title' => 'Razorpay',
                ],
                'mollie' => [
                    'icon' => 'align-left',
                    'route_name' => 'mollie.payment.index',
                    'active_route_name' => 'payment_gateways',
                    'params' => [],
                    'disabled' => maildoll_config('mollie') == 1 ? 'NO' : 'YES',
                    'title' => 'Mollie',
                ],
            ],
        ], // payment_gateways
        'third_parties' => [
            'icon' => 'git-commit',
            'route_name' => null,
            'active_route_name' => 'perfex',
            'permission' => 'AdminCustomer',
            'params' => [],
            'title' => 'Third Parties',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 16,
            'sub_menu' => [
                'perfex' => [
                    'icon' => 'align-left',
                    'route_name' => 'perfex.index',
                    'active_route_name' => 'perfex',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Perfex CRM',
                ],
                // 'wordpress' => [
                //     'icon' => 'align-left',
                //     'route_name' => 'wordpress.index',
                //     'active_route_name' => 'wordpress',
                //     'params' => [],
                //     'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                //     'title' => 'Wordpress',
                // ],
                'activecms' => [
                    'icon' => 'align-left',
                    'route_name' => 'activecms.index',
                    'active_route_name' => 'activecms',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Active CMS',
                ],
            ],
        ], // third_parties
        'chatgpt' => [
            'icon' => 'git-commit',
            'route_name' => null,
            'active_route_name' => 'perfex',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Chat GPT',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' || org('disable_chatgpt') == 1 ? 'YES' : 'NO',
            'position' => 16,
            'sub_menu' => [
                'perfex' => [
                    'icon' => 'align-left',
                    'route_name' => 'chat.gpt.setup',
                    'active_route_name' => 'chatgpt',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Setup',
                ],
                'chatgpt' => [
                    'icon' => 'align-left',
                    'route_name' => 'chat.gpt.index',
                    'active_route_name' => 'chatgpt',
                    'params' => [],
                    'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
                    'title' => 'Chat GPT',
                ],
            ],
        ], // chat gpt
        'support' => [
            'icon' => 'at-sign',
            'route_name' => 'support.ticket.new',
            'active_route_name' => 'support',
            'permission' => 'Everyone',
            'params' => [],
            'title' => 'Support Tickets',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 17,
        ], // support
        'upgrade' => [
            'icon' => 'git-pull-request',
            'route_name' => 'auto.update.index',
            'active_route_name' => 'upgrade',
            'permission' => 'Admin',
            'params' => [],
            'title' => 'Upgrade',
            'marketplace' => 'YES',
            'counter_badge' => null,
            'disabled' => env('SAAS_ACTIVE') == 'YES' ? 'YES' : 'NO',
            'position' => 18,
        ], // upgrade

        //ENDS
    ];

    return collect($menu)->sortBy('position')->toArray();
}

/**
 * Get current route sub_menu active_route_name
 */
function active_menu_name()
{
    return [
        'dashboard' => 'dashboard',
        'profile.index' => 'dashboard',
        'app.api.index' => 'dashboard',
        'help' => 'dashboard',
        'notifications.index' => 'dashboard',
        'profile.change.password' => 'dashboard',

        'marketplace.index' => 'marketplace',
        'marketplace.buyers' => 'marketplace',

        'org.index' => 'setup',
        'system.smtp.configure' => 'setup',

        'language.index' => 'setup',
        'language.translate' => 'setup',

        'currencies.index' => 'setup',
        'currencies.edit' => 'setup',

        'chat.provider' => 'setup',
        'chat.edit' => 'setup',

        'seo.index' => 'setup',
        'server.status' => 'setup',

        'smtp.index' => 'email',
        'smtp.configure' => 'email',
        'mailgun.domain.index' => 'email',
        'mailgun.domain.show' => 'email',
        'mailgun.domain.verify' => 'email',
        'mailgun.domain.delete' => 'email',
        'mailgun.domain.create' => 'email',
        'sms.admin.configure.edit' => 'sms',

        'mail.activity.index' => 'email',
        'bounce.check' => 'email',
        'bounce.emails' => 'email',

        'sms.index' => 'sms',
        'log.sms' => 'sms',
        'log.sms.infobip' => 'sms',
        'sms.admin.configure' => 'sms',
        'sms.configure' => 'sms',

        'twilio.voice.index' => 'voice',

        'template.builder.originate' => 'builder',
        'template.builder.edit.thumbnail' => 'builder',
        'pro.template.builder.edit' => 'builder',
        'template.import' => 'builder',
        'template.builder.edit' => 'builder',
        'templates.index' => 'builder',
        'template.builder.create' => 'builder',
        'builder.sms.create' => 'builder',
        'builder.sms.templates' => 'builder',
        'builder.sms.template.edit' => 'builder',
        'builder.sms.template.show' => 'builder',

        'email.contacts.index' => 'contacts',
        'email.contacts.list' => 'contacts',
        'phone.contacts.list' => 'contacts',
        'email.contacts.bulk.csv' => 'contacts',
        'email.contact.show' => 'contacts',

        'group.create' => 'groups',
        'group.index' => 'groups',
        'group.create.type' => 'groups',
        'group.store.step1' => 'groups',
        'group.edit' => 'groups',
        'group.show' => 'groups',

        'campaign.create' => 'campaigns',
        'campaign.type' => 'campaigns',
        'campaign.type' => 'campaigns',
        'campaign.index' => 'campaigns',
        'campaign.emails.edit' => 'campaigns',
        'campaign.schedule.emails' => 'campaigns',
        'campaign.schedule.email' => 'campaigns',
        'campaign.schedule.sms' => 'campaigns',
        'campaign.schedule.sms.create' => 'campaigns',
        'tracker.emails.index' => 'campaigns',
        'autoresponder.index' => 'campaigns',
        'autoresponder.create_step1' => 'campaigns',
        'autoresponder.builder' => 'campaigns',
        'logs.campaign.index' => 'campaigns',
        'campaign.create.type' => 'campaigns',
        'campaign.store.step1' => 'campaigns',
        'campaign.store.store2' => 'campaigns',
        'campaign.schedule.email.edit' => 'campaigns',
        'tracker.campaign' => 'campaigns',
        'autoresponder.edit_step1' => 'campaigns',
        'autoresponder.builder.edit' => 'campaigns',

        'subscription.index' => 'subscription',
        'subscription.edit' => 'subscription',
        'limit.manage' => 'subscription',

        'purchased.plan' => 'customer_subscription',

        'limit.index' => 'user_management',
        'limit.restore' => 'user_management',
        'login.as.customer' => 'user_management',

        'notes.index' => 'notes',
        'notes.create' => 'notes',
        'notes.edit' => 'notes',
        'notes.show' => 'notes',

        'payment.setup.paypal' => 'payment_gateways',
        'payment.setup.stripe' => 'payment_gateways',
        'payment.setup.khalti' => 'payment_gateways',
        'dashboard.flutterwave.index' => 'payment_gateways',
        'rave.pay' => 'payment_gateways',
        'rave.callback' => 'payment_gateways',
        'instamojo.pay' => 'payment_gateways',
        'instamojo.success' => 'payment_gateways',
        'dashboard.instamojo.index' => 'payment_gateways',
        'payment.setup.instamojo.store' => 'payment_gateways',
        'paystack.index' => 'payment_gateways',
        'paystack.store' => 'payment_gateways',
        'razorpay.payment.index' => 'payment_gateways',
        'razorpay.payment.store' => 'payment_gateways',
        'razorpay.payment.setup' => 'payment_gateways',
        'mollie.payment.index' => 'payment_gateways',
        'mollie.payment.setup' => 'payment_gateways',

        'paytm.index' => 'payment_gateways',
        'paytm.store' => 'payment_gateways',
        'paytm.callback' => 'payment_gateways',

        'auto.update.index' => 'updgrade',

        'support.ticket.new' => 'support',
        'submit.request' => 'support',
        'submit.request.success' => 'support',
        'ticket.reply' => 'support',
        'support.ticket.unread' => 'support',
        'support.ticket.search' => 'support',
        'support.ticket.sent.starred' => 'support',
        'support.ticket.sent.reply' => 'support',
        'ticket.solved' => 'support',

        'dashboard.blog.index' => 'blog',
        'dashboard.blog.create' => 'blog',
        'dashboard.blog.final_create' => 'blog',
        'dashboard.blog.store' => 'blog',
        'dashboard.blog.show' => 'blog',
        'dashboard.blog.update' => 'blog',
        'dashboard.blog.destroy' => 'blog',

        'page.index' => 'page',
        'page.create' => 'page',
        'page.edit' => 'page',
        'page.store' => 'page',
        'page.show' => 'page',
        'page.update' => 'page',
        'page.destroy' => 'page',

        'contact.index' => 'contact',
        'contact.create' => 'contact',
        'contact.store' => 'contact',
        'contact.show' => 'contact',
        'contact.destroy' => 'contact',
        'contact.replay' => 'contact',

        'coupon.index' => 'coupon',
        'coupon.create' => 'coupon',
        'coupon.store' => 'coupon',
        'coupon.destroy' => 'coupon',
        'coupon.edit' => 'coupon',
        'coupon.update' => 'coupon',
        'coupon.published' => 'coupon',

        'agents.index' => 'agents',

        'perfex.index' => 'perfex',
        'perfex.token' => 'perfex',
        'perfex.fetch.data' => 'perfex',

        'wordpress.index' => 'wordpress',
        'activecms.index' => 'activecms',
        'chat.gpt.index' => 'chatgpt',
        'chat.gpt.chat' => 'chatgpt',
        'chat.gpt.single' => 'chatgpt',
        'chat.gpt.setup' => 'chatgpt',
    ];
}

// formatCode
function formatCode($code)
{
    return str_replace('>', 'HTMLCloseTag', str_replace('<', 'HTMLOpenTag', $code));
}

/** User Type */
function admin()
{
    if (Auth::user()->user_type == 'Admin') {
        return true;
    } else {
        return false;
    }
}

function customer()
{
    if (Auth::user()->user_type == 'Customer') {
        return true;
    } else {
        return false;
    }
}

// layout
function layout()
{
    return 'side-menu';
}

// layout
function defaultMail($mail)
{
    if (env('DEFAULT_MAIL') == $mail) {
        return 'border border-theme-1';
    } else {
        return null;
    }
}

// layout
function defaultSMS($sms)
{
    if (env('DEFAULT_SMS') == $sms) {
        return 'border border-theme-1';
    } else {
        return null;
    }
}

/**
 * SmtpServer
 */
function SmtpServer()
{
    return SmtpServer::count();
}

// username
function username()
{
    return Auth::user()->name;
}

// userId
function userId()
{
    return Auth::user()->id;
}

// userInfo
function userInfo()
{
    return User::where('id', Auth::user()->id)->with('personal')->first();
}

// avatar
function avatar()
{
    if (Auth::user()->photo != null) {
        return filePath(Auth::user()->photo);
    } else {
        return Avatar::create(Str::upper(username()));
    }
}

// avatar
function emailAvatar($email)
{
    return Avatar::create(Str::upper($email));
}

// avatar
function namevatar($name)
{
    return Avatar::create(Str::upper($name));
}

// commonAvatar
function commonAvatar($name)
{
    return Avatar::create(Str::upper($name));
}

// emailCount
function emailCount()
{
    return EmailContact::Active()->HasAgent()->whereNotNull('email')->count();
}

// emailCount
function phoneCount()
{
    return EmailContact::Active()->HasAgent()->whereNotNull('phone')->count();
}

// favCount
function favCount()
{
    return EmailContact::Favourite()->count();
}

// trashedCount
function trashedCount()
{
    return EmailContact::TrashedBin()->count();
}

// blockedCount
function blockedCount()
{
    return EmailContact::Blocked()->count();
}

// campaignCount
function campaignCount()
{
    return Campaign::Active()->HasAgent()->where('type', 'email')->count();
}

// SMScampaignCount
function SMScampaignCount()
{
    return Campaign::Active()->HasAgent()->where('type', 'sms')->count();
}

// emailGroupCount
function emailGroupCount()
{
    return EmailGroup::Active()->HasAgent()->where('type', 'email')->count();
}

// SMSGroupCount
function SMSGroupCount()
{
    return EmailGroup::Active()->HasAgent()->where('type', 'sms')->count();
}

// emailGroupCount
function templateCount()
{
    return TemplateBuilder::HasAgent()->count();
}

// emailGroupCount
function smsTemplateCount()
{
    return SmsBuilder::where('user_id', Auth::user()->id)->count();
}

// totalSentMail
function totalSentMail()
{
    return UserSentRecord::User()->count();
}

// totalSentMail
function totalSMSSent()
{
    return SmsLog::where('user_id', Auth::user()->id)->count();
}

// emailGroupCount
function queueCount()
{
    return Job::count();
}

// mailReach
function mailReach()
{
    return MailLog::where('opens', 1)->count();
}

// mailReach
function mailNoReach()
{
    return MailLog::where('opens', 0)->count();
}

// SmsLog
function smsLog($campaign_id, $number, $message, $gateway)
{
    $smsLog = new SmsLog();
    $smsLog->user_id = Auth::user()->id;
    $smsLog->campaign_id = $campaign_id;
    $smsLog->number = $number;
    $smsLog->message_id = Str::random(20);
    $smsLog->message = $message;
    $smsLog->gateway = $gateway;
    $smsLog->save();

    return $smsLog;
}

// QueueMonitor

function QueueMonitor($name)
{
    return QueueMonitor::where($name, 1)->count();
}

// failedJobs

function failedJobs()
{
    return DB::table('failed_jobs')->count();
}

// mailBounced

function mailBounced()
{
    return BouncedEmail::where('bounce', 0)->HasAgent()->count();
}

// totalTasks

function totalTasks()
{
    return 1;
}

// logo
function logo()
{
    $logo = OrganizationSetup::where('name', 'logo')->first();
    $company_name = OrganizationSetup::where('name', 'company_name')->first();

    if ($logo->value != null) {
        return filePath($logo->value);
    } else {
        return Avatar::create(Str::upper($company_name->value));
    }
}

// favIcon
function favIcon()
{
    $favIcon = OrganizationSetup::where('name', 'favIcon')->first();
    $company_name = OrganizationSetup::where('name', 'company_name')->first();

    if ($favIcon->value != null) {
        return filePath($favIcon->value);
    } else {
        return Avatar::create(Str::upper($company_name->value));
    }
}

// footerLogo
function footerLogo()
{
    $footerLogo = OrganizationSetup::where('name', 'footer_logo')->first();
    $company_name = OrganizationSetup::where('name', 'company_name')->first();

    if ($footerLogo->value != null) {
        return filePath($footerLogo->value);
    } else {
        return Avatar::create(Str::upper($company_name->value));
    }
}

function maildoll()
{
    return asset('maildoll.png');
}

function maildollLogo()
{
    return asset('icon.png');
}

/**
 * DB connection check
 */
function checkDBConnection()
{
    if (DB::connection()->getDatabaseName()) {
        return true;
    } else {
        return false;
    }
}

// mailLogo
function mailLogo($name)
{
    return filePath('mail/' . $name . '.png');
}

// mailLogo
function smsLogo($name)
{
    return filePath('sms/' . $name . '.png');
}

function checkColor()
{
    return OrganizationSetup::where('name', 'color')->first();
}

// mailLogo
function color()
{
    $org = OrganizationSetup::where('name', 'color')->first();

    if ($org->value != null) {
        return $org->value;
    } else {
        return null;
    }
}

//org
function org($name)
{
    $org = OrganizationSetup::where('name', $name)->first();

    return $org ? $org->value : null;
}

//org
function active_lang($code)
{
    $org = OrganizationSetup::where('name', $code)->first();

    return $org->value;
}

/**
 * DEVELOPER MODE
 */
function devtool()
{
    $dev = OrganizationSetup::where('name', 'dev_mode')->first();

    if ($dev->value == 1) {
        return true;
    } else {
        return false;
    }
}

//org
function seo($name)
{
    $seo = Seo::where('name', $name)->first();

    return $seo->value ?? null;
}

//orgName
function orgName()
{
    $orgName = OrganizationSetup::where('name', 'company_name')->first();

    return $orgName->value ?? 'Maildoll';
}

//orgEmail
function orgEmail()
{
    $orgName = OrganizationSetup::where('name', 'company_email')->first();

    return $orgName->value;
}

//orgPhone
function orgPhone()
{
    $orgName = OrganizationSetup::where('name', 'company_phone_number')->first();

    return $orgName->value;
}

//orgTel
function orgTel()
{
    $orgName = OrganizationSetup::where('name', 'company_tel_number')->first();

    return $orgName->value;
}

//orgName
function orgAddress()
{
    $orgName = OrganizationSetup::where('name', 'company_address')->first();

    return $orgName->value;
}

// flag
function flag($flag)
{
    return asset('assets/lang/' . $flag);
}

// country flag
function countryFlag()
{
    $flag = OrganizationSetup::where('name', 'default_language')->first()->value;

    return Language::where('code', $flag)->first()->image;
}

//this function for open Json file to read language json file
function openJSONFile($code)
{
    $jsonString = [];
    if (File::exists(base_path('resources/lang/' . $code . '.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/' . $code . '.json'));
        $jsonString = json_decode($jsonString, true);
    }

    return $jsonString;
}

//save the new language json file
function saveJSONFile($code, $data)
{
    ksort($data);
    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    file_put_contents(base_path('resources/lang/' . $code . '.json'), stripslashes($jsonData));
}

//translate the key with json
function translate($key)
{
    $key = ucfirst(str_replace('_', ' ', $key));
    if (File::exists(base_path('resources/lang/en.json'))) {
        $jsonString = file_get_contents(base_path('resources/lang/en.json'));
        $jsonString = json_decode($jsonString, true);
        if (!isset($jsonString[$key])) {
            $jsonString[$key] = $key;
            saveJSONFile('en', $jsonString);
        }
    }

    return __($key);
}

//scan directory for load flag
function readFlag()
{
    $dir = base_path('public/assets/lang');
    $file = scandir($dir);

    return $file;
}

//auto Rename Flag
function flagRenameAuto($name)
{
    $nameSubStr = substr($name, 8);
    $nameReplace = ucfirst(str_replace('_', ' ', $nameSubStr));
    $nameReplace2 = ucfirst(str_replace('.png', '', $nameReplace));

    return $nameReplace2;
}

function defaultCurrency()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int) getSystemSetting('default_currencies')->value;
    }

    $currency = Currency::find($id);

    return $currency->code;
}

//format the Price
function convertPrice($price)
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int) getSystemSetting('default_currencies')->value;
    }

    $currency = Currency::find($id);
    $p = $price * $currency->rate;

    return $p;
}
//format the Price
function formatPrice($price)
{
    try {
        $sc = session('currency');
        if ($sc != null) {
            $id = $sc;
        } else {
            $id = (int) getSystemSetting('default_currencies')->value;
        }

        $currency = Currency::find($id);
        if ($currency) {
            $p = $price * $currency->rate;
            return $currency->align == 0 ? number_format($p, 2) . $currency->symbol : $currency->symbol . number_format($p, 2);
        }
    } catch (\Throwable $th) {
        // Fallback if db setting not found
    }

    return '$' . number_format((float)$price, 2);
}

//format the Price
function noFormatPrice($huh)
{
    try {
        $x = session('currency');
        if ($x != null) {
            $ids = $x;
        } else {
            $defaultSetting = getSystemSetting('default_currencies');
            $ids = $defaultSetting ? (int) $defaultSetting->value : null;
        }

        if ($ids != null) {
            $currency = Currency::find($ids);
            if ($currency) {
                return $huh * $currency->rate;
            }
        }
    } catch (\Throwable $th) {
        // Fallback
    }

    return (float) $huh;
}

//format the Price Code
function formatPriceCode()
{
    try {
        $priceCode = session('currency');
        if ($priceCode == null) {
            $defaultSetting = getSystemSetting('default_currencies');
            $priceCode = $defaultSetting ? (int) $defaultSetting->value : null;
        }

        if ($priceCode != null) {
            $currency = Currency::find($priceCode);
            if ($currency && !empty($currency->code)) {
                return $currency->code;
            }
        }

        $firstCurrency = Currency::first();
        if ($firstCurrency && !empty($firstCurrency->code)) {
            return $firstCurrency->code;
        }
    } catch (\Throwable $th) {
        // Fallback
    }

    return 'USD';
}

function getPriceRate($code)
{
    $getPriceCode = Currency::where('code', $code)->first();

    return $getPriceCode->rate;
}

/*only price for payment*/
function onlyPrice($price)
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int) getSystemSetting('default_currencies')->value;
    }

    $currency = Currency::find($id);
    $p = $price * $currency->rate;

    return $p;
}

function PaytmPrice($price)
{
    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) * getPriceRate('INR');
            break;

        case 'BDT':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'INR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'LKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'PKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'NPR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'ZAR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'JPY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'KRW':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'IDR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'AED':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'TRY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        default:
            // code...
            break;
    }
}

function RazorPayPrice($price)
{
    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) * getPriceRate('INR');
            break;

        case 'BDT':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'INR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'LKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'PKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'NPR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'ZAR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'JPY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'KRW':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'IDR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'AED':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;
        case 'TRY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('INR');
            break;

        default:
            // code...
            break;
    }
}

function StripePrice($stripePrice)
{
    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($stripePrice);
            break;
        case 'BDT':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'INR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'LKR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'PKR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'NPR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'ZAR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'JPY':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'KRW':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'IDR':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'AED':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;
        case 'TRY':
            return noFormatPrice($stripePrice) / getPriceRate(activeCurrency());
            break;

        default:
            // code...
            break;
    }
}

function NPRPrice($price)
{
    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($price) * getPriceRate('NPR');
            break;

        case 'BDT':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'INR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'LKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'PKR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'NPR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'ZAR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'JPY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'KRW':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'IDR':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'AED':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;
        case 'TRY':
            return noFormatPrice($price) / getPriceRate(activeCurrency()) * getPriceRate('NPR');
            break;

        default:
            // code...
            break;
    }
}

function PaypalPrice($PaypalPrice)
{
    switch (activeCurrency()) {
        case 'USD':
            return noFormatPrice($PaypalPrice);
            break;
        case 'BDT':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'INR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'LKR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'PKR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'NPR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'ZAR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'JPY':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'KRW':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'AED':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'IDR':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;
        case 'TRY':
            return noFormatPrice($PaypalPrice) / getPriceRate(activeCurrency());
            break;

        default:
            // code...
            break;
    }
}

/*Active Currency*/
function activeCurrency()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int) getSystemSetting('default_currencies')->value;
    }

    $currency = Currency::find($id);

    return $currency->code;
}

/*Active Currency*/
function activeCurrencySymbol()
{
    $sc = session('currency');
    if ($sc != null) {
        $id = $sc;
    } else {
        $id = (int) getSystemSetting('default_currencies')->value;
    }

    $currency = Currency::find($id);

    return $currency->symbol;
}

//override or add env file or key
function overWriteEnvFile($type, $val)
{
    $path = base_path('.env');
    if (file_exists($path)) {
        $val = '"' . trim($val) . '"';
        if (is_numeric(strpos(file_get_contents($path), $type)) && strpos(file_get_contents($path), $type) >= 0) {
            file_put_contents($path, str_replace($type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)));
        } else {
            file_put_contents($path, file_get_contents($path) . "\r\n" . $type . '=' . $val);
        }
    }
}

//get system setting data
function getSystemSetting($key)
{
    return OrganizationSetup::where('name', $key)->first();
}

/**
 * Uses the static files if not exists in the storage
 *
 * @param  string  $file
 * @return string
 */
function filePath($file)
{
    $exists = File::exists(storage_path('app/public/' . $file));
    $path = $exists ? asset('storage/' . $file) : asset($file);

    return $path;
}

/**
 * Deletes a file from a given relative storage path or public path
 *
 * @param  string  $file
 * @return bool
 */
function fileDelete($file)
{
    if ($file != null) {
        $exists = File::exists(storage_path('app/public/' . $file));
        if ($exists) {
            return File::delete(storage_path('app/public/' . $file));
        }
        if (file_exists(public_path($file))) {
            return unlink(public_path($file));
        }
    }

    return false;
}

/**
 * Uploads a file with a random name to the given folder in the storage
 *
 * @return string
 */
function fileUpload(UploadedFile $file, string $folder)
{
    return $file->storeAs("uploads/$folder", Str::random(32) . '.' . $file->extension(), 'public');
}

// cookie time day
function cookiesLimit()
{
    $days = (int) getSystemSetting('cookies_limit')->value;
    /*return day*/

    return $days * 1440;
}

/*default template*/
function emailTemplates()
{
    return TemplateBuilder::HasAgent()->get();
}

/*default template*/
function smsTemplates()
{
    return SmsBuilder::where('user_id', Auth::user()->id)->get();
}

/*emailGroups*/
function emailGroups($type)
{
    return EmailGroup::HasAgent()->where('type', $type)->get();
}

/*emailGroupEmails*/
function emailGroupEmails($group_id)
{
    return EmailListGroup::where('email_group_id', $group_id)->get();
}

// EMAILS REPORT :: START ------------------------------------------------------------------------------------------------------------------

/**
 * Get Month Wise Current Year Data
 */
function sentMailMonthWiseCurrentYearData()
{
    return UserSentRecord::HasAgent()->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->groupBy('monthname')
        ->get();
}

/**
 * Get Current Month Data
 */
function sentMailCurrentMonthData()
{
    return UserSentRecord::HasAgent()->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->count();
}

/**
 * Get Last Month records
 */
function sentMailLastMonthData()
{
    return UserSentRecord::HasAgent()->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
}

// EMAILS REPORT :: END -------------------------------------------------------------------------------------------------------------------

// SMS REPORT :: START --------------------------------------------------------------------------------------------------------------------

/**
 * Twilio
 */
function sentSMSMonthWiseCurrentYearDataTwilio()
{
    return SmsLog::where('user_id', Auth::user()->id)->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->where('gateway', 'twilio')
        ->groupBy('monthname')
        ->get();
}

function sentSMSMonthWiseCurrentYearData()
{
    return SmsLog::where('user_id', Auth::user()->id)->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->groupBy('monthname')
        ->get();
}

/**
 * Nexmo
 */
function sentSMSMonthWiseCurrentYearDataNexmo()
{
    return SmsLog::where('user_id', Auth::user()->id)
        ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->where('gateway', 'nexmo')
        ->groupBy('monthname')
        ->get();
}

/**
 * Plivo
 */
function sentSMSMonthWiseCurrentYearDataPlivo()
{
    return SmsLog::where('user_id', Auth::user()->id)
        ->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->where('gateway', 'plivo')
        ->groupBy('monthname')
        ->get();
}

/**
 * Get Current Month Data
 */
function sentSMSCurrentMonthData()
{
    return SmsLog::where('user_id', Auth::user()->id)
        ->whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->count();
}

/**
 * Get Last Month records
 */
function sentSMSLastMonthData()
{
    return SmsLog::where('user_id', Auth::user()->id)->whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
}

// SMS REPORT :: END ----------------------------------------------------------------------------------------------------------------------

/**
 * TOTAL EMAILS
 */

/**
 * Get Current Month Data
 */
function totalMailCurrentMonthData()
{
    return EmailContact::whereMonth('created_at', date('m'))
        ->whereYear('created_at', date('Y'))
        ->count();
}

/**
 * Get Last Month records
 */
function totalMailLastMonthData()
{
    return EmailContact::whereMonth('created_at', '=', Carbon::now()->subMonth()->month)->count();
}

/**
 * LAST SENT EMAIL
 */
function lastSentMails($paginate)
{
    return bouncedEmail::HasAgent()->latest()->paginate($paginate);
}

/**
 * LAST SENT SMS
 */
function lastSentSMS($paginate)
{
    return SmsLog::where('user_id', Auth::user()->id)->latest()->paginate($paginate);
}

/**
 * PERCENTAGE MATH ------------------------------------------------------------------------------------------------------------------------
 */
function getPercentageChange($newNumber, $oldNumber)
{
    if ($oldNumber != 0) {
        $decreaseValue = $oldNumber - $newNumber;

        return ($decreaseValue / $oldNumber) * 100;
    } else {
        return $oldNumber = 0;
    }
}

/**
 * CHECK HIGHER LOWER THAN
 */
function checkHigherLowerThan($newNumber, $oldNumber)
{
    if ($oldNumber < $newNumber) {
        return translate('Higher than last month');
    } else {
        return translate('Lower than last month');
    }
}

/**
 * CHECK HIGHER LOWER THAN ADD CLASS
 */
function checkHigherLowerThanAddClass($newNumber, $oldNumber)
{
    if ($oldNumber < $newNumber) {
        return 'bg-theme-6';
    } else {
        return 'bg-theme-9';
    }
}

function checkHigherLowerThanAddIcon($newNumber, $oldNumber)
{
    if ($oldNumber < $newNumber) {
        return 'chevron-up';
    } else {
        return 'chevron-down';
    }
}

/**
 * countSubscriptionPlan
 */
function countSubscriptionPlan()
{
    return SubscriptionPlan::where('status', 1)->count();
}

function displaySubscriptionPlan()
{
    return SubscriptionPlan::where('status', 1)->where('display', 1)->count();
}

/**
 * PERCENTAGE MATH:END --------------------------------------------------------------------------------------------------------------------
 */

/**
 * userSubscriptionPlan
 */
function userSubscriptionPlan()
{
    UserSentLimitPlan::User()->Active()->first();
}

/**
 * EMAIL LIMIT
 */
function userSubscriptionLimit($user)
{
    return EmailSMSLimitRate::where('status', 1)->where('owner_id', $user)->first();
}

/**
 * EMAIL LIMIT EXPIRED
 */
function expiredCheck()
{
    return EmailSMSLimitRate::ExpiredCheck()->first();
}

/**
 * Check Status
 */
function LimitStatus()
{
    $statusFalse = EmailSMSLimitRate::UserCheck()->first()->status;

    if ($statusFalse == 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * DATE LIMIT
 */
function dateLimitCheck()
{
    $dateCheck = EmailSMSLimitRate::Active()->whereDate('to', '>', Carbon::now())->first();
    if ($dateCheck) {
        return true;
    } else {
        return false;
    }
}

/**
 * LIMIT CHECK
 */
function emailLimitCheck($user)
{
    try {
        if (Auth::user()->user_type == 'Admin') {
            return true;
        } else {
            if (userSubscriptionLimit($user)->email > 0 && dateLimitCheck() && LimitStatus()) {
                return true;
            } else {
                return false;
            }
        }
    } catch (\Throwable $th) {
        return redirect()->route('dashboard')->withErrors($th->getMessage());
    }
}

function SMSLimitCheck($user)
{
    try {
        if (userSubscriptionLimit($user)->sms > 0 && dateLimitCheck()) {
            return true;
        } else {
            return false;
        }
    } catch (\Throwable $th) {
        return redirect()->route('dashboard')->withErrors($th->getMessage());
    }
}

/**
 * FREE DATE LIMIT
 */
function freeDateLimitCheck($plan_name)
{
    $freeDateLimitCheck = UserSentLimitPlan::HasAgent()
        ->where('plan_name', $plan_name)
        ->where('status', 1)
        ->first();

    if ($freeDateLimitCheck != null) {
        return true;
    } else {
        return false;
    }
}

function availableEmailPerUser($user_id)
{
    return EmailSMSLimitRate::where('status', 1)->where('owner_id', $user_id)->first()->email;
}

function usedEmailPerUser($user_id)
{
    $plan_from = EmailSMSLimitRate::where('status', 1)->where('owner_id', $user_id)->first()->from;
    $cost_email = UserSentRecord::where('owner_id', $user_id)
        ->where('created_at', '>=', $plan_from)->count();

    return $cost_email;
}

/**
 * EMAAIL LIMIT PERCENTAGE
 */
function emailLimitCheckPercentage($user)
{
    $limit = userSubscriptionLimit($user) ?? null;

    if ($limit != null) {
        if (userSubscriptionLimit($user)->email <= 0) {
            return 0;
        } else {
            $substract = availableEmailPerUser($user) - usedEmailPerUser($user);
            $divide = $substract / availableEmailPerUser($user);
            $emailLeft = $divide * 100;

            return $emailLeft;
        }
    } else {
        return 1;
    }
}

/**
 * SMS LIMIT PERCENTAGE
 */
function availableSMSPerUser($user_id)
{
    return EmailSMSLimitRate::where('status', 1)->where('owner_id', $user_id)->first()->sms;
}

function usedSMSPerUser($user_id)
{
    $plan_from = EmailSMSLimitRate::where('status', 1)->where('owner_id', $user_id)->first()->from;
    $cost_email = UserSentRecord::where('owner_id', $user_id)
        ->where('created_at', '>=', $plan_from)->count();

    return $cost_email;
}

function smsLimitCheckPercentage($user)
{
    $limit = userSubscriptionLimit($user) ?? null;

    if ($limit != null) {
        if (userSubscriptionLimit($user)->sms <= 0) {
            return 0;
        } else {
            $substract = availableSMSPerUser($user) - usedSMSPerUser($user);
            $divide = $substract / availableSMSPerUser($user);
            $smsLeft = $divide * 100;

            return $smsLeft;
        }
    } else {
        return 1;
    }
}

/**
 * SMS LIMIT PROGRESSBAR
 */
function smsLimitProgressBar($user)
{
    $eLimit = smsLimitCheckPercentage($user);

    switch ($eLimit) {
        case $eLimit >= 100:
            return 'w-full bg-theme-9';
            break;
        case $eLimit >= 90:
            return 'w-11/12 bg-theme-9';
            break;
        case $eLimit >= 80:
            return 'w-4/5 bg-theme-9';
            break;
        case $eLimit >= 70:
            return 'w-3/4 bg-theme-9';
            break;
        case $eLimit >= 60:
            return 'w-2/3 bg-theme-1';
            break;
        case $eLimit >= 50:
            return 'w-1/2 bg-theme-1';
            break;
        case $eLimit >= 30:
            return 'w-1/3 bg-theme-12';
            break;
        case $eLimit >= 25:
            return 'w-1/4 bg-theme-12';
            break;
        case $eLimit >= 10:
            return 'w-1/12 bg-theme-6';
            break;
        case $eLimit <= 10:
            return 'w-0 bg-theme-6';
            break;

        default:
            // code..
            break;
    }
}

/**
 * EMAIL LEFT
 */
function availableEmail()
{
    $availableEmail = EmailSMSLimitRate::Active()->first();

    if ($availableEmail != null) {
        return $availableEmail->email;
    } else {
        return 0;
    }
}

function usedEmail()
{
    $plan_from = EmailSMSLimitRate::Active()->first();

    if ($plan_from != null) {
        $cast_email = UserSentRecord::HasAgent()
            ->where('created_at', '>=', $plan_from->from)->count();

        return $cast_email;
    } else {
        return 0;
    }
}

function emailLeftCount()
{
    return $emailLeft = availableEmail();
}

function emailLeft()
{
    if (totalSentMail() <= 0) {
        return availableEmail();
    } else {
        return $emailsLeft = totalSentMail() / totalSentMail();
    }
}

/**
 * SMS LEFT
 */
function availableSMS()
{
    $availableSMS = EmailSMSLimitRate::Active()->first();

    if ($availableSMS != null) {
        return $availableSMS->sms;
    } else {
        return 0;
    }
}

function usedSMS()
{
    $plan_from_sms = EmailSMSLimitRate::Active()->first();

    if ($plan_from_sms != null) {
        $cast_sms = UserSentRecord::HasAgent()
            ->where('type', 'sms')
            ->where('created_at', '>=', $plan_from_sms->from)->count();

        return $cast_sms;
    } else {
        return 0;
    }
}

function smsLeftCount()
{
    return $smsLeft = availableSMS();
}

function smslLeft()
{
    if (totalSMSSent() <= 0) {
        return availableSMS();
    } else {
        $left = EmailSMSLimitRate::Active()->first()->sms;

        return $emaislLeft = availableSMS() / totalSMSSent();
    }
}

/**
 * SUBSCRIPTION
 */
function displaySubscriptions()
{
    try {
        return SubscriptionPlan::where('status', 1)
            ->where('display', 1)
            ->get();
    } catch (\Throwable $th) {
        return collect([]);
    }
}

function subscriptions($name)
{
    if (Auth::user()->user_type == 'Admin') {
        return SubscriptionPlan::where('name', $name)->get();
    } else {
        return SubscriptionPlan::where('name', $name)->Active()->get();
    }
}

/**
 * SUBSCRIPTION NAME
 */
function subscriptionName($id)
{
    return SubscriptionPlan::where('id', $id)->first()->name;
}

/**
 * CHECK SUBSCRIPTION
 */
function checkSubscription($plan_id)
{
    $checkSubscription = PlanPurchased::where('user_id', Auth::user()->id)->where('plan_id', $plan_id)->first();

    if ($checkSubscription != null) {
        return translate('Renew This Plan With PayPal');
    } else {
        return translate('Purchase With PayPal');
    }
}

/**
 * CheckPlanExist
 */
function CheckPlanExist()
{
    return UserSentLimitPlan::Active()->whereDate('to', '>', Carbon::now())->first();
}

/**
 * DEFAULT TEMPLATE
 */
function defaultTemplate()
{
    return TemplateBuilder::where('id', 1)->first();
}

/**
 * PROGRESS BAR
 */
function emailLimitProgressBar($user)
{
    $eLimit = emailLimitCheckPercentage($user);

    switch ($eLimit) {
        case $eLimit >= 100:
            return 'w-full bg-theme-9';
            break;
        case $eLimit >= 90:
            return 'w-11/12 bg-theme-9';
            break;
        case $eLimit >= 80:
            return 'w-4/5 bg-theme-9';
            break;
        case $eLimit >= 70:
            return 'w-3/4 bg-theme-9';
            break;
        case $eLimit >= 60:
            return 'w-2/3 bg-theme-1';
            break;
        case $eLimit >= 50:
            return 'w-1/2 bg-theme-1';
            break;
        case $eLimit >= 30:
            return 'w-1/3 bg-theme-12';
            break;
        case $eLimit >= 25:
            return 'w-1/4 bg-theme-12';
            break;
        case $eLimit >= 10:
            return 'w-1/12 bg-theme-6';
            break;
        case $eLimit <= 10:
            return 'w-0 bg-theme-6';
            break;

        default:
            // code...
            break;
    }
}

/**
 * NOTES
 */
function notes()
{
    $notes = ImportantNotice::Active()->latest()->get();

    return $notes;
}

/**
 * CAMPAINGS
 */
function campaingEmails()
{
    return EmailContact::Active()->HasAgent()->whereNotNull('email')->latest()->paginate(20);
}

function campaingSMS()
{
    return EmailContact::Active()->HasAgent()
        ->whereNotNull('country_code')
        ->whereNotNull('phone')
        ->where('phone', '!=', 'NULL')
        ->latest()
        ->get();
}

/**
 * SERVER ERROR
 */
function serverError()
{
    return app('Illuminate\Http\Response')->status();
}

/**
 * MEMORY UEAGE
 */
function convert($size)
{
    $unit = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

    return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
}

/**
 * Language
 */
function languages()
{
    return Language::all();
}

/**
 * Bounced
 */
function bounced()
{
    return BouncedEmail::Bounced()->HasAgent()->latest()->paginate(20);
}

/**
 * INVOICE NUMBER
 */
function invoiceNumber()
{
    return date('Y') . rand(1000, 10000);
}

/**
 * GET USER
 */
function getUser($id)
{
    return User::where('id', $id)->first();
}
/**
 * CHECK USER
 */
function checkUser($email)
{
    return User::where('email', $email)->first();
}

/**
 * PLAN ID
 */
function planPurchase($id)
{
    return PlanPurchased::where('id', $id)->first();
}

/**
 * GET PLAN DETAILS
 */
function planDetails($id)
{
    return SubscriptionPlan::where('id', $id)->first();
}

/**
 * USER NOTIFICATIONS
 */
function telling($link, $message)
{
    $tell = new UserNotification();
    $tell->owner_id = Auth::user()->id;
    $tell->link = $link ?? null;
    $tell->message = $message ?? null;
    $tell->save();
}

/**
 * NOTIFICATIONS
 */
function notifications()
{
    if (Auth::check()) {
        return UserNotification::HasAgent()->latest()->paginate(10);
    } else {
        return 0;
    }
}
/**
 * NOTIFICATIONS
 */
function unreadNotifications()
{
    return UserNotification::HasAgent()->where('is_read', 0)->count();
}

/**
 * CAMPAIGN LOG
 */
function campaignLog($campaign_id, $campaign_name, $message)
{
    $campaignLog = new CampaignLog();
    $campaignLog->owner_id = Auth::user()->id;
    $campaignLog->campaign_id = $campaign_id;
    $campaignLog->campaign_name = $campaign_name;
    $campaignLog->message = $message ?? null;
    $campaignLog->save();
}

function campaignlogs()
{
    return CampaignLog::HasAgent()->latest()->paginate(30);
}

function getCampaignName($id)
{
    return Campaign::where('id', $id)->first();
}

function getCampaignEmails($campaign_id)
{
    return CampaignEmail::where('campaign_id', $campaign_id)->count();
}

function listCampaignEmails($campaign_id)
{
    return CampaignEmail::where('campaign_id', $campaign_id)->get();
}

/**
 * PURCHASED PLANS
 */
function invoice_path($file)
{
    $path = public_path('invoice_pdf/');
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }

    return $path . $file . '.pdf';
}

function purchased($page)
{
    if (Auth::user()->user_type == 'Admin') {
        return PlanPurchased::latest()->paginate($page);
    } else {
        return PlanPurchased::where('user_id', Auth::user()->id)->latest()->paginate($page);
    }
}

/**
 * MOST USED PAYMENT
 */
function mostUsedGateway($gateway)
{
    if (Auth::user()->user_type == 'Admin') {
        return PlanPurchased::where('gateway', $gateway)->where('status', 1)->count();
    } else {
        return PlanPurchased::where('user_id', Auth::user()->id)->where('gateway', $gateway)->where('status', 1)->count();
    }
}

/**
 * EARNING
 */

/**
 * Get Month Wise Current Year Data
 */
function monthWiseEarnings()
{
    return PlanPurchased::where('status', 1)->select(DB::raw('(COUNT(*)) as count'), DB::raw('MONTHNAME(created_at) as monthname'), DB::raw('SUM(price) as total_amount'))
        ->whereYear('created_at', date('Y'))
        ->orderByRaw('DATE_FORMAT(created_at, "%m-%d")')
        ->groupBy('monthname')
        ->get();
}

/**
 * Total Earned
 */
function totalEarned()
{
    return PlanPurchased::where('status', 1)
        ->sum('price');
}

/**
 * weeklyTopSenders
 */
function weeklyTopSenders()
{
    $last7days = Carbon::now();
    $last7days->subDays(7);

    return BouncedEmail::select('owner_id')
        ->groupBy('owner_id')
        ->orderByRaw('COUNT(*) DESC')
        ->whereDate('created_at', '>=', $last7days)
        ->take(10)
        ->get();
}

/**
 * weeklyTopSendersRecord
 */
function weeklyTopSendersRecord($id)
{
    return BouncedEmail::where('owner_id', $id)->count();
}

/**
 * THEME
 */
function theme()
{
    try {
        $theme = org('theme');
        if (!empty($theme)) {
            return $theme;
        }
    } catch (\Throwable $th) {
        // Fallback if db table not ready
    }

    return env('ACTIVE_THEME', 'mailwave');
}

/**
 * FRONTEND
 */
function frontend($data)
{
    return Frontend::where('label', $data)->first()->value;
}
/**
 * MODULE
 */
function frontendModule($module)
{
    return FrontendModule::where('label', $module)->first();
}
/**
 * FEATURES
 */
function frontendFeatures($features)
{
    return FrontendFeature::where('label', $features)->first();
}

/**
 * IMAGES FOR AUTH
 */
function ImageForAuth($folder)
{
    $path = public_path('auth/' . $folder);
    $files = File::files($path);

    return $randomFile = $files[rand(0, count($files) - 1)]->getRelativePathname();
}

/**
 * GET IMAGE
 */
function getImageForAuth($path)
{
    return asset('auth/' . $path . '/' . ImageForAuth($path));
}

/**
 * NOT FOUND
 */
function notFound($image)
{
    return asset('not_found/' . $image);
}

/**
 * INSTALLER
 */
function versionOfPhp()
{
    return number_format((float) phpversion(), 2, '.', '');
}

/**
 * totalExpense
 */
function totalExpense()
{
    return PlanPurchased::where('user_id', Auth::user()->id)->where('status', 1)->sum('price');
}

/**
 * lastPurchasedPlan
 */
function lastPurchasedPlan()
{
    return PlanPurchased::where('user_id', Auth::user()->id)->where('status', 1)->latest()->first();
}

/**
 * totalCustomer
 */
function totalCustomer()
{
    return User::where('user_type', 'Customer')->count();
}

/**
 * totalPurchased
 */
function totalPurchased()
{
    return PlanPurchased::where('status', 1)->count();
}

// totalSentMail
function totalSystemSentMail()
{
    return UserSentRecord::count();
}

// totalSentMail
function totalSystemSMSSent()
{
    return SmsLog::count();
}

/**
 * TOTAL SUBSCRIBED
 */
function totalSubscribed()
{
    return EmailContact::where('is_subscribed', 1)->count();
}

/**
 * Email List
 */
function emailList()
{
    return EmailContact::HasAgent()
        ->Active()
        ->whereNotNull('email')
        ->latest()
        ->paginate(30);
}

/**
 * phone List
 */
function phoneList()
{
    return EmailContact::HasAgent()
        ->Active()
        ->whereNotNull('phone')
        ->latest()
        ->paginate(30);
}

/**
 * SERVER STATUS
 */
function server_cache_clear()
{
    Artisan::call('cache:clear');

    return back();
}

function server_optimize()
{
    Artisan::call('optimize:clear');

    return back();
}

function server_memory_get_usage()
{
    return convert(memory_get_usage(true)); // memory in use
}

function server_max_execution_time()
{
    $normalTimeLimit = ini_get('max_execution_time');
    ini_set('max_execution_time', 60000000);

    return ini_set('max_execution_time', $normalTimeLimit);
}

function server_max_input_time()
{
    return ini_get('max_input_time');
}

function server_memory_limit()
{
    $normalTimeLimit = ini_get('memory_limit');
    ini_set('memory_limit', '10000M');

    return ini_set('memory_limit', $normalTimeLimit);
}

function server_upload_max_filesize()
{
    return ini_get('upload_max_filesize');
}

function server_max_file_uploads()
{
    return ini_get('max_file_uploads');
}

function server_default_socket_timeout($time)
{
    $normalTimeLimit = ini_get('default_socket_timeout');
    ini_set('default_socket_timeout', $time);

    return ini_set('default_socket_timeout', $normalTimeLimit);
}

function server_php_version()
{
    return $_SERVER['PHP_SELF'];
}

function application_version()
{
    return app()->version();
}

function server_db_port()
{
    return env('DB_PORT');
}

function server_remote_port()
{
    return $_SERVER['REMOTE_PORT'];
}

function server_request_time()
{
    return $_SERVER['REQUEST_TIME'];
}

function server_post_max_size()
{
    return ini_get('post_max_size');
}

function server_realpath_cache_size()
{
    return ini_get('realpath_cache_size');
}

function software_version()
{
    return env('VERSION');
}

function server_MariaDB()
{
    $con = mysqli_connect(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), env('DB_DATABASE'));

    if (mysqli_connect_errno()) {
        return 'MySQL: ' . mysqli_connect_error();
        exit();
    }

    return mysqli_get_server_info($con);
}

function CheckQueue()
{
    $jobs = Queue::count();

    if ($jobs > 0) {
        return 'btn-animated';
    } else {
        return '';
    }
}

function CheckFailedJob()
{
    $failed = FailedJob::count();

    if ($failed > 0) {
        return 'btn-animated';
    } else {
        return '';
    }
}

function demo_mode()
{
    if (env('DEMO_MODE') === 'YES') {
        Alert::warning('warning', 'This is demo purpose only');

        return back();
    }
}

/**
 * EMAIL SERVICE
 */
function activeEmailService()
{
    return EmailService::Active()->first()->provider_name ?? null;
}

function sendEmailFrom()
{
    return EmailService::Active()->first()->from;
}

function getEmailInfo()
{
    return EmailService::Active()->first();
}

function getUserActiveEmailDetails($id): EmailService
{
    return EmailService::where('id', $id)->with(['sender_email' => function ($query) {
        $query->where('owner_id', Auth::id());
    }])->first();
}

/**
 * SMS Provider
 */
function getSmsInfo($sms)
{
    $hasSms = Sms::where('sms_name', $sms)->HasAgent()->exists();

    if ($hasSms) {
        return true;
    } else {
        return false;
    }
}

/**
 * ! Deprecated from 6.6.4 - 13-12-2023
 */

// takes a string of CSV data and returns a JSON representing an array of objects (one object per row)
function convert_csv_to_json($csv_data)
{

    //     $context = array(
    //         'http'=>array(
    //             'follow_location' => false,
    //             'max_redirects' => 1000000
    //             )
    //     );

    //     $context = stream_context_create($context);

    //     if (($handle = fopen($csv_data, "r", false, $context)) !== FALSE) {
    //     $csvs = [];
    //     while(! feof($handle)) {
    //     $csvs[] = fgetcsv($handle);
    //     }
    //     $datas = [];
    //     $column_names = [];
    //     foreach ($csvs[0] as $single_csv) {
    //         $column_names[] = $single_csv;
    //     }
    //     foreach ($csvs as $key => $csv) {
    //         if ($key === 0) {
    //             continue;
    //         }
    //         foreach ($column_names as $column_key => $column_name) {
    //             $datas[$key-1][$column_name] = $csv[$column_key];
    //         }
    //     }

    //     return $json = json_encode($datas);

    // }

    /**
     * NEW CSV CONVERTER
     */
    $cols = [
        'id',
        'owner_id',
        'name',
        'email',
        'country_code',
        'phone',
        'favourites',
        'blocked',
        'trashed',
        'is_subscribed',
        'deleted_at',
        'created_at',
        'updated_at',
    ];

    $csv = file($csv_data);
    $output = [];

    foreach ($csv as $line_index => $line) {
        if ($line_index > 0) { // I assume the the first line contains the column names.
            $newLine = [];
            $values = explode(',', $line);
            foreach ($values as $col_index => $value) {
                if ($value != null) {
                    $newLine[$cols[$col_index]] = $value;
                }
            }
            $output[] = $newLine;
        }
    }

    return $json_output = json_encode($output, true);
}

/**
 * DOWNLOAD CSV
 */
function csv_path()
{
    return public_path('sample_data.csv');
}

/**
 * Layout
 */
function checkthemeLayout()
{
    return OrganizationSetup::where('name', 'layout')->first();
}

function themeLayout()
{
    $layout = OrganizationSetup::where('name', 'layout')->first();

    if ($layout->value != null) {
        return $layout->value;
    } else {
        return 'classic';
    }
}

function checkthemeDirection()
{ // RTL ------------------------
    return OrganizationSetup::where('name', 'direction')->first();
}

function themeDirection()
{ // RTL ------------------------
    $layout = OrganizationSetup::where('name', 'direction')->first();

    if ($layout->value != null) {
        return $layout->value;
    } else {
        return 'ltr';
    }
}

/**
 * Scenarioes
 */
function scenarioes($provider)
{
    return InfobipScenario::where('provider', $provider)->latest()->get();
}

/**
 * VERSION 1.3
 */

//   ChatProviders
function chatProviders()
{
    return ChatProvider::where('status', 1)->select('body')->get();
}

/**
 * VERSION 1.3:::END
 */

/**
 * VERSION 2.2
 */
function check_key()
{
    $checkApi = App\Models\ApiKey::HasAgent()->first();

    if ($checkApi == null) {
        return true;
    } else {
        return false;
    }
}

function app_key()
{
    $bytes = random_bytes(20);
    $key = Str::lower(orgName()) . '_' . bin2hex($bytes);

    return $key;
}

function app_secret_key()
{
    $bytes = random_bytes(10);
    $secret_key = Str::lower(orgName()) . '_' . bin2hex($bytes . time());

    return $secret_key;
}

function user_app_key()
{
    return App\Models\ApiKey::HasAgent()->first()->app_key ?? app_key();
}

function user_app_secret_key()
{
    return App\Models\ApiKey::HasAgent()->first()->app_secret_key ?? app_secret_key();
}

/**
 * VERSION 2.2 END
 */

/**
 * VERSION 3.0
 */
function smtp_provider_list()
{
    return $smtp_provider_list = ['Gmail', 'Yahoo', 'Webmail', 'Mailgun', 'Zoho', 'Elastic', 'Amazon SES', 'Mailtrap', 'Postmark', 'Mailjet', 'Sendgrid'];
}

function smtp_driver_list()
{
    return $smtp_provider_list = ['smtp', 'sendmail', 'mailgun'];
}

function getSmtpServerWiseList()
{
    return EmailService::with(['sender_email' => function ($query) {
        $query->where('owner_id', Auth::id());
    }])->get()->groupBy('provider_name');
}

function getSmtpServerName($id)
{
    return EmailService::where('id', $id)->first()->provider_name;
}

/**
 * VERSION 3.0::END
 */

/**
 * VERSION 4.0
 */
function elements_json()
{
    $jsonReqUrl = asset('maildoll-editor/elements-1.json');
    $reqjson = file_get_contents($jsonReqUrl);

    return $reqjsonDecode = json_decode($reqjson, true);
}

function elements_json_url()
{
    return asset('maildoll-editor/elements-1.json');
}

function pro_builder_supported($name)
{
    $fileNames = [];
    $path = public_path('maildoll-editor/templates/saved/');
    $files = \File::allFiles($path);

    foreach ($files as $file) {
        array_push($fileNames, pathinfo($file)['filename']);
    }

    $check_file = in_array($name, $fileNames);

    if ($check_file != null) {
        return true;
    } else {
        return false;
    }
}

/**
 * VERSION 4.0::END
 */

/**
 * VERSION 4.1
 */
function campaignTracker($campaign_id)
{
    return EmailTracker::select('campaign_id', DB::raw('count(*) as total'))
        ->where('campaign_id', $campaign_id)
        ->first();
}

/**
 * CLICKS
 */
function campaignEmailTotalClicks($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->sum('total_clicks');
}

function campaignEmailUniqueClicks($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->where('total_clicks', '!=', 0)->count();
}

function allPages()
{
    return Page::where('status', 1)->get();
}

/**
 * OPEN RATE
 */
function campaignEmailNotClicked($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->where('record', 'NOT OPEN')->count();
}

function campaignEmailClicked($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->where('record', 'OPENED')->count();
}

function campaignEmailClickedAndNotClicked($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->count();
}

function campaignOpenRate($campaign_id)
{
    $rate = (campaignEmailClicked($campaign_id) / campaignEmailClickedAndNotClicked($campaign_id)) * 100;

    return round($rate);
}

/**
 * DELIVERY RATE
 */
function campaignEmailNotDelivered($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->where('status', 1)->count();
}

function campaignEmailDelivered($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->where('status', 0)->count();
}

function campaignEmailNotOpenedAndNotOpen($campaign_id)
{
    return EmailTracker::where('campaign_id', $campaign_id)->count();
}

function campaignDeliveryRate($campaign_id)
{
    $rate = (campaignEmailDelivered($campaign_id) / campaignEmailNotOpenedAndNotOpen($campaign_id)) * 100;

    return round($rate);
}

/**
 * STATISTICS
 */
function campaignStatisticsRecipients($id = null)
{
    if ($id == null) {
        return EmailTracker::count('email_id');
    } else {
        return EmailTracker::where('campaign_id', $id)->count('email_id');
    }
}

function activeUserWiseCampaignStatisticsRecipients()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->count('email_trackers.email_id');
}

function campaignStatisticsDelivered($id = null)
{
    if ($id == null) {
        return EmailTracker::where('status', 0)->count();
    } else {
        return EmailTracker::where('campaign_id', $id)->where('status', 0)->count();
    }
}

function activeUserWiseCampaignStatisticsDelivered()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->where('email_trackers.status', 0)
        ->count();
}

function campaignStatisticsFailed($id = null)
{
    if ($id == null) {
        return EmailTracker::where('status', 1)->count();
    } else {
        return EmailTracker::where('campaign_id', $id)->where('status', 1)->count();
    }
}

function activeUserWiseCampaignStatisticsFailed()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->where('email_trackers.status', 1)
        ->count();
}

function campaignStatisticsOpened($id = null)
{
    if ($id == null) {
        return EmailTracker::where('record', 'OPENED')->count();
    } else {
        return EmailTracker::where('campaign_id', $id)->where('record', 'OPENED')->count();
    }
}

function activeUserWiseCampaignStatisticsOpened()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->where('email_trackers.record', 'OPENED')
        ->count();
}

function campaignStatisticsClicked($id = null)
{
    if ($id == null) {
        return EmailTracker::sum('total_clicks');
    } else {
        return EmailTracker::where('campaign_id', $id)->sum('total_clicks');
    }
}

function activeUserWiseCampaignStatisticsClicked()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->sum('email_trackers.total_clicks');
}

function campaignStatisticsUniqueClicked($id = null)
{
    if ($id == null) {
        return EmailTracker::where('record', 'OPENED')->count();
    } else {
        return EmailTracker::where('campaign_id', $id)->where('record', 'OPENED')->count();
    }
}

function activeUserWiseCampaignStatisticsUniqueClicked()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->where('email_trackers.record', 'OPENED')
        ->count();
}

function campaignStatisticsBounced($id = null)
{
    if ($id == null) {
        return EmailTracker::where('status', 1)->count();
    } else {
        return EmailTracker::where('campaign_id', $id)->where('status', 1)->count();
    }
}

function activeUserWiseCampaignStatisticsBounced()
{
    $loggedInUser = Auth::user();

    return $loggedInUser->campaigns()
        ->leftJoin('email_trackers', 'campaigns.id', '=', 'email_trackers.campaign_id')
        ->where('email_trackers.status', 1)
        ->count();
}

function campaignTotalRan($id)
{
    return EmailTracker::where('campaign_id', $id)->count();
}

function calendar($date = null)
{
    $date = empty($date) ? Carbon::now() : Carbon::createFromDate($date);
    $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
    $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);
    $html = collect();
    while ($startOfCalendar <= $endOfCalendar) {
        $html->push($startOfCalendar->format('d-m-Y'));
        $startOfCalendar->addDay();
    }

    return $html;
}

function todayOnCalender($date = null)
{
    $date = empty($date) ? Carbon::now() : Carbon::createFromDate($date);
    $startOfCalendar = $date->copy()->firstOfMonth()->startOfWeek(Carbon::SUNDAY);
    $endOfCalendar = $date->copy()->lastOfMonth()->endOfWeek(Carbon::SATURDAY);
    $html = collect();
    while ($startOfCalendar <= $endOfCalendar) {
        $html->push($startOfCalendar->isToday());
        $startOfCalendar->addDay();
    }

    return $html;
}

/**
 * check schedule
 */
function checkCampaignSchedule($campaign_id, $date)
{
    $days = ScheduleEmail::where('campaign_id', $campaign_id)->get('scheduled_at');

    $data = collect();
    foreach ($days as $day) {
        $data->push(Carbon::parse($day->scheduled_at)->format('d-m-Y'));
    }

    foreach ($data as $value) {
        if ($value == $date) {
            return 'ase';
        }
    }
}

/**parsing date */

function parseDate($date)
{
    return Carbon::parse($date)->format('d');
}

/**
 * VERSION 4.1::END
 */

/**
 * VERSION 4.2::START
 */
function CampaignEmailArrayToString($id)
{
    $emails = CampaignEmail::where('campaign_id', $id)->get('email_id');

    $data = collect();

    foreach ($emails as $email) {
        $data->push($email->email_id);
    }

    $stringData = explode(',', $data->implode(','));

    $jsonToString = '';

    foreach ($stringData as $value) {
        $jsonToString .= '"' . $value . '",';
    }

    return $jsonToString;
}

function GroupEmailArrayToString($id)
{
    $emails = EmailListGroup::where('email_group_id', $id)->get('email_id');

    $data = collect();

    foreach ($emails as $email) {
        $data->push($email->email_id);
    }

    $stringData = explode(',', $data->implode(','));

    $jsonToString = '';

    foreach ($stringData as $value) {
        $jsonToString .= '"' . $value . '",';
    }

    return $jsonToString;
}

/**
 * VERSION 4.2::ENDS
 */

/**
 * VERSION 4.3::STARTS
 */
function argonContent($cid)
{
    return App\Models\ArgonContent::where('cid', $cid)->first()->text ?? null;
}

function argonImagePath($path)
{
    if ($path != null) {
        return asset('frontend/argon/uploads/' . $path);
    }
}

/**
 * VERSION 4.3::ENDS
 */

/**
 * VERSION 5.1.0::STARTS
 */

//  teleSignSMS
function teleSignSMS($phone, $message, $username, $password)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://rest-api.telesign.com/v1/messaging',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => "phone_number={$phone}&message={$message}&message_type=MKT",
        CURLOPT_HTTPHEADER => [
            'Authorization: Basic ' . base64_encode($username . ':' . $password) . '',
            'Content-Type: application/x-www-form-urlencoded',
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

//  sinchSMS
function sinchSMS($from, $to, $body, $service_id, $token)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://us.sms.api.sinch.com/xms/v1/' . $service_id . '/batches',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '
            {
                "from": "' . $from . '",
                "to": [
                    "' . $to . '"
                ],
                "body": "' . $body . '"
            }',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token . '',
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

// Clickatell SMS
function clickatellSMS($to, $body, $token)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://platform.clickatell.com/v1/message',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "messages": [
                {
                    "channel": "sms",
                    "to": "{$to}",
                    "content": "{$body}"
                }
            ]
        }',
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: ' . $token,
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);

    return $response;
}

// Mailjet SMS
function mailjetSMS($to, $from, $body, $token)
{
    $curl = curl_init();

    curl_setopt_array($curl, [
        CURLOPT_URL => 'https://api.mailjet.com/v4/sms-send',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => '{
            "Text": "' . $to . '"
            "To": "' . $body . '"
            "From": "' . $from . '"
        }',
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);

    curl_close($curl);
    echo $response;
}

/**
 * VERSION 5.1.0::ENDS
 */

/**
 * SAAS
 */

// trim domain
function trimDomain($domain)
{
    $checkProtocol = Str::contains($domain, ['https://', 'http://']);

    if ($checkProtocol == true) {
        if (Str::contains($domain, 'https://')) {
            $removeHttps = Str::after($domain, 'https://');
        } elseif (Str::contains($domain, 'http://')) {
            $removeHttps = Str::after($domain, 'http://');
        }

        $base_domain = Str::before($removeHttps, '/public');

        return $base_domain;
    } else {
        return $domain;
    }
}

// subdomain

function subdomain()
{
    $subdomain = implode('.', explode('.', $_SERVER['HTTP_HOST'], -2));

    return $subdomain;
}

function full_domain()
{
    $full_domain = $_SERVER['HTTP_HOST'];

    return $full_domain;
}

/**
 * Check SaaS database Name
 */
function checkSaaSDB()
{
    $subdomain = implode('.', explode('.', $_SERVER['HTTP_HOST'], -2)); // -2 or -1 to check the domain name only maildoll.com => output: maildoll

    // return $subdomain.'.' . env('YOUR_DOMAIN');

    if ($_SERVER['HTTP_HOST'] === $subdomain . '.' . env('YOUR_DOMAIN')) {
        $subdomain = implode('.', explode('.', $_SERVER['HTTP_HOST'], -2));

        return $selectedDatabase = env('DB_PREFIX') . $subdomain;
    } else {
        return $selectedDatabase = env('DB_DATABASE') . '_________';
    }
}

/**
 * Disable at SaaS
 */
function disableAtSaaS()
{
    if (env('SAAS_ACTIVE') == 'YES') {
        if (subdomain()) {
            return false;
        } else {
            return true;
        }
    } else {
        return true;
    }
}

// check saas
function saas()
{
    if (env('SAAS_ACTIVE') == 'YES') {
        return true;
    } else {
        return false;
    }
}

// api url
function apiUrl()
{
    return env('API_URL');
}

function saas_key()
{
    return env('SAAS_KEY');
}

// cURL Response
function cURL($domain, $api_url, $method)
{
    if (env('SAAS_ACTIVE') == 'YES') {
        $url = apiUrl() . $api_url . '?domain=' . $domain . '&saas_key=' . saas_key();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_HTTPHEADER => [],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return $response;
    } else {
        return 'SaaS is not active';
    }
}

// API

// saas_check_expiry
function saas_check_expiry($domain)
{ // YES or NO
    return cURL($domain, '/api/check-expiry', 'POST');
}

// user_subscription_data
function user_subscription_data($domain)
{ // collection
    return cURL($domain, '/api/user-subscription-data', 'GET');
}

// user_emails_limit
function user_emails_limit($domain)
{ // integer
    return cURL($domain, '/api/user-emails-limit', 'GET');
}

// user_email_limit_check
function user_email_limit_check($domain)
{ // HAS-LIMIT/LIMIT-CROSSED
    return cURL($domain, '/api/user-email-limit-check', 'GET');
}

// user_email_limit_left
function user_email_limit_left($domain)
{ // integer
    return cURL($domain, '/api/user-email-limit-left', 'GET');
}

// user_item_limit_decrement
function user_email_limit_decrement($domain)
{ // integer
    return cURL($domain, '/api/user-email-limit-decrement', 'POST');
}

// payment_histories
function payment_histories($domain)
{ // integer
    return cURL($domain, '/api/payment-histories', 'GET');
}

// user_branch_limit
function user_sms_limit($domain)
{ // integer
    return cURL($domain, '/api/user-sms-limit', 'GET');
}

// user_branch_limit_check
function user_sms_limit_check($domain)
{ // String
    return cURL($domain, '/api/user-sms-limit-check', 'GET');
}

// user_sms_limit_left
function user_sms_limit_left($domain)
{ // integer
    return cURL($domain, '/api/user-sms-limit-left', 'GET');
}

// user_sms_limit_decrement
function user_sms_limit_decrement($domain)
{ // integer
    return cURL($domain, '/api/user-sms-limit-decrement', 'POST');
}

// user_subscription_date_endin
function user_subscription_date_endin($domain)
{ // integer
    return cURL($domain, '/api/user-subscription-date-endin', 'GET');
}

// user_restriction
function user_restriction($domain)
{ // boolean
    return cURL($domain, '/api/user-restriction', 'GET');
}
// API::ENDS

/**
 * SAAS::ENDS
 */

/**
 * VERSION 5.3.0::STARTS
 */

// generateApiKey
function generateApiKey()
{
    $api = ApiKey::HasAgent()->first();

    return hash_hmac('sha256', $api->app_key . Carbon::now()->year, $api->app_secret_key); // algorithm, data . year, key
}

// get token user id
function getTokenUserId($token)
{
    $getToken = ApiKey::where('token', $token)->first();

    return $getToken->owner_id;
}

/**
 * Remove third brackets and double quotes
 */
function removeThirdBrackets($string)
{
    $string = str_replace('[', '', $string);
    $string = str_replace(']', '', $string);
    $string = str_replace('"', '', $string);

    return $string;
}

function emailAddressVerify($email)
{
    $verify = new EmailVerify;

    if ($verify->verify_domain($email)) {
        return 1; // Domain has been verified
    } else {
        return 0; // Domain has NOT been verified
    }
}

/**
 * VERSION 5.3.0::ENDS
 */

/**
 * VERSION 6.0.0::STARTS
 */
function createUserXMLfile($say, $audio, $file_name)
{
    $xml =
        '<?xml version="1.0" encoding="UTF-8"?>
<Response>
  <Say voice="alice">' . $say . '</Say>
  <Play>' . $audio . '</Play>
</Response>';

    $path = '/public/voice/' . $file_name . '.xml';

    File::put(base_path($path), $xml);
}

// voice_server_list
function voice_server_list()
{
    return [
        'Twilio',
    ];
}

// getVoiceServerWiseList
function getVoiceServerWiseList()
{
    return VoiceServer::get()->groupBy('provider');
}

// audioUpload
function audioUpload($file, $folder)
{
    return $file->store('/voice' . $folder);
}

// audioPath
function audioPath($file)
{
    return asset($file);
}

// allCampaigns
function allCampaigns($type)
{
    return Campaign::where('type', $type)->latest()->get();
}

// checkCampaignExistsInAutoresponder
function checkCampaignExistsInAutoresponder($campaign_id)
{
    $autoresponder = App\Models\Autoresponder::where('campaign_id', $campaign_id)->first();

    if ($autoresponder) {
        return true;
    } else {
        return false;
    }
}

// disable_theme
function disable_theme()
{
    if (env('DISABLE_THEME') == 'YES') {
        return true;
    } else {
        return false;
    }
}

// sms providers
function sms_provider_list()
{
    return $sms_provider_list = [
        'twilio',
        'nexmo',
        'plivo',
        'infobip',
        'viber',
        'whatsapp',
        'telesign',
        'sinch',
        'clickatell',
        'mailjet',
        'textlocal',
        // 'lao',
        // 'aakash'
    ];
}

function getSMSServerWiseList()
{
    return App\Models\SmsService::with(['sms_sender_id' => function ($query) {
        $query->where('owner_id', Auth::id());
    }])->get()->groupBy('sms_name');
}

function getSMSServerName($id)
{
    return App\Models\SmsService::where('id', $id)->first()->sms_name;
}

function getSMSServerSenderInfo($id)
{
    return App\Models\SmsService::where('id', $id)->first();
}

/**
 * VERSION 6.0.0::ENDS
 */

/**
 * VERSION 6.2.0
 */
function monthNameByNumber($number)
{
    $month = [
        '1' => 'January',
        '2' => 'February',
        '3' => 'March',
        '4' => 'April',
        '5' => 'May',
        '6' => 'June',
        '7' => 'July',
        '8' => 'August',
        '9' => 'September',
        '10' => 'October',
        '11' => 'November',
        '12' => 'December',
    ];

    return $month[$number];
}

// marketplace csv path
function marketplace_email_csv_path($country)
{ // marketplace_email_csv_path
    return env('APP_URL') . '/public/uploads/marketplace/csv/email/' . $country . '.csv';
}

function marketplace_sms_csv_path()
{ // marketplace_sms_csv_path
    return env('APP_URL') . '/public/uploads/marketplace/csv/sms/' . $country . '.csv';
}

function monthlyWiseSales()
{
    return App\Models\MarketplaceSell::selectRaw("
                            sum(price) AS price,
                            DATE_FORMAT(created_at, '%m') AS new_date,
                            YEAR(created_at) AS year,
                            MONTH(created_at) AS month
                        ")
        ->groupBy('new_date')
        ->get();
}

// country code list
function country_name_code_lists()
{
    // read json file
    $json = File::get(public_path('countries.json'));
    // convert json to array
    $country_code_list = json_decode($json, true);

    // return array
    return $country_code_list;
}

// marketplace csv files
function marketplace_csv_files()
{
    return App\Models\MarketplaceCSV::orderBy('country')->get();
}

// csv file path
function csv_file_path($file_name)
{
    return public_path('uploads/marketplace/csv/email/' . $file_name);
}

// directory csv file size
function directory_csv_file_size($file_name)
{
    return csv_file_path(File::size(public_path('uploads/marketplace/csv/email/' . $file_name)));
}

// bytes to MB
function convertToReadableSize($size)
{
    $base = log($size) / log(1024);
    $suffix = ['B', 'KB', 'MB', 'GB', 'TB'];
    $f_base = floor($base);

    return round(pow(1024, $base - floor($base)), 1) . $suffix[$f_base];
}

// all csv filze size
function all_csv_file_size()
{
    $total_size = 0;
    $files = File::allFiles(public_path('uploads/marketplace/csv/email'));
    foreach ($files as $file) {
        $total_size += File::size($file);
    }

    return convertToReadableSize($total_size);
}

// MarketplaceSetting
function marketplace_setting($csv_id)
{
    return App\Models\MarketplaceSetting::where('csv_id', $csv_id)->first();
}

// check marketplace_settings exists
function marketplace_settings_exists($csv_id)
{
    return App\Models\MarketplaceSetting::where('csv_id', $csv_id)->exists();
}

// check marketplace_c_s_v_s exists
function marketplace_csv_exists($country)
{
    return App\Models\MarketplaceCSV::where('country', $country)->exists();
}

// total sales
function marketplace_total_sales()
{
    return App\Models\MarketplaceSell::sum('price');
}

// today earnings
function marketplace_today_earnings()
{
    return App\Models\MarketplaceSell::whereDate('created_at', Carbon::today())->sum('price');
}

// total marketplace_total_buyer
function marketplace_total_buyer()
{
    return App\Models\MarketplaceSell::count();
}

// most purchased country
function marketplace_most_purchased_country()
{
    return App\Models\MarketplaceSell::selectRaw('
                            country,
                            count(country) AS total_country
                        ')
        ->groupBy('country')
        ->orderBy('total_country', 'DESC')
        ->first()->country ?? 'N/A';
}

// today new buyer count
function marketplace_today_new_buyer_count()
{
    return App\Models\MarketplaceSell::whereDate('created_at', Carbon::today())->count();
}
// new contact count
function new_contact_count()
{
    return App\Models\Contact::where('reply', null)->count();
}

// unique buyer email
function marketplace_unique_buyer_email()
{
    return App\Models\MarketplaceSell::get()->unique('email')->count();
}

// get Marketpalce Settings
function marketplace_available_csv()
{
    return App\Models\MarketplaceSetting::with('marketplace_csv')->get();
}

// find country from country_name_code_lists()
function marketplace_country_code($country)
{
    $country_code_list = country_name_code_lists();
    foreach ($country_code_list as $key => $value) {
        if ($value['code'] == $country) {
            return $value['country'];
        }
    }
}

// check marketplace_settings
function marketplace_settings($csv_id)
{
    return App\Models\MarketplaceSetting::where('csv_id', $csv_id)->first();
}

/**
 * VERSION 6.2.0::ENDS
 */

/**6.3.1::STARTS */
function maildoll_config($key)
{
    return config('maildoll.' . $key);
}

/** Patchs Path */
function patchs_path()
{
    return base_path('/patchs/');
}

function listdirfile_by_date()
{
    if (maildoll_config('patch') == true) { // if patch is true

        $str_path = base_path(); // get the path of the current directory

        // get the list of files in the current directory
        $cls_rii = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($str_path),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        $ary_files = []; // create an array to store the files

        foreach ($cls_rii as $str_fullfilename => $cls_spl) {
            if ($cls_spl->isFile()) {
                $ary_files[] = $str_fullfilename;
            }
        }

        // ary_files is an array of all the files in the directory
        $ary_files = array_combine(
            $ary_files,
            array_map('filemtime', $ary_files) // get the file modification time
        );

        arsort($ary_files); // sort by date, newest first

        // get $ary_files after date time of maildoll_config(last_patch_at)

        $last_patch_at = maildoll_config('last_patch_at');
        $patch_strtotime = strtotime($last_patch_at);
        $patch_date = date('D/M/Y H:i:s', $patch_strtotime);

        // show only files after $patch_date
        $ary_files = array_filter(
            $ary_files,
            function ($value) use ($patch_strtotime) {
                return $value > $patch_strtotime;
            }
        );

        $str_latest_file = key($ary_files); // str_latest_file

        $main_file_path = str_replace($str_path, '', $str_latest_file); // main_file_path

        $file_name = basename($main_file_path); // file_name

        $modified_date = date('Y-m-d H:i:s', $ary_files[$str_latest_file]); // modified_date

        $only_path = dirname(str_replace($str_path, '', $str_latest_file)); // only_path

        $renamed_path = substr(str_replace('\\', '%', dirname(str_replace($str_path, '', $str_latest_file))), 1); // renamed_path

        // copy modified file to patchs folder
        $new_file_path = 'patchs/'; // new_file_path

        $file_from = substr($main_file_path, 1); // file from

        $file_to = $new_file_path . $renamed_path . '\\' . $file_name; //  file to

        $check_file_contains = Str::contains($file_from, [
            '.git',
            '.vscode',
            '.env',
            '.env.example',
            '.env_prodcution',
            '.idea',
            '.htaccess',
            'artisan',
            '.styleci.yml',
            '.editorconfig',
            '.gitignore',
            '.gitattributes',
            '.gitmodules',
            '.composer.json',
            '.composer.lock',
            'env.txt',
            'index.php',
            'package-lock.json',
            'package.json',
            'phpunit.xml',
            'README.md',
            'server.php',
            'tailwind.config.js',
            'webpack.mix.js',
            'storage',
            'patchs',
            'vendor',
        ]); // check_file_contains

        if ($check_file_contains == false) {
            $check_patch = App\Models\Patch::where('file_from', $file_from)->first();

            if ($check_patch) {
                $check_patch->delete();
            }

            $patch_store = new App\Models\Patch;
            $patch_store->file_from = $file_from;
            $patch_store->file_to = $file_to;
            $patch_store->modified_date = $modified_date;
            $patch_store->renamed_path = $renamed_path;
            $patch_store->save();
        }

        // Get all patchs
        $patchs = App\Models\Patch::get();

        $base_domain = base_path() . '/'; // base_domain

        foreach ($patchs as $patch) {

            // create folder if not exists
            if (!File::exists($base_domain . $new_file_path . $patch->renamed_path)) {
                File::makeDirectory($base_domain . $new_file_path . $patch->renamed_path, 0777, true); // create folder
            }

            $exact_file_name = basename($patch->file_from); // exact_file_name

            // copy file
            copy($base_domain . $patch->file_from, $base_domain . $patch->file_to);

            // create zip
            $zip = new ZipArchive();
            $zip_name = $patch->renamed_path;
            $zip_path = base_path('patchs/');
            $zip_file = $zip_path . $zip_name . '.zip';
            if ($zip->open($zip_file, ZipArchive::CREATE) === true) {
                $zip->addFile($patch->file_to, $exact_file_name); // copy file dir, rename file name
                $zip->close();
            } else {
                return false;
            }

            // delete folder
            File::deleteDirectory($base_domain . $new_file_path . $patch->renamed_path);
        }

        return $patchs;
    } else { // if patch is false
        return false;
    }
}
/**6.3.1::ENDS */

/**6.3.2::STARTS */

function all_blogs()
{
    $blogs = Blog::where('status', 1)->latest()->paginate(10);

    return $blogs;
}
/**6.3.2::ENDS */

/**6.3.3::STARTS */

// enable_disable_payment_gateway
function enable_disable_payment_gateway($gateway)
{
    switch ($gateway) {
        case 'paypal': // paypal
            if (env('PAYPAL_PAYMENT') == 'NO' || env('PAYPAL_CLIENT_ID') == '' || env('PAYPAL_SECRET') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'stripe': // stripe
            if (env('STRIPE_PAYMENT') == 'NO' || env('STRIPE_KEY') == '' || env('STRIPE_SECRET') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'khalti': // khalti
            if (env('KHALTI_PAYMENT') == 'NO' || env('KHALTI_KEY') == '' || env('KHALTI_SECRET') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'flutterwave': // flutterwave
            if (env('FLW_PAYMENT') == 'NO' || env('FLW_PUBLIC_KEY') == '' || env('FLW_SECRET_KEY') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'instamojo': // instamojo
            if (env('IM_PAYMENT') == 'NO' || env('IM_API_KEY') == '' || env('IM_AUTH_TOKEN') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'paystack': // paystack
            if (env('PAYSTACK_PAYMENT') == 'NO' || env('PAYSTACK_PUBLIC_KEY') == '' || env('PAYSTACK_SECRET_KEY') == '' || env('PAYSTACK_PAYMENT_URL') == '' && env('MERCHANT_EMAIL') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'razorpay':
            if (env('RAZORPAY') == 'NO' || env('RAZORPAY_KEY') == '' || env('RAZORPAY_SECRET') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'mollie':
            if (env('MOLLIE') == 'NO' || env('MOLLIE_KEY') == '' || env('MOLLIE_PARTNER_ID') == '' || env('MOLLIE_PROFILE_ID') == '') {
                return false;
            } else {
                return true;
            }
            break;
        case 'paytm':
            if (env('PAYTM') == 'NO' || env('PAYTM_MERCHANT_ID') == '' || env('PAYTM_MERCHANT_KEY') == '') {
                return false;
            } else {
                return true;
            }
            break;

        default:
            return true;
            break;
    }
}

/**6.3.3::ENDS */

/**
 * TIMEZONE
 */
function timeZone()
{
    $json = file_get_contents(base_path('public/timezone.json'));

    return json_decode($json, true);
}

/**
 * Flutterwave Supported Countries and currencies
 */
function flutterwaveSupportedCountries()
{
    return [
        'Argentine Peso' => 'ARS',
        'Brazilian Real' => 'BRL',
        'British Pound Sterling' => 'GBP',
        'Canadian Dollar' => 'CAD',
        'Cape Verdean Escudo' => 'CVE',
        'Chilean Peso' => 'CLP',
        'Colombian Peso' => 'COP',
        'Congolese France' => 'CDF',
        'Egyptian Pound' => 'EGP',
        'SEPA' => 'EUR',
        'Gambian Dalasi' => 'GMD',
        'Ghanaian Cedi' => 'GHS',
        'Guinean Franc' => 'GNF',
        'Kenyan Shilling' => 'KES',
        'Liberian Dollar' => 'LRD',
        'Malawian Kwacha' => 'MWK',
        'Mexican Peso' => 'MXN',
        'Moroccan Dirham' => 'MAD',
        'Mozambican Metical' => 'MZN',
        'Nigerian Naira' => 'NGN',
        'Peruvian Sol' => 'SOL',
        'Rwandan Franc' => 'RWF',
        'Sierra Leonean Leone' => 'SLL',
        'São Tomé and Príncipe dobra' => 'STD',
        'South African Rand' => 'ZAR',
        'Tanzanian Shilling' => 'TZS',
        'Ugandan Shilling' => 'UGX',
        'United States Dollar' => 'USD',
        'Central African CFA Franc BEAC' => 'XAF',
        'West African CFA Franc BCEAO' => 'XOF',
        'Zambian Kwacha' => 'ZMW',

    ];
}

/**
 * Coupons
 */
function coupons()
{
    return Coupon::latest()->paginate(10);
}

/**
 * Country Json with Phone code
 */
function country_codes()
{
    $json = file_get_contents(base_path('public/countries.json')); // countries

    return json_decode($json, true);
}

/**
 * Agent owner ID
 */
function agent_owner_id()
{
    return Agent::where('user_id', Auth::id())->first()->assined_for_customer_id;
}

/**
 * Support Ticket List
 */
function supportTicketList()
{
    return SupportTicket::orderBy('id', 'desc')->paginate(15);
}

function chatgptRecent()
{
    return ChatGPTMessage::where('parent', true)->paginate(15);
}

function createOrOverwriteChatGptApiKey($value)
{
    $envFilePath = base_path('.env');
    $envContent = file_get_contents($envFilePath);

    // Check if the variable already exists
    $pattern = '/^CHATGPT_API_KEY=.*/m';
    if (preg_match($pattern, $envContent)) {
        // Update the existing variable's value
        $envContent = preg_replace($pattern, "CHATGPT_API_KEY=\"$value\"", $envContent);
    } else {
        // Add a new variable
        $envContent .= "\nCHATGPT_API_KEY=\"$value\"";
    }

    // Write the updated content back to the .env file
    file_put_contents($envFilePath, $envContent);
}

/**
 * Extract Json From a String Dump
 *
 * @return mixed
 */
function extractJson(string $text)
{
    $extractor = new JsonExtractor($text);

    return $extractor->extract();
}

function timezones(): array
{
    $timeZonesPath = public_path('timezone.json');
    $jsonContents = file_get_contents($timeZonesPath);
    $timeZonesArray = json_decode($jsonContents, true);

    return $timeZonesArray;
}

/**
 * Stores an image given an image request and a directory
 *
 * @param  UploadedFile  $file
 * @param  string  $old_path
 * @param  string  $prefix  skip if you need clientOriginalName
 * @param  string  $disk  default = public
 * @return string $new_path
 */
function saveFile($file, string $dir, ?string $prefix = '', string $disk = 'public')
{
    if ($file) {
        if ($prefix === '' || $prefix === null) {
            $prefix = Str::slug($file->getClientOriginalName());
        }
        $ext = $file->extension();
        $name = $prefix . '-' . now()->timestamp . '.' . $ext;
        $path = $file->storeAs("uploads/$dir", $name, $disk);

        return $path;
    } else {
        return $file;
    }
}

/**
 * Spells a given amount
 */
function spell(int $number, string $locale = 'en_US'): string
{
    $formatter = new NF($locale, NF::SPELLOUT);
    $spelled = $formatter->format($number);

    return $spelled;
}

function setEnv($values)
{
    $envFile = base_path('.env');
    foreach ($values as $key => $value) {
        $envContent = File::get($envFile);
        $pattern = "/^({$key}=)(.*)$/m";
        $updatedEnvContent = preg_replace($pattern, "$1\"{$value}\"", $envContent);
        File::put($envFile, $updatedEnvContent);
    }
    Artisan::call('config:clear');
}

function paymentGateways()
{
    $files = scandir(base_path('app/Services/Payment'));
    array_shift($files);
    array_shift($files);
    $services = [];
    foreach ($files as $file) {
        $key = str($file)->replace('.php', '')->lower()->toString();
        $method = str($key)->studly();
        $class = "App\\Services\\Payment\\{$method}";
        $obj = new $class;
        $services[$key] = $obj;
    }

    return $services;
}
function replaceTags($string, $tags)
{
    // Escape square brackets for use in regular expression
    $pattern = '/\[\[([^\]]+)\]\]/';

    // Use preg_replace_callback for flexibility in replacements
    return preg_replace_callback($pattern, function ($matches) use ($tags) {
        $key = $matches[1];
        if (isset($tags[$key])) {
            return $tags[$key];
        } else {
            // Return original string if key not found
            return '';
        }
    }, $string);
}

/**
 * adds Foreign key contarins between two tables and removes non existing foreign keys
 * @param string $parentTableName
 * @param string $childTableName
 * @param string $parentTableId default = id
 * @param string|null $foreignKey defaults to `str($parentTableName)->singular()->snake()->toString() . '_id`
 */
function addForeignKey(string $parentTableName, string $childTableName, string $parentTableId = 'id', ?string $foreignKey = null)
{
    $foreignKey = $foreignKey ?? str($parentTableName)->singular()->snake()->toString() . '_id';

    try {
        DB::table($childTableName)
            ->whereNotIn($foreignKey, function ($query) use ($parentTableName, $parentTableId) {
                $query->select($parentTableId)->from($parentTableName);
            })
            ->delete();

        Schema::table($childTableName, function (Blueprint $table) use ($parentTableName, $parentTableId, $foreignKey) {
            $table->foreign($foreignKey)
                ->references($parentTableId)
                ->on($parentTableName)
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    } catch (\Throwable $th) {
        if (str_contains($th->getMessage(), 'General error: 1826 Duplicate foreign key constraint')) {
            # code...
            removeForeignKey($childTableName, $foreignKey);
            Schema::table($childTableName, function (Blueprint $table) use ($parentTableName, $parentTableId, $foreignKey) {
                $table->foreign($foreignKey)
                    ->references($parentTableId)
                    ->on($parentTableName)
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
            });
        }
    }
}
/**
 * Removes Foreign key contarins between two tables
 */
function removeForeignKey(string $tableName, string $foreignKey)
{
    Schema::table($tableName, function (Blueprint $table) use ($foreignKey) {
        $table->dropForeign([$foreignKey]);
    });
}
