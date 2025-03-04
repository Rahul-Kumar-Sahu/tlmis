@extends('layouts.layout_admin')
@section('styles')
<link rel="stylesheet" href="{{asset('assets/libs/datatable/dataTables.bootstrap5.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/libs/datatable/buttons.dataTables.min.css')}}"/>
<style>
    .min-card-height{
        height: 460px;
        min-height: 460px;
    }
    .check_all,.task_check{
        height: 25px;
        width: 25px;
        margin: auto;
    }
</style>
@endsection
@section('content')
<div class=row>
    <div class="col-xl-12">
        <form id="tl_form" action="{{ $review ? route('tl_update', $review->id) : route('tl_save') }}" autocomplete="off" method="post">
            @csrf
            @if($review)
            @method('PUT')
            <input type="hidden" name="cmd" id="cmd" value="UPD">
            @else
            <input type="hidden" name="cmd" id="cmd" value="INS">
            @endif
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h5 class="card-title mb-0 flex-grow-1">{{ $review ? 'Edit TL Review' : 'New TL Review' }}  / <small> {{ $review ? 'समीक्षा सुधारें' : 'समीक्षा जोड़ें' }}</small></h5>
                        <div class="flex-shrink-0">
                            <div class="d-flex flex-wrap gap-2">
                                <a href="{{ route('tl_view') }}" class="btn btn-danger add-btn">
                                    <i class="bx bx-list-ul align-bottom me-1"></i> View List
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-5">
                            <div class="form-group m-2">
                                <label for="tl_date" class="form-label">TL Review Date / <small>समीक्षा की तिथि</small></label>
                                <input type="date" name="tl_date" id="tl_date" class="form-control" value="{{ $review->tl_date ?? '' }}">
                            </div>
                            <div class="form-group m-2">
                                <label for="tl_time" class="form-label">TL Review Time / <small>समीक्षा की समय</small></label>
                                <input type="time" name="tl_time" id="tl_time" class="form-control" value="{{ $review->tl_time ?? '' }}">
                            </div>
                            <div class="form-group m-2">
                                <label for="tl_title" class="form-label">Review Title / <small>समीक्षा का शीर्षक</small></label>
                                <input type="text" name="tl_title" id="tl_title" class="form-control" placeholder="e.g.TL Review of Tasks" value="{{ $review->tl_title ?? '' }}">
                            </div>
                            <div class="form-group m-2">
                                <label for="tl_desc" class="form-label">Review Description / <small>समीक्षा का विवरण</small></label>
                                <textarea name="tl_desc" id="tl_desc" class="form-control" placeholder="Enter Review Agenda Here">{{ $review->tl_desc ?? '' }}</textarea>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-group m-2">
                                <label for="" class="form-label">Select Tasks To Included in This Review / <small>समीक्षा मे सम्मिलित करने के लिए कार्य चुनें</small></label>
                            </div>
                            <div class="table-responsive min-card-height">
                                <table class="table table-bordered datatable mb-0">
                                    <thead>
                                        <tr>
                                            <th width="15%" class="text-center"><input type="checkbox" class="form-check check_all"></th>
                                            <th width="20%">Task Title</th>
                                            <th width="30%">Departments</th>
                                            <th width="15%">Due Date</th>
                                            <th width="15%">Status</th>
                                            <th width="10%">Priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($data['tasks'] as $value)
                                        @php
                                        $sts = "Inprogress";
                                        $color = "primary";
                                        if ($value->status == "P") { $sts = "Pending"; $color = "secondary"; }
                                        elseif ($value->status == "C") { $sts = "Completed"; $color = "success"; }
                                        @endphp
                                        <tr>
                                            <td class="text-center">
                                                <input type="checkbox" value="{{ $value->task_id }}" name="task_id[]" class="form-check task_check"
                                                    {{ $review && in_array($value->task_id, $review->tasks->pluck('task_id')->toArray()) ? 'checked' : '' }}>
                                            </td>
                                            <td>{{ $value->title }}</td>
                                            <td class="client_name">
                                                @foreach ($value->taskDepartments as $val)
                                                {{ $loop->index + 1 }}. {{ $val->department->department_hin }}<br>
                                                @endforeach
                                            </td>
                                            <td class="due_date">{{ $value->due_date }}</td>
                                            <td class="status">
                                                <span class="badge bg-{{ $color }}-subtle text-{{ $color }} text-uppercase">{{ $sts }}</span>
                                            </td>
                                            <td class="priority">
                                                <span class="badge bg-{{ $value->fk_task_priority_id == 1 ? 'success' : 'danger' }} text-uppercase">{{ $value->taskPriority->priority }}</span>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="flex justify-end">
                        <div class="ms-auto">
                            <button type="reset" class="btn btn-primary btn-lg">Reset</button>
                            <button type="submit" class="btn btn-success btn-lg">Submit</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('assets/js/pages/form-wizard.init.js')}}"></script>
<script src="{{asset('assets/libs/datatable/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/dataTables.buttons.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/buttons.flash.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/buttons.html5.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/buttons.print.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/jszip.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/pdfmake.min.js')}}"></script>
<script src="{{asset('assets/libs/datatable/vfs_fonts.js')}}"></script>
<script>
    $(document).ready(function() {
        $('.datatable').DataTable({
            language: {
                search: "Search:",
            },
            
        });
    });
    $('#tl_form').submit(function(e) {
        e.preventDefault();
        if (!validateForm()) {
            return;
        }
        let formData = new FormData(this);
        let selectedTasks = [];
        $('.task_check:checked').each(function() {
            selectedTasks.push($(this).val());
        });
        formData.append('task_id', JSON.stringify(selectedTasks));
        $.ajax({
            url: 'tl-save',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $(':input[type="submit"]').prop('disabled', true);
            },
            success: function(response) {
                if (response.status === 'success') {
                    SwalWithCustomSettings.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $('#tl_form')[0].reset();
                            $('.task_check').prop('checked', false);
                            window.location.href = "{{ route('tl_view') }}";
                        }
                    });
                }
            },
            error: function(xhr) {
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    let errorMessage = Object.values(xhr.responseJSON.errors)
                        .map(error => error.join('<br>'))
                        .join('<br>');
                    showAlert(errorMessage, 'error');
                } else {
                    showAlert('Something went wrong. Please try again!', 'error');
                }
            },
            complete: function() {
                $(':input[type="submit"]').prop('disabled', false);
            }
        });
    });

    function validateForm() {
        let isValid = true;
        if ($('#tl_date').val() == '' || $('#tl_time').val() == '') {
            showAlert('Please fill all required inputs.', 'error');
            isValid = false;
        }
        if ($('.task_check:checked').length === 0) {
            showAlert('At least one task must be selected for review.', 'error');
            isValid = false;
        }
        return isValid;
    }
    document.addEventListener('DOMContentLoaded', function() {
        const today = new Date();
        const formattedDate = today.toISOString().split('T')[0];
        document.getElementById('tl_date').value = formattedDate;
    });
    document.addEventListener('DOMContentLoaded', function() {
        const now = new Date();
        const formattedTime = now.toTimeString().split(' ')[0].slice(0, 5);
        document.getElementById('tl_time').value = formattedTime;
    });
    document.querySelector('.check_all').addEventListener('click', function() {
        let isChecked = this.checked;
        let taskCheckboxes = document.querySelectorAll('.task_check');
        taskCheckboxes.forEach(function(checkbox) {
            checkbox.checked = isChecked;
        });
    });
</script>
@endsection
