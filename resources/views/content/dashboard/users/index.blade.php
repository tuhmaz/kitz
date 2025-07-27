@extends('layouts/contentNavbarLayout')

@section('title', __('Users List'))

@section('vendor-style')
@vite([
    'resources/assets/vendor/libs/select2/select2.scss',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.scss',

])
@endsection

@section('vendor-script')
@vite([
    'resources/assets/vendor/libs/select2/select2.js',
    'resources/assets/vendor/libs/sweetalert2/sweetalert2.js',
    'resources/assets/vendor/libs/jquery/jquery.js',
    'resources/assets/js/pages/userlist.js'
])
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold m-0">
        <span class="text-muted fw-light">{{ __('User Management') }} /</span> {{ __('Users List') }}
    </h4>
    <div>
        @can('admin users')
        <button type="button" id="deleteSelectedBtn" class="btn btn-danger me-2" style="display: none;">
            <i class="ti ti-trash me-1"></i>{{ __('Delete Selected') }}
        </button>
        <button type="button" class="btn btn-primary" onclick="window.location.href='{{ route('dashboard.users.create') }}'">
            <i class="ti ti-plus me-1"></i>{{ __('Add User') }}
        </button>
        @endcan
    </div>
</div>

<div class="card">
    <div class="card-header border-bottom">
        <form id="filterForm" class="row g-3"
            data-users-url="{{ route('dashboard.users.index') }}"
            data-network-error="{{ __('Network error. Please check your connection.') }}"
            data-loading-error="{{ __('Error loading users. Please try again.') }}"
            data-delete-confirm="{{ __('Are you sure you want to delete this user?') }}"
            data-delete-multiple-confirm="{{ __('Are you sure you want to delete the selected users?') }}">
            <div class="col-md-5">
                <label class="form-label" for="UserRole">{{ __('Role') }}</label>
                <select id="UserRole" name="role" class="form-select select2">
                    <option value="">{{ __('All Roles') }}</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>
                            {{ ucfirst($role->name) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <label class="form-label" for="UserSearch">{{ __('Search') }}</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="ti ti-search"></i></span>
                    <input type="text" id="UserSearch" name="search" class="form-control"
                           placeholder="{{ __('Search by name or email') }}" value="{{ request('search') }}">
                </div>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="button" id="resetFiltersBtn" class="btn btn-secondary w-100">
                    <i class="ti ti-refresh me-1"></i>{{ __('Reset') }}
                </button>
            </div>
        </form>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="40">
                            <div class="form-check">
                                <input class="form-check-input select-all" type="checkbox" id="selectAll">
                                <label class="form-check-label" for="selectAll"></label>
                            </div>
                        </th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Role') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody id="users-table-body">
                    @include('content.dashboard.users.partials.users-table', ['users' => $users])
                </tbody>
            </table>
        </div>
    </div>

    <div class="card-footer">
        <div class="pagination pagination-outline-secondary">
          {{ $users->links('components.pagination.custom') }}
        </div>
    </div>
</div>
@endsection

@section('page-script')
<script>
    $(document).ready(function() {
        // Handle select all checkbox
        $('#selectAll').on('change', function() {
            const isChecked = $(this).prop('checked');
            $('.user-select-checkbox').prop('checked', isChecked);
            updateDeleteButtonVisibility();
        });

        // Handle individual checkboxes
        $(document).on('change', '.user-select-checkbox', function() {
            updateDeleteButtonVisibility();

            // Update select all checkbox state
            const allChecked = $('.user-select-checkbox:checked').length === $('.user-select-checkbox').length;
            $('#selectAll').prop('checked', allChecked);
        });

        // Handle delete selected button
        $('#deleteSelectedBtn').on('click', function() {
            if (!confirm($('#filterForm').data('delete-multiple-confirm'))) {
                return;
            }

            const selectedIds = [];
            $('.user-select-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            if (selectedIds.length === 0) {
                return;
            }

            // Create and submit a form to delete selected users
            const form = $('<form>', {
                'method': 'POST',
                'action': '{{ route("dashboard.users.destroy-multiple") }}'
            });

            form.append($('<input>', {
                'type': 'hidden',
                'name': '_token',
                'value': $('meta[name="csrf-token"]').attr('content')
            }));

            form.append($('<input>', {
                'type': 'hidden',
                'name': '_method',
                'value': 'DELETE'
            }));

            // Add selected user IDs
            selectedIds.forEach(function(id) {
                form.append($('<input>', {
                    'type': 'hidden',
                    'name': 'ids[]',
                    'value': id
                }));
            });

            form.appendTo('body').submit();
        });

        // Update delete button visibility based on selected checkboxes
        function updateDeleteButtonVisibility() {
            const hasSelected = $('.user-select-checkbox:checked').length > 0;
            $('#deleteSelectedBtn').toggle(hasSelected);
        }
    });
</script>
@endsection
