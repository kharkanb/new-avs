@extends('layouts.tech')

@section('title', 'لیست تجهیزات')

@section('content')
<div class="container">
    <h2 class="mb-4"><i class="bi bi-hdd-stack"></i> لیست تجهیزات</h2>

    <div class="card">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>نوع تجهیز</th>
                        <th>کد اسکادا</th>
                        <th>پست</th>
                        <th>برند</th>
                        <th>وضعیت</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($equipments as $index => $equipment)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $equipment->equipment_type }}</td>
                        <td>{{ $equipment->scada_code ?? '---' }}</td>
                        <td>{{ $equipment->post->name ?? '---' }}</td>
                        <td>{{ $equipment->switch_brand ?? '---' }}</td>
                        <td>
                            @if($equipment->is_active)
                                <span class="badge bg-success">فعال</span>
                            @else
                                <span class="badge bg-secondary">غیرفعال</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('tech.equipments.show', $equipment->id) }}" class="btn btn-sm btn-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('tech.equipments.edit', $equipment->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection