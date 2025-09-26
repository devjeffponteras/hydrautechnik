<?php

namespace Database\Seeders;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Permission::insert([
            ['name' => 'View Page','module' => 'page','description' => 'User can view page list and detail','routes' => '["pages.index","pages.show","pages.index.advance-search"]','methods' => '["index","show","advance_index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Page','module' => 'page','description' => 'User can create pages','routes' => '["pages.create","pages.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Page','module' => 'page','description' => 'User can edit pages','routes' => '["pages.edit","pages.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore page','module' => 'page','description' => 'User can delete and restore pages','routes' => '["pages.destroy","pages.delete","pages.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of Page','module' => 'page','description' => 'User can change status of pages','routes' => '["pages.change.status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View Album','module' => 'banner','description' => 'User can view album list and detail','routes' => '["albums.index","albums.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Album','module' => 'banner','description' => 'User can create albums','routes' => '["albums.create","albums.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Album','module' => 'banner','description' => 'User can edit albums','routes' => '["albums.edit","albums.update","albums.quick_update"]','methods' => '["edit","update","quick_update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore album','module' => 'banner','description' => 'User can delete and restore albums','routes' => '["albums.destroy","albums.destroy_many","albums.restore"]','methods' => '["destroy","destroy_many","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Manage File manager','module' => 'file_manager','description' => 'User can manage file manager','routes' => '["file-manager.show","file-manager.upload","file-manager.index"]','methods' => '["show","upload","index"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View menu','module' => 'menu','description' => 'User can view menu list and detail','routes' => '["menus.index","menus.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create Menu','module' => 'menu','description' => 'User can create menus','routes' => '["menus.create","menus.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit Menu','module' => 'menu','description' => 'User can edit menus','routes' => '["menus.edit","menus.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore menu','module' => 'menu','description' => 'User can delete and restore menus','routes' => '["menus.destroy","menus.destroy_many","menus.restore"]','methods' => '["destroy","destroy_many","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View news','module' => 'news','description' => 'User can view news list and detail','routes' => '["news.index","news.show","news.index.advance-search"]','methods' => '["index","show","advance_index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create News','module' => 'news','description' => 'User can create news','routes' => '["news.create","news.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit news','module' => 'news','description' => 'User can edit news','routes' => '["news.edit","news.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore News','module' => 'news','description' => 'User can delete and restore news','routes' => '["news.destroy","news.delete","news.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change Status of News','module' => 'news','description' => 'User can change status of news','routes' => '["news.change.status"]','methods' => '["change_status"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'View News Category','module' => 'news_category','description' => 'User can view news category list and details','routes' => '["news-categories.index","news-categories.show"]','methods' => '["index","show"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create news category','module' => 'news_category','description' => 'User can create news categories','routes' => '["news-categories.create","news-categories.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit news category','module' => 'news_category','description' => 'User can edit news categories','routes' => '["news-categories.edit","news-categories.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Delete/Restore news category','module' => 'news_category','description' => 'User can delete and restore news categories','routes' => '["news-categories.destroy","news-categories.delete","news-categories.restore"]','methods' => '["destroy","delete","restore"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit website settings','module' => 'website_settings','description' => 'User can edit website settings','routes' => '["website-settings.edit","website-settings.update","website-settings.update-contacts","website-settings.update-media-accounts","website-settings.update-data-privacy","website-settings.remove-logo","website-settings.remove-icon","website-settings.remove-media"]','methods' => '["edit","update","update_contacts","update_media_accounts","update_data_privacy","remove_logo","remove_icon","remove_media"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'View audit logs','module' => 'audit_logs','description' => 'User can view audit logs','routes' => '["audit-logs.index"]','methods' => '["index"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'View users','module' => 'user','description' => 'User can view user list and detail','routes' => '["users.index","users.show","user.search","user.activity.search"]','methods' => '["index","show","search","filter"]','user_id' => '1','is_view_page' => '1'],
            ['name' => 'Create user','module' => 'user','description' => 'User can create users','routes' => '["users.create","users.store"]','methods' => '["create","store"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Edit user','module' => 'user','description' => 'User can edit users','routes' => '["users.edit","users.update"]','methods' => '["edit","update"]','user_id' => '1','is_view_page' => '0'],
            ['name' => 'Change status of user','module' => 'user','description' => 'User can change status of users','routes' => '["users.deactivate","users.activate"]','methods' => '["deactivate","activate"]','user_id' => '1','is_view_page' => '0'],   

        ]);
    }
}
