<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class UserCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class UserCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation { update as traitUpdate; }
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\User::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/user');
        CRUD::setEntityNameStrings('user', 'users');

        if (backpack_user()->isAdmin != 1) {
            $this->crud->denyAccess(['list', 'create', 'update', 'delete', 'show']);
        }
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
        CRUD::column('email');
        CRUD::column('mobile');
        CRUD::column('roleId')->type('select')->label('Role')->entity('role')->attribute('role')->model('App\Models\Role');
        CRUD::column('isAdmin')->type('boolean')->label('Admin');
        CRUD::column('status')->type('select_from_array')->options([
            0 => 'For Approval',
            1 => 'Active',
            100 => 'Rejected'
        ]);

        CRUD::addButtonFromView('line', 'approve', 'approve', 'beginning');
        CRUD::addButtonFromView('line', 'reject', 'reject', 'beginning');
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(UserRequest::class);

        CRUD::field('name');
        CRUD::field('email');
        CRUD::field('password')->type('password');
        CRUD::field('mobile');
        CRUD::field('roleId')->type('select')->label('Role')->entity('role')->attribute('role')->model('App\Models\Role');
        CRUD::field('isAdmin')->type('boolean')->label('Admin');
        CRUD::field('designation');
        CRUD::field('status')->type('select_from_array')->options([
            0 => 'For Approval',
            1 => 'Active',
            100 => 'Rejected'
        ])->default(0);
    }

    public function approve($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = 1;
        $user->save();
        
        //On approve send email to user informing that the account is approved
        Mail::to($user->email)->send(new \App\Mail\UserApproved($user));

        \Alert::success('User approved successfully.')->flash();
        return redirect()->back();
    }

    public function reject($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->status = 100;
        $user->save();
        
        \Alert::warning('User rejected.')->flash();
        return redirect()->back();
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
        
        // Remove password requirement on update if not provided
        // $this->crud->field('password')->attributes(['required' => false]);
    }

    public function update()
    {
        $request = $this->crud->validateRequest();

        // If the password field is empty, remove it from the request so it's not updated
        if (empty($request->password)) {
            $request->request->remove('password');
        }

        return $this->traitUpdate();
    }
}
