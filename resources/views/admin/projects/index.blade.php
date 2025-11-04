@extends('admin.layouts.app')

@section('pagetitle')
Manage Projects
@endsection

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="mb-1">Projects</h3>
                <p class="text-muted mb-0">Manage projects on the website</p>
            </div>
            <a href="{{ route('projects.create') }}" class="btn btn-success"><i data-feather="plus"></i> Add Project</a>
        </div>
    </div>


            <!-- Search & Filter (products-like) -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i data-feather="filter" class="me-2"></i>
                                Search & Filter Projects
                            </h6>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('projects.index') }}">
                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="q" class="form-label fw-bold">Search Projects</label>
                                        <input name="q"
                                               id="q"
                                               type="search"
                                               class="form-control"
                                               placeholder="Search by project name..."
                                               value="{{ request('q') }}">
                                        <div class="form-text">Enter project name keywords</div>
                                    </div>

                                    <div class="col-md-4 mb-3">
                                        <label for="category" class="form-label fw-bold">Category</label>
                                        <select name="category" id="category" class="form-control">
                                            <option value="">All Categories</option>
                                            @foreach($categories as $key => $label)
                                                <option value="{{ $key }}" @if(request('category') == $key) selected @endif>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Filter by project category</div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label for="status" class="form-label fw-bold">Status</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="">All Status</option>
                                            @foreach($statuses as $sk => $sl)
                                                <option value="{{ $sk }}" @if(request('status') == $sk) selected @endif>{{ $sl }}</option>
                                            @endforeach
                                        </select>
                                        <div class="form-text">Filter by publication status</div>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i data-feather="search" class="me-1"></i>
                                                Search
                                            </button>
                                            <a href="{{ route('projects.index') }}" class="btn btn-outline-secondary">
                                                <i data-feather="x" class="me-1"></i>
                                                Clear
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i data-feather="list" class="me-2"></i>
                                All Projects
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            @if($projects->count())
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead class="table-light">
                                            <tr>
                                                <th class="px-4 py-3">#</th>
                                                <th class="px-4 py-3">Image</th>
                                                <th class="px-4 py-3">Name</th>
                                                <th class="px-4 py-3">Category</th>
                                                <th class="px-4 py-3">Status</th>
                                                <th class="px-4 py-3">Description</th>
                                                <th class="px-4 py-3 text-center">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($projects as $project)
                                            <tr>
                                                <td class="px-4 py-3"><span class="badge bg-light text-dark">{{ $loop->iteration }}</span></td>
                                                <td class="px-4 py-3">
                                                    @if(!empty($project->image))
                                                        <img src="{{ asset($project->image) }}" alt="project image" style="width:60px; height:60px; object-fit:cover;" class="rounded">
                                                    @else
                                                        <img src="{{ asset('images/products/prd1.jpg') }}" alt="placeholder" style="width:60px; height:60px; object-fit:cover;" class="rounded">
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">{{ $project->name }}</td>
                                                <td class="px-4 py-3">{{ $project->category ?? '—' }}</td>
                                                <td class="px-4 py-3">
                                                    @if(!empty($project->status) && strtoupper($project->status) == 'PUBLISHED')
                                                        <span class="badge bg-success">
                                                            <i data-feather="check-circle" style="width:12px; height:12px;" class="me-1"></i>
                                                            Published
                                                        </span>
                                                    @elseif(!empty($project->status) && strtoupper($project->status) == 'PRIVATE')
                                                        <span class="badge bg-secondary">
                                                            <i data-feather="lock" style="width:12px; height:12px;" class="me-1"></i>
                                                            Private
                                                        </span>
                                                    @else
                                                        <span class="badge bg-light text-dark">—</span>
                                                    @endif
                                                </td>
                                                <td class="px-4 py-3">{{ \Illuminate\Support\Str::limit(strip_tags($project->description ?? ''), 100) }}</td>
                                                <td class="px-4 py-3 text-center">
                                                    <div class="btn-group" role="group">
                                                        <a href="{{ url('/projects/'.$project->id) }}" class="btn btn-sm btn-primary me-1" target="_blank" title="View on site"><i data-feather="eye"></i></a>
                                                        <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-info me-1"><i data-feather="edit"></i></a>
                                                        <form action="{{ route('projects.destroy', $project->id) }}" method="POST" style="display:inline;" class="confirm-delete-form" data-name="{{ $project->name }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="button" class="btn btn-sm btn-danger btn-confirm-delete"><i data-feather="trash-2"></i></button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <div class="mb-3"><i data-feather="settings" style="width:48px; height:48px;" class="text-muted"></i></div>
                                    <h5 class="text-muted">No projects found</h5>
                                    <p class="text-muted">You can add a new project using the button above.</p>
                                    <a href="{{ route('projects.create') }}" class="btn btn-success"><i data-feather="plus" class="me-1"></i>Add Project</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete confirmation modal -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
              <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="confirmDeleteLabel">Confirm delete</h5>
                            <!-- Support both Bootstrap 5 (data-bs-dismiss) and Bootstrap 4 (data-dismiss) -->
                            <button type="button" class="btn-close" data-bs-dismiss="modal" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                  <div class="modal-body">
                    <p id="confirmDeleteMessage">Are you sure you want to delete this item?</p>
                  </div>
                  <div class="modal-footer">
                <!-- Support both Bootstrap 5 and 4 dismissal attributes -->
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger btn-sm" id="confirmDeleteButton">Delete</button>
                  </div>
                </div>
              </div>
            </div>

            <script>
                (function(){
                    var formToSubmit = null;
                    var deleteModalEl = document.getElementById('confirmDeleteModal');
                    var deleteModal = null;
                    try {
                        if (typeof bootstrap !== 'undefined' && deleteModalEl) {
                            deleteModal = new bootstrap.Modal(deleteModalEl);
                        }
                    } catch(e) {
                        // bootstrap not present, will fallback to native confirm
                        deleteModal = null;
                    }

                    function onConfirmButtonClick(e) {
                        if (formToSubmit) {
                            formToSubmit.submit();
                            formToSubmit = null;
                        }
                    }

                    document.querySelectorAll('.btn-confirm-delete').forEach(function(btn){
                        btn.addEventListener('click', function(ev){
                            ev.preventDefault();
                            var form = btn.closest('form.confirm-delete-form');
                            if (!form) return;
                            var name = form.dataset.name || 'this item';
                            var message = "Are you sure you want to delete '" + name + "'? This action cannot be undone.";

                            if (deleteModal) {
                                document.getElementById('confirmDeleteMessage').textContent = message;
                                formToSubmit = form;
                                deleteModal.show();
                            } else {
                                // fallback
                                if (window.confirm(message)) {
                                    form.submit();
                                }
                            }
                        });
                    });

                    var confirmBtn = document.getElementById('confirmDeleteButton');
                    if (confirmBtn) confirmBtn.addEventListener('click', onConfirmButtonClick);
                })();
            </script>
        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="https://unpkg.com/feather-icons"></script>
<script>document.addEventListener('DOMContentLoaded', function(){ if(window.feather) feather.replace(); });</script>
@endsection
