<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class VendorCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class VendorCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Vendor::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/vendor');
        CRUD::setEntityNameStrings('vendor', 'vendors');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('average_rating')
            ->view('vendor.backpack.ui.columns.star_rating')
            ->label('Rating');
        CRUD::column('country_id')->type('select')->label('Country')->entity('country')->attribute('name')->model('App\Models\Country');
        CRUD::column('vendor_type_id')->type('select')->label('Type')->entity('type')->attribute('name')->model('App\Models\VendorType');
        CRUD::column('status')->type('select_from_array')->options([0 => 'Inactive', 1 => 'Active']);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(VendorRequest::class);
        $this->crud->setCreateView('admin.vendor.create');
        $this->crud->setUpdateView('admin.vendor.edit');

        CRUD::field('logo')
            ->type('upload')
            ->label('Vendor Logo');

        CRUD::field('name')
            ->label('Name')
            ->attributes(['placeholder' => 'Vendor Name']);

        CRUD::field('address')
            ->type('textarea')
            ->label('Address');

        CRUD::field('vendor_type_id')
            ->type('select')
            ->label('Vendor Type')
            ->entity('type')
            ->attribute('name')
            ->model('App\Models\VendorType')
            ->placeholder('-- Select Vendor Type --');

        CRUD::field('country_id')
            ->type('select')
            ->label('Country')
            ->entity('country')
            ->attribute('name')
            ->model('App\Models\Country')
            ->placeholder('-- Select Country --');

        CRUD::field('tin')
            ->label('TIN')
            ->attributes(['placeholder' => 'TIN']);

        CRUD::field('contacts')
            ->type('view')
            ->view('admin.vendor.fields.dynamic_contacts')
            ->label('Contact Information');

        CRUD::field('website_url')
            ->type('url')
            ->label('Website URL')
            ->attributes(['placeholder' => 'Website URL']);

        CRUD::field('status')
            ->type('select_from_array')
            ->options([1 => 'Active', 0 => 'Inactive'])
            ->default(1);
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

    /**
     * Handle the update request — ensures contacts (view-type field)
     * and logo (upload field) are saved correctly.
     */
    public function update($id = null)
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();

        // Register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // Get the standard stripped save data (view-type fields are excluded by Backpack)
        $data = $this->crud->getStrippedSaveRequest($request);

        // Manually include contacts (JSON string from the hidden input)
        if ($request->has('contacts')) {
            $raw = $request->input('contacts');
            $data['contacts'] = is_string($raw) ? json_decode($raw, true) : $raw;
        }

        // Skip logo if no new file was uploaded (prevents clearing the existing logo)
        if (!$request->hasFile('logo')) {
            unset($data['logo']);
        }

        // Stamp updated_by / updated_when
        $data['updated_by']   = backpack_user()->id;
        $data['updated_when'] = now();

        $entryId = $request->get($this->crud->model->getKeyName()) ?? $id;
        $item = $this->crud->update($entryId, $data);
        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    protected function setupShowOperation()
    {
        CRUD::setValidation(VendorRequest::class);
        $this->crud->setShowView('admin.vendor.show');

        $this->setupListOperation();

        CRUD::column('website_url')->type('url');
        CRUD::column('address')->type('textarea');
        CRUD::column('tin')->label('TIN');
        CRUD::column('contact_person');

        // Display Comments
        CRUD::column('comments_section')
            ->type('custom_html')
            ->value('<hr><h3>Comments</h3>');

        CRUD::column('comments')
            ->type('relationship')
            ->label('Vendor Comments')
            ->entity('comments')
            ->attribute('comment')
            ->model('App\Models\VendorComment')
            ->view('vendor.backpack.ui.columns.vendor_comments_list'); // Custom view for list of comments
    }
}
