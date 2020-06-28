@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"><b>Hasil Penilaian</b></div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                            <th scope="col">Nama</th>
                            @foreach($topics as $topic)
                                <th scope="col">Materi {{ $topic->name }}</th>
                            @endforeach
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($names as $index => $name)
                            <tr>
                            <td>{{ $name }}</td>
                            @foreach($grades[$index] as $grade)
                                <td>{{ $grade }}</td>
                            @endforeach
                            </tr>    
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <br/>
            <a href="/course/<?php echo $id_course; ?>" class="btn btn-primary" id="back" role="button">Kembali ke Kelas</a>
        </div>
    </div>
</div>
@endsection