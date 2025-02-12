<?php

namespace App\Livewire\Users;

use App\Models\User;
use App\Models\Department;
use Illuminate\View\View;
use Livewire\Component;
use Livewire\WithPagination;

class ViewAll extends Component
{
    use WithPagination;

    public $search = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selectedDepartment = [];
    public $selectedRole = [];
    public $selectedStatus = [];

    // Modal States
    public $showCreateModal = false;
    public $showEditModal = false;

    // Form Data
    public $userId = null;
    public $name = '';
    public $email = '';
    public $department_id = null;
    public $role = '';
    public $position = '';
    public $employee_id = '';
    public $phone = '';
    public $status = 'active';
    public $password = '';
    public $password_confirmation = '';

    protected $roleOptions = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'auditor' => 'Auditor',
        'user' => 'User'
    ];

    protected $statusOptions = [
        'active' => 'Active',
        'inactive' => 'Inactive'
    ];

    protected $rules = [
        'name' => 'required|min:2|max:255',
        'email' => 'required|email|unique:users,email',
        'department_id' => 'required|exists:departments,id',
        'role' => 'required|in:super_admin,admin,auditor,user',
        'position' => 'required|string|max:255',
        'employee_id' => 'required|string|max:50',
        'phone' => 'nullable|string|max:20',
        'status' => 'required|in:active,inactive',
        'password' => 'required|min:8|confirmed',
    ];

    public function mount(): void
    {
        // Adjust rules if user is not super admin
        if (!auth()->user()->role === 'super_admin') {
            unset($this->roleOptions['super_admin']);
        }
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function sortBy($field): void
    {
        $this->sortDirection = $this->sortField === $field
            ? $this->sortDirection === 'asc' ? 'desc' : 'asc'
            : 'asc';
        $this->sortField = $field;
    }

    public function createModal(): void
    {
        $this->resetForm();
        $this->showCreateModal = true;
    }

    public function editUser($userId): void
    {
        $user = User::findOrFail($userId);

        // Check authorization
        if (!auth()->user()->role === 'super_admin' &&
            auth()->user()->organization_id !== $user->organization_id) {
            return;
        }

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->department_id = $user->department_id;
        $this->role = $user->role;
        $this->position = $user->position;
        $this->employee_id = $user->employee_id;
        $this->phone = $user->phone;
        $this->status = $user->status;

        // Update validation rules for edit
        $this->rules['email'] = 'required|email|unique:users,email,' . $this->userId;
        $this->rules['password'] = 'nullable|min:8|confirmed';

        $this->showEditModal = true;
    }

    public function createUser(): void
    {
        $this->validate();

        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'department_id' => $this->department_id,
            'role' => $this->role,
            'position' => $this->position,
            'employee_id' => $this->employee_id,
            'phone' => $this->phone,
            'status' => $this->status,
            'password' => bcrypt($this->password),
            'organization_id' => auth()->user()->organization_id,
        ]);

        $this->resetForm();
        $this->dispatch('user-saved');
    }

    public function updateUser(): void
    {
        $this->validate();

        $user = User::findOrFail($this->userId);

        $data = [
            'name' => $this->name,
            'email' => $this->email,
            'department_id' => $this->department_id,
            'role' => $this->role,
            'position' => $this->position,
            'employee_id' => $this->employee_id,
            'phone' => $this->phone,
            'status' => $this->status,
        ];

        if ($this->password) {
            $data['password'] = bcrypt($this->password);
        }

        $user->update($data);

        $this->resetForm();
        $this->dispatch('user-updated');
    }

    public function resetForm(): void
    {
        $this->reset(['showCreateModal', 'showEditModal', 'userId', 'name', 'email',
            'department_id', 'role', 'position', 'employee_id', 'phone', 'status',
            'password', 'password_confirmation']);
    }

    public function render(): View
    {
        $query = User::query()
            ->when(!auth()->user()->role === 'super_admin', function($query) {
                $query->where('organization_id', auth()->user()->organization_id);
            })
            ->when($this->search, function($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('employee_id', 'like', "%{$search}%");
                });
            })
            ->when($this->selectedDepartment, function($query) {
                $query->where('department_id', $this->selectedDepartment);
            })
            ->when($this->selectedRole, function($query) {
                $query->where('role', $this->selectedRole);
            })
            ->when($this->selectedStatus, function($query) {
                $query->where('status', $this->selectedStatus);
            })
            ->with(['department'])
            ->orderBy($this->sortField, $this->sortDirection);

        $departments = Department::where('organization_id', auth()->user()->organization_id)
            ->orderBy('name')
            ->get();

        return view('livewire.users.view-all', [
            'users' => $query->paginate(10),
            'departments' => $departments,
            'roleOptions' => $this->roleOptions,
            'statusOptions' => $this->statusOptions,
        ]);
    }
}
