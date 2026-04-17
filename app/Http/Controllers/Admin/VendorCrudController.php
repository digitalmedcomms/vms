<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\VendorRequest;
use App\Models\VendorComment;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        CRUD::field('vendor_documents')
            ->type('view')
            ->view('admin.vendor.fields.vendor_documents')
            ->label('Documents');

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
     * Custom store — handles contacts (view-type) and document uploads.
     */
    public function store()
    {
        $this->crud->hasAccessOrFail('create');

        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $data = $this->crud->getStrippedSaveRequest($request);

        // Contacts
        if ($request->has('contacts')) {
            $raw = $request->input('contacts');
            $data['contacts'] = is_string($raw) ? json_decode($raw, true) : $raw;
        }

        // Documents
        $data['documents'] = $this->processUploadedDocuments($request, [], $data['name'] ?? 'vendor');

        // Legacy NOT NULL columns no longer used by the form — ensure they always have a value
        $data['contact_person']  = $data['contact_person']  ?? '';
        $data['email_address']   = $data['email_address']   ?? '';
        $data['contact_number']  = $data['contact_number']  ?? '';
        $data['tin']             = $data['tin']             ?? '';
        $data['website_url']     = $data['website_url']     ?? '';

        // Audit
        $data['created_by']   = backpack_user()->id;
        $data['created_when'] = now();

        $item = $this->crud->create($data);
        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Handle the update request — ensures contacts (view-type field),
     * logo (upload field) and document uploads are saved correctly.
     */
    public function update($id = null)
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $data = $this->crud->getStrippedSaveRequest($request);

        // Contacts
        if ($request->has('contacts')) {
            $raw = $request->input('contacts');
            $data['contacts'] = is_string($raw) ? json_decode($raw, true) : $raw;
        }

        // Skip logo if no new file was uploaded (prevents clearing the existing logo)
        if (!$request->hasFile('logo')) {
            unset($data['logo']);
        }

        // Documents — merge with existing, honour removals
        $entryId       = $request->get($this->crud->model->getKeyName()) ?? $id;
        $existingEntry = $this->crud->model->find($entryId);
        $existingDocs  = $existingEntry->documents ?? [];

        // Delete files that were marked for removal
        $toRemove = json_decode($request->input('remove_documents', '[]'), true) ?? [];
        foreach ($toRemove as $idx) {
            if (isset($existingDocs[$idx])) {
                Storage::disk('public')->delete($existingDocs[$idx]['path']);
                unset($existingDocs[$idx]);
            }
        }
        $existingDocs = array_values($existingDocs);

        // Append newly uploaded files
        $newDocs = $this->processUploadedDocuments($request, [], $data['name'] ?? $existingEntry->name ?? 'vendor');
        $data['documents'] = array_merge($existingDocs, $newDocs);

        // Legacy NOT NULL columns no longer used by the form — ensure they always have a value
        $data['contact_person']  = $data['contact_person']  ?? $existingEntry->contact_person  ?? '';
        $data['email_address']   = $data['email_address']   ?? $existingEntry->email_address   ?? '';
        $data['contact_number']  = $data['contact_number']  ?? $existingEntry->contact_number  ?? '';
        $data['tin']             = $data['tin']             ?? $existingEntry->tin             ?? '';
        $data['website_url']     = $data['website_url']     ?? $existingEntry->website_url     ?? '';

        // Audit
        $data['updated_by']   = backpack_user()->id;
        $data['updated_when'] = now();

        $item = $this->crud->update($entryId, $data);
        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Upload files from vendor_documents[] and return an array of document metadata.
     */
    private function processUploadedDocuments(Request $request, array $existingDocs, string $vendorName): array
    {
        $docs = $existingDocs;

        if (!$request->hasFile('vendor_documents')) {
            return $docs;
        }

        $vendorSlug = Str::slug($vendorName);
        $dateStamp  = now()->format('Ymd');   // e.g. 20260417

        foreach ($request->file('vendor_documents') as $i => $file) {
            if (!$file || !$file->isValid()) {
                continue;
            }

            $originalName = $file->getClientOriginalName();
            $ext          = $file->getClientOriginalExtension();
            $baseName     = Str::slug(pathinfo($originalName, PATHINFO_FILENAME));
            // Format: originalfilename_vendorname_YYYYMMDD.ext
            $storedName   = $baseName . '_' . $vendorSlug . '_' . $dateStamp . '.' . $ext;

            // Ensure a unique filename if one with that name already exists
            $storedName = $this->uniqueFileName('vendors/documents', $storedName);

            $path = $file->storeAs('vendors/documents', $storedName, 'public');

            $docs[] = [
                'original_name' => $originalName,
                'stored_name'   => $storedName,
                'path'          => $path,
                'uploaded_at'   => now()->toDateTimeString(),
            ];
        }

        return $docs;
    }

    /**
     * Append a counter to a filename if it already exists in storage.
     */
    private function uniqueFileName(string $directory, string $filename): string
    {
        $ext      = pathinfo($filename, PATHINFO_EXTENSION);
        $base     = pathinfo($filename, PATHINFO_FILENAME);
        $counter  = 1;
        $candidate = $filename;

        while (Storage::disk('public')->exists($directory . '/' . $candidate)) {
            $candidate = $base . '_' . $counter . '.' . $ext;
            $counter++;
        }

        return $candidate;
    }

    public function storeComment(Request $request, $id)
    {
        $this->crud->hasAccessOrFail('show');

        $request->validate([
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        VendorComment::create([
            'vendor_id'   => $id,
            'user_id'     => backpack_user()->userId,
            'rating'      => $request->rating,
            'comment'     => $request->comment,
            'insert_date' => now()->toDateTimeString(),
        ]);

        \Alert::success('Comment added successfully.')->flash();

        return redirect()->back();
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
