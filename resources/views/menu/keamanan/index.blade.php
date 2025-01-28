@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <h1 class="h3 mb-4 text-gray-800">
                <i class="fas fa-shield-alt me-2"></i>Keamanan Sistem
            </h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list me-2"></i>Log Aktivitas
                    </h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="logAktivitasTable">
                            <thead>
                                <tr>
                                    <th>Waktu</th>
                                    <th>Pengguna</th>
                                    <th>Aktivitas</th>
                                    <th>Detail</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($logAktivitas as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d M Y H:i:s') }}</td>
                                    <td>
                                        <span class="badge 
                                            @if($log->user->role == 'admin') bg-danger 
                                            @elseif($log->user->role == 'operator') bg-warning 
                                            @else bg-secondary 
                                            @endif">
                                            {{ $log->user->name }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if(str_contains(strtolower($log->aktivitas), 'login')) bg-success 
                                            @elseif(str_contains(strtolower($log->aktivitas), 'logout')) bg-secondary 
                                            @elseif(str_contains(strtolower($log->aktivitas), 'error')) bg-danger 
                                            @else bg-info 
                                            @endif">
                                            {{ $log->aktivitas }}
                                        </span>
                                    </td>
                                    <td>{{ $log->detail }}</td>
                                    <td>{{ $log->ip_address }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-bar me-2"></i>Statistik Keamanan
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Login Berhasil</h5>
                                    <p class="card-text display-6">
                                        {{ $logAktivitas->where('aktivitas', 'like', '%login%')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card bg-danger text-white">
                                <div class="card-body">
                                    <h5 class="card-title">Percobaan Gagal</h5>
                                    <p class="card-text display-6">
                                        {{ $logAktivitas->where('aktivitas', 'like', '%error%')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Aktivitas Terakhir</h5>
                            @if($logAktivitas->isNotEmpty())
                                @php
                                    $latestLog = $logAktivitas->first();
                                @endphp
                                <p>
                                    <strong>{{ $latestLog->user->name }}</strong> 
                                    {{ $latestLog->aktivitas }} 
                                    pada {{ $latestLog->created_at->diffForHumans() }}
                                </p>
                            @else
                                <p>Tidak ada log aktivitas</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    $('#logAktivitasTable').DataTable({
        "order": [[0, "desc"]],
        "pageLength": 10,
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ entri",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        }
    });
});
</script>
@endsection
