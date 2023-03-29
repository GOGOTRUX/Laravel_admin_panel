<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Roles permissions
        // Permission::create(['name' => 'roles_manage']);
        // // Users permissions
        // Permission::create(['name' => 'users_manage']);
        // Permission::create(['name' => 'users_view']);
        // Permission::create(['name' => 'users_create']);
        // Permission::create(['name' => 'users_edit']);
        // Permission::create(['name' => 'users_delete']);
        // Role permissions
        // Permission::create(['name' => 'role_view']);
        // Permission::create(['name' => 'role_create']);
        // Permission::create(['name' => 'role_edit']);
        // Permission::create(['name' => 'role_delete']);
        // // Operators permissions
        // Permission::create(['name' => 'operator_manage']);
        // Permission::create(['name' => 'operator_block']);
        // Permission::create(['name' => 'operator_view']);
        // Permission::create(['name' => 'operator_create']);
        // Permission::create(['name' => 'operator_edit']);
        // Permission::create(['name' => 'operator_delete']);
        // Customers permissions
        // Permission::create(['name' => 'customer_manage']);
        // Permission::create(['name' => 'customer_view']);
        // Permission::create(['name' => 'customer_create']);
        // Permission::create(['name' => 'customer_edit']);
        // Permission::create(['name' => 'customer_delete']);
        
        // Subscriptions permissions
        // Permission::create(['name' => 'subscription_manage']);
        // Permission::create(['name' => 'subscription_view']);
        // Permission::create(['name' => 'subscription_create']);
        // Permission::create(['name' => 'subscription_edit']);
        // Permission::create(['name' => 'subscription_delete']);
        // Permission::create(['name' => 'subscription_final_approval']);
        // Permission::create(['name' => 'subscription_type_manage']);
        // Vehicles permissions
        // Permission::create(['name' => 'vehicle_manage']);
        // Permission::create(['name' => 'vehicle_view']);
        // Permission::create(['name' => 'vehicle_create']);
        // Permission::create(['name' => 'vehicle_edit']);
        // Permission::create(['name' => 'vehicle_delete']);
        // Permission::create(['name' => 'vehicle_facility_manage']);
        // Permission::create(['name' => 'vehicle_facility_view']);
        // Permission::create(['name' => 'vehicle_facility_create']);
        // Permission::create(['name' => 'vehicle_facility_edit']);
        // Permission::create(['name' => 'vehicle_facility_delete']);
        // Permission::create(['name' => 'vehicle_facility_master_manage']);
        // Feedback permissions
        // Permission::create(['name' => 'feedback_manage']);
        // Permission::create(['name' => 'feedback_view']);
        
        //operator block permission

        //order permissions
        // Permission::create(['name' => 'order_manage']);
        // Permission::create(['name' => 'order_view']);
        // Permission::create(['name' => 'order_create']);
        // Permission::create(['name' => 'order_edit']);
        // Permission::create(['name' => 'order_delete']);

        //information permission
        // Permission::create(['name' => 'information_manage']);
        // Permission::create(['name' => 'information_view']);
        // Permission::create(['name' => 'notification_view']);
        // Permission::create(['name' => 'notification_create']);
        // Permission::create(['name' => 'mail_view']);
        // Permission::create(['name' => 'mail_create']);

        //home screen permission
        // Permission::create(['name' => 'home_screen_manage']);
        // Permission::create(['name' => 'home_screen_view']);
        // Permission::create(['name' => 'home_screen_create']);
        // Permission::create(['name' => 'home_screen_edit']);
        // Permission::create(['name' => 'home_screen_delete']);

        //payment permission
        // Permission::create(['name' => 'payment_manage']);
        // Permission::create(['name' => 'payment_view']);
        
        /* //24 sep temp commented
        Permission::create(['name' => 'payment_edit']);
        Permission::create(['name' => 'payment_approve']);
        */
        
        // Verify various permission 
        // Permission::create(['name' => 'verification_manage']);
        // Permission::create(['name' => 'verify_subplan']);

        //24 sep 2019

        // Permission::create(['name' => 'customer_block']); 
        // Permission::create(['name' => 'sms_view']); 
        // Permission::create(['name' => 'sms_create']); 

        // // trip management permission
        // Permission::create(['name' => 'trip_manage']);
        // Permission::create(['name' => 'trip_view']);
        // Permission::create(['name' => 'realtimeassistance_view']);

        // // price management permission
        // Permission::create(['name' => 'price_manage']);
        // Permission::create(['name' => 'create_logic']);
        // Permission::create(['name' => 'create_factor']);

        // // reports management permission
        // Permission::create(['name' => 'report_manage']);
        
        // // settings management permission
        // Permission::create(['name' => 'setting_manage']);

        // // loyalty management permission
        // Permission::create(['name' => 'loyalty_manage']);

        // 5 oct 2019
        // dashboard management
        Permission::create(['name' => 'dashboard_manage']);
        Permission::create(['name' => 'dashboard_view']);

        // php artisan db:seed --class=PermissionSeeder
    }
}
