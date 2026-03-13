<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorCommentRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class VendorCommentCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VendorCommentCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\VendorComment::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vendor-comment');
        CRUD::setEntityNameStrings('vendor comment', 'vendor comments');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('vendor_id')->type('select')->label('Vendor')->entity('vendor')->attribute('name')->model('App\Models\Vendor');
        CRUD::column('rating')->type('number');
        CRUD::column('comment')->type('text');
        CRUD::column('insert_date')->type('datetime');
        CRUD::column('user_id')->type('select')->label('User')->entity('user')->attribute('name')->model('App\Models\User');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(VendorCommentRequest::class);

        CRUD::field('vendor_id')->type('select')->label('Vendor')->entity('vendor')->attribute('name')->model('App\Models\Vendor');
        CRUD::field('rating')->type('number');
        CRUD::field('comment')->type('textarea');
        CRUD::field('user_id')->type('select')->label('User')->entity('user')->attribute('name')->model('App\Models\User');
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
